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
     */
    private const CHUNK_SIZE = 50;

    private function checkAccess(Request $request)
    {
        if (!$request->user() instanceof Hospital) {
            abort(403, 'Unauthorized. Admin access only.');
        }
    }

    /**
     * List all users or search by CR Number.
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
     * Get a single user by ID.
     */
    public function show(Request $request, $id)
    {
        $this->checkAccess($request);

        $user = User::findOrFail($id);
        return response()->json(['success' => true, 'data' => $user]);
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
     * Update an existing user's details.
     */
    public function update(Request $request, $id)
    {
        $this->checkAccess($request);

        $user = User::findOrFail($id);

        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'user_age'    => 'nullable|integer|min:0|max:150',
            'user_gender' => 'nullable|string|max:20',
            'password'    => 'nullable|string|min:6',
        ]);

        $data = $request->only(['name', 'user_age', 'user_gender']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data'    => $user->fresh()
        ]);
    }

    /**
     * Delete a user.
     * Guard: cannot delete if user has active bookings.
     */
    public function destroy(Request $request, $id)
    {
        $this->checkAccess($request);

        $user = User::findOrFail($id);

        $activeBookings = $user->bookings()->where('status', 'active')->count();
        if ($activeBookings > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete user. They have active bookings.'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Bulk import users from a CSV file.
     */
    public function bulkStore(Request $request)
    {
        $this->checkAccess($request);

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file   = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return response()->json(['success' => false, 'message' => 'Could not read the uploaded file.'], 422);
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return response()->json(['success' => false, 'message' => 'The CSV file is empty.'], 422);
        }

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

        $rowCount = 0;
        while (fgetcsv($handle) !== false) {
            $rowCount++;
        }

        if ($rowCount === 0) {
            fclose($handle);
            return response()->json(['success' => false, 'message' => 'The CSV file has no data rows.'], 422);
        }

        rewind($handle);
        fgetcsv($handle);

        $importedCount = 0;
        $skippedCount  = 0;
        $errors        = [];
        $batch         = [];
        $rowNumber     = 1;
        $now           = now()->toDateTimeString();

        $existingCrnos = User::pluck('crno')->flip()->all();

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

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

                if (count($batch) >= self::CHUNK_SIZE) {
                    DB::table('users')->insert($batch);
                    $importedCount += count($batch);
                    $batch = [];
                }
            }

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
