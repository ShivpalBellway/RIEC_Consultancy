@extends('layouts.admin')

@section('title', 'Agent Partners Management')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-handshake-angle text-gold"></i>
                <span>Agent Partner Network</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Review pending agent registrations, approve applications, and manage partner accounts</p>
        </div>

        @if($pendingCount > 0)
        <div class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-xs font-bold flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
            <span>{{ $pendingCount }} Pending Agent Approvals</span>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.agents.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-gold transition-colors"
                    placeholder="Search agent name, agency, email...">
            </div>

            <div class="w-full md:w-48">
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-gold transition-colors">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Approved / Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl text-sm hover:bg-slate-800 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Agents Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="py-4 px-5">Agent & Agency Name</th>
                        <th class="py-4 px-5">Contact Details</th>
                        <th class="py-4 px-5">Students Managed</th>
                        <th class="py-4 px-5">Status</th>
                        <th class="py-4 px-5 text-right">Admin Approval Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-5">
                            <div class="font-extrabold text-slate-900 text-base">{{ $agent->name }}</div>
                            <div class="text-xs font-semibold text-gold"><i class="fa-solid fa-building mr-1"></i>{{ $agent->agency_name }}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Registered: {{ $agent->created_at->format('d M Y') }}</div>
                        </td>

                        <td class="py-4 px-5">
                            <div class="text-xs font-medium text-slate-800"><i class="fa-solid fa-envelope text-slate-400 mr-1.5"></i>{{ $agent->email }}</div>
                            @if($agent->phone)<div class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-phone text-slate-400 mr-1.5"></i>{{ $agent->phone }}</div>@endif
                            @if($agent->country)<div class="text-xs text-slate-400 mt-0.5"><i class="fa-solid fa-globe text-slate-400 mr-1.5"></i>{{ $agent->country }}</div>@endif
                        </td>

                        <td class="py-4 px-5">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 text-indigo-700 font-bold rounded-lg text-xs">
                                <i class="fa-solid fa-user-graduate"></i>
                                {{ $agent->students_count }} Students
                            </span>
                        </td>

                        <td class="py-4 px-5">
                            @if($agent->status === 'pending')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                <i class="fa-solid fa-clock mr-1"></i>Pending Admin Approval
                            </span>
                            @elseif($agent->status === 'active')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <i class="fa-solid fa-circle-check mr-1"></i>Approved & Active
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                <i class="fa-solid fa-ban mr-1"></i>Suspended
                            </span>
                            @endif
                        </td>

                        <td class="py-4 px-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($agent->status !== 'active')
                                <form action="{{ route('admin.agents.update-status', $agent) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Approve Agent</span>
                                    </button>
                                </form>
                                @endif

                                @if($agent->status !== 'suspended')
                                <button type="button" onclick="confirmSuspend({{ $agent->id }}, '{{ $agent->name }}')" class="px-3 py-1.5 bg-slate-100 hover:bg-rose-100 text-slate-700 hover:text-rose-700 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                                    <i class="fa-solid fa-ban"></i>
                                    <span>Suspend</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-user-slash text-3xl text-slate-300 mb-2 block"></i>
                            <p class="font-semibold text-slate-600">No agents found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agents->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $agents->links() }}
        </div>
        @endif
    </div>

    {{-- Hidden suspension form --}}
    <form id="suspendForm" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="status" value="suspended">
        <input type="hidden" name="suspension_reason" id="suspensionReasonInput">
    </form>

    <script>
        function confirmSuspend(agentId, agentName) {
            Swal.fire({
                title: 'Suspend Agent?',
                html: `You are about to suspend <strong>${agentName}</strong>.<br><br>Please provide a reason for suspension:`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa-solid fa-ban"></i> Yes, Suspend',
                cancelButtonText: 'Cancel',
                input: 'textarea',
                inputPlaceholder: 'Enter suspension reason here...',
                inputAttributes: {
                    'aria-label': 'Suspension reason'
                },
                inputValidator: (value) => {
                    if (!value || value.trim().length < 3) {
                        return 'Please enter a valid reason (minimum 3 characters)';
                    }
                },
                customClass: {
                    confirmButton: 'rounded-lg px-4 py-2.5 font-bold text-sm',
                    cancelButton: 'rounded-lg px-4 py-2.5 font-semibold text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('suspendForm');
                    form.action = "{{ url('admin/agents') }}/" + agentId + "/status";
                    document.getElementById('suspensionReasonInput').value = result.value;
                    form.submit();
                }
            });
        }
    </script>
</div>
@endsection
