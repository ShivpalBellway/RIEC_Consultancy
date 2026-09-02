@extends('layouts.admin')

@section('title', 'Document Removal Requests')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-trash-can-arrow-up text-rose-600"></i>
                <span>Document Removal Requests Workflow</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Review document deletion/replacement requests submitted by Agents</p>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="py-4 px-5">Student & Agent</th>
                        <th class="py-4 px-5">Document</th>
                        <th class="py-4 px-5">Removal Reason Submitted</th>
                        <th class="py-4 px-5">Requested Date</th>
                        <th class="py-4 px-5 text-right">Review Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $req)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-5">
                            <div class="font-extrabold text-slate-900">{{ $req->student?->full_name }}</div>
                            <div class="text-xs text-gold font-semibold"><i class="fa-solid fa-building mr-1"></i>{{ $req->agent?->agency_name }}</div>
                            <div class="text-[11px] text-slate-400">Agent: {{ $req->agent?->name }}</div>
                        </td>

                        <td class="py-4 px-5">
                            <div class="font-bold text-slate-900 text-sm">{{ $req->document_type_name }}</div>
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($req->file_path) }}" target="_blank" class="text-xs text-primary hover:underline flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-download text-[10px]"></i>
                                <span>{{ $req->document_name }}</span>
                            </a>
                        </td>

                        <td class="py-4 px-5">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 italic max-w-md">
                                "{{ $req->removal_request_reason }}"
                            </div>
                        </td>

                        <td class="py-4 px-5 text-xs text-slate-500">
                            {{ $req->removal_requested_at ? $req->removal_requested_at->format('d M Y, h:i A') : 'N/A' }}
                        </td>

                        <td class="py-4 px-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.document-removals.approve', $req) }}" method="POST" class="inline" onsubmit="return confirm('Approve document removal? The file will be permanently removed.')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Approve Removal</span>
                                    </button>
                                </form>

                                <form action="{{ route('admin.document-removals.reject', $req) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition-colors">
                                        <span>Reject</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-circle-check text-3xl text-slate-300 mb-2 block"></i>
                            <p class="font-semibold text-slate-600">No pending document removal requests.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
