<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class HospitalUserController extends Controller
{
    /**
     * Number of rows inserted per database batch.
     * Keeps memory usage low and avoids giant single queries.
     */
    private const CHUNK_SIZE = 50;

    private function checkAccess(Request $request)
    {
        if (!$request->user() instanceof Hospital) {
            abort(403, 'Unauthorized. Admin access only.');
        }
    }

    /**
     * Search for a user by CR Number.
     */
    public function index(Request $request)
    {
        $this->checkAccess($request);

        $crno = $request->query('crno');
        
        if ($crno) {
            $formattedCrno = User::formatCrno($crno);
            $user = User::where('crno', $formattedCrno)->first();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found', 'data' => []], 404);
            }
            return response()->json(['success' => true, 'data' => [$user]]);
        }

        $users = User::orderBy('id', 'desc')->get();
        return response()->json(['success' => true, 'data' => $users]);
    }

    /**
     * Add a single user manually.
     */
    public function store(Request $request)
    {
        $this->checkAccess($request);

        if ($request->has('crno')) {
            $request->merge(['crno' => User::formatCrno($request->crno)]);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'crno'        => 'required|string|unique:users,crno',
            'user_age'    => 'nullable|integer|min:0|max:150',
            'user_gender' => 'nullable|string|max:20',
            'password'    => 'nullable|string|min:6',
        ]);

        $password = $request->password ? Hash::make($request->password) : Hash::make($request->crno);

        $user = User::create([
            'name'        => $request->name,
            'crno'        => $request->crno,
            'user_age'    => $request->user_age,
            'user_gender' => $request->user_gender,
            'password'    => $password,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data'    => $user
        ], 201);
    }

    /**
     * Bulk import users from a CSV file.
     *
     * Strategy:
     *  1. Reject files that exceed MAX_ROWS immediately (before any DB work).
     *  2. Validate headers — file must contain 'name' and 'crno' columns.
     *  3. Process in CHUNK_SIZE batches using DB::table()->insert() for speed.
     *  4. Collect per-row errors without aborting the whole import.
     *  5. Wrap everything in a transaction — rolled back only on unexpected exceptions.
     */
    public function bulkStore(Request $request)
    {
        $this->checkAccess($request);

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120', // 5 MB max file size
        ]);

        $file   = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return response()->json(['success' => false, 'message' => 'Could not read the uploaded file.'], 422);
        }

        // ── Step 1: Read & validate header row ───────────────────────────────
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return response()->json(['success' => false, 'message' => 'The CSV file is empty.'], 422);
        }

        // Normalize header names (trim whitespace, lowercase)
        $header = array_map(fn($col) => strtolower(trim($col)), $header);

        $requiredColumns = ['name', 'crno'];
        $missingColumns  = array_diff($requiredColumns, $header);

        if (!empty($missingColumns)) {
            fclose($handle);
            return response()->json([
                'success' => false,
                'message' => 'Missing required columns: ' . implode(', ', $missingColumns) . '. The CSV must have "name" and "crno" columns.'
            ], 422);
        }

        // ── Step 2: Count rows ───────────────────────────────────────────────
        $rowCount = 0;
        while (fgetcsv($handle) !== false) {
            $rowCount++;
        }

        if ($rowCount === 0) {
            fclose($handle);
            return response()->json(['success' => false, 'message' => 'The CSV file has no data rows.'], 422);
        }

        // ── Step 3: Rewind and process in batches ────────────────────────────
        rewind($handle);
        fgetcsv($handle); // skip header again

        $importedCount = 0;
        $skippedCount  = 0;
        $errors        = [];
        $batch         = [];
        $rowNumber     = 1; // 1-based for user-facing error messages
        $now           = now()->toDateTimeString();

        // Pre-fetch existing CRNOs to avoid N+1 exists() queries inside the loop
        $existingCrnos = User::pluck('crno')->flip()->all();

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // Skip completely blank rows
                if (count(array_filter($row, fn($v) => trim($v) !== '')) === 0) {
                    continue;
                }

                if (count($row) !== count($header)) {
                    $errors[] = "Row {$rowNumber}: column count mismatch — skipped.";
                    $skippedCount++;
                    continue;
                }

                $data = array_combine($header, $row);
                $name = trim($data['name'] ?? '');
                $crno = trim($data['crno'] ?? '');
                
                $crno = User::formatCrno($crno);

                // Basic per-row validation
                if (empty($name) || empty($crno)) {
                    $errors[] = "Row {$rowNumber}: 'name' and 'crno' cannot be empty — skipped.";
                    $skippedCount++;
                    continue;
                }

                if (isset($existingCrnos[$crno])) {
                    $errors[] = "Row {$rowNumber}: CRNO '{$crno}' already exists — skipped.";
                    $skippedCount++;
                    continue;
                }

                // Mark as seen so duplicates within the same file are caught
                $existingCrnos[$crno] = true;

                $batch[] = [
                    'name'        => $name,
                    'crno'        => $crno,
                    'user_age'    => isset($data['user_age']) && is_numeric($data['user_age']) ? (int)$data['user_age'] : null,
                    'user_gender' => trim($data['user_gender'] ?? '') ?: null,
                    'password'    => Hash::make($crno),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];

                // Flush batch when CHUNK_SIZE is reached
                if (count($batch) >= self::CHUNK_SIZE) {
                    DB::table('users')->insert($batch);
                    $importedCount += count($batch);
                    $batch = [];
                }
            }

            // Insert any remaining rows
            if (!empty($batch)) {
                DB::table('users')->insert($batch);
                $importedCount += count($batch);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            \Log::error($e);
            $message = ($e instanceof \Illuminate\Database\QueryException || $e instanceof \PDOException)
                ? 'An unexpected database error occurred.'
                : $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => 'Bulk import failed: ' . $message
            ], 500);
        }

        fclose($handle);

        return response()->json([
            'success'  => true,
            'message'  => "Import complete. {$importedCount} users added, {$skippedCount} skipped.",
            'summary'  => [
                'imported' => $importedCount,
                'skipped'  => $skippedCount,
                'total'    => $rowCount,
            ],
            'errors'   => $errors
        ], 200);
    }
}
