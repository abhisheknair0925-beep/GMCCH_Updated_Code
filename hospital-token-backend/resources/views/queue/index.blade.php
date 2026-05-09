@extends('layouts.app')

@section('title', 'Live Queue - ' . $unit->name)

@section('content')
<div style="width: 100%;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 class="font-abel" style="font-size: 2.5rem; color: var(--primary); margin: 0; text-transform: uppercase; letter-spacing: 2px;">Live Queue</h2>
            <p style="color: var(--text-muted); margin: 0.5rem 0 0 0;">{{ $unit->name }} | Date: <strong>{{ $date }}</strong></p>
        </div>
        <div style="text-align: right;">
            <span class="badge badge-active" style="padding: 0.8rem 1.5rem; font-size: 1rem;">LIVE SESSION</span>
        </div>
    </div>

    <div class="grid-cols-3" style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        
        {{-- Left: Current Token Display --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="glass-card" style="padding: 3rem; text-align: center; background: linear-gradient(135deg, #ffffff 0%, #fff5fa 100%); border: 2px solid var(--primary); display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <h3 class="font-abel" style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 3px; font-size: 0.9rem; margin-bottom: 1rem;">Now Serving</h3>
                <div id="current-token-number" style="font-size: 8rem; font-weight: 900; color: var(--primary); line-height: 1; margin: 1rem 0;">
                    {{ $currentToken->token_number ?? '---' }}
                </div>
                <div id="current-patient-name" style="font-size: 1.5rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem;">
                    {{ $currentToken->user->name ?? 'No active tokens' }}
                </div>
                <div id="current-type" class="badge badge-active" style="background: var(--primary); color: white;">
                    {{ strtoupper($currentToken->type ?? 'NONE') }}
                </div>
            </div>

            <button id="btn-call-next" onclick="callNext()" class="btn-primary" style="padding: 1.5rem; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                <i class="fas fa-bullhorn"></i> CALL NEXT PATIENT
            </button>
        </div>

        {{-- Right: Queue List Table --}}
        <div class="table-card" style="margin: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Token</th>
                            <th>Patient Name</th>
                            <th>Slot Time</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="queue-table-body">
                        @forelse($upcoming as $booking)
                            <tr class="reveal" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                                <td><strong style="color: var(--primary);">#{{ $booking->token_number }}</strong></td>
                                <td style="font-weight: 600;">{{ $booking->user->name }}</td>
                                <td>{{ $booking->slot_time ?? 'N/A' }}</td>
                                <td>
                                    @if($booking->type == 'chemo')
                                        <span style="color: #8b5cf6;"><i class="fas fa-pills mr-1"></i> Chemo</span>
                                    @else
                                        <span style="color: #0ea5e9;"><i class="fas fa-user-md mr-1"></i> Follow up</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-active">Waiting</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 5rem; color: var(--text-muted);">
                                    No upcoming patients in the queue.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    async function callNext() {
        const btn = document.getElementById('btn-call-next');
        const originalText = btn.innerHTML;
        
        try {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            const response = await fetch("{{ route('queue.callNext') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    unit_id: "{{ $unit->id }}"
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update Current Token Display
                if (data.currentToken) {
                    document.getElementById('current-token-number').innerText = data.currentToken.token_number;
                    document.getElementById('current-patient-name').innerText = data.currentToken.patient_name;
                    document.getElementById('current-type').innerText = data.currentToken.type.toUpperCase();
                    document.getElementById('current-type').style.display = 'inline-block';
                } else {
                    document.getElementById('current-token-number').innerText = '---';
                    document.getElementById('current-patient-name').innerText = 'No active tokens';
                    document.getElementById('current-type').style.display = 'none';
                }

                // Update Upcoming Table
                const tbody = document.getElementById('queue-table-body');
                tbody.innerHTML = '';

                if (data.upcoming.length > 0) {
                    data.upcoming.forEach((b, index) => {
                        const row = `
                            <tr class="reveal" style="animation-delay: ${index * 0.05}s;">
                                <td><strong style="color: var(--primary);">#${b.token_number}</strong></td>
                                <td style="font-weight: 600;">${b.patient_name}</td>
                                <td>${b.slot_time || 'N/A'}</td>
                                <td>
                                    ${b.type === 'chemo' 
                                        ? '<span style="color: #8b5cf6;"><i class="fas fa-pills mr-1"></i> Chemo</span>' 
                                        : '<span style="color: #0ea5e9;"><i class="fas fa-user-md mr-1"></i> Follow up</span>'}
                                </td>
                                <td><span class="badge badge-active">Waiting</span></td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 5rem; color: var(--text-muted);">No upcoming patients in the queue.</td></tr>';
                }
            }
        } catch (error) {
            console.error('Queue error:', error);
            alert('An error occurred while updating the queue.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
</script>
@endpush
@endsection
