@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('header-actions')




@endsection

@section('content')
@php
$totalApplications        = $stats['total_applications']    ?? 0;
$pendingApplications      = $statusCounts['pending']               ?? 0;
$receivedApplications     = $statusCounts['received']              ?? 0;
$univApplied              = $statusCounts['university_applied']    ?? 0;
$tuitionConfirmed         = $statusCounts['tuition_fee_confirmed'] ?? 0;
$visaApplied              = $statusCounts['visa_applied']          ?? 0;
$visaGranted              = $statusCounts['visa_granted']          ?? 0;
$visaRejected             = $statusCounts['visa_rejected']         ?? 0;
$studying                 = $statusCounts['studying']              ?? 0;
$refundComplete           = $statusCounts['refund_complete']       ?? 0;

// Pie chart percentages (9 slices)
$pct = function($n) use ($totalApplications) {
    return $totalApplications ? round(($n / max($totalApplications, 1)) * 100, 2) : 0;
};

$cards = [
    ['title'=>'Total Applications','value'=>number_format($totalApplications),'icon'=>'fa-file-lines','bg'=>'bg-purple-100','text'=>'text-purple-600','line'=>'#7c3aed'],
    ['title'=>'Pending',           'value'=>number_format($pendingApplications),'icon'=>'fa-hourglass-half','bg'=>'bg-amber-100','text'=>'text-amber-500','line'=>'#f59e0b'],
    ['title'=>'Received',          'value'=>number_format($receivedApplications),'icon'=>'fa-inbox','bg'=>'bg-blue-100','text'=>'text-blue-600','line'=>'#2563eb'],
    ['title'=>'Visa Granted',      'value'=>number_format($visaGranted),'icon'=>'fa-passport','bg'=>'bg-emerald-100','text'=>'text-emerald-600','line'=>'#22c55e'],
    ['title'=>'Visa Rejected',     'value'=>number_format($visaRejected),'icon'=>'fa-circle-xmark','bg'=>'bg-red-100','text'=>'text-red-500','line'=>'#ef4444'],
];

// 9-status pie slices with colours
$pieSlices = [
    ['label'=>'Pending',            'count'=>$pendingApplications,  'color'=>'#f59e0b'],
    ['label'=>'Received',           'count'=>$receivedApplications, 'color'=>'#2563eb'],
    ['label'=>'University Applied', 'count'=>$univApplied,          'color'=>'#7c3aed'],
    ['label'=>'Tuition Confirmed',  'count'=>$tuitionConfirmed,     'color'=>'#0891b2'],
    ['label'=>'Visa Applied',       'count'=>$visaApplied,          'color'=>'#d97706'],
    ['label'=>'Visa Granted',       'count'=>$visaGranted,          'color'=>'#22c55e'],
    ['label'=>'Visa Rejected',      'count'=>$visaRejected,         'color'=>'#ef4444'],
    ['label'=>'Studying',           'count'=>$studying,             'color'=>'#061d43'],
    ['label'=>'Refund Complete',    'count'=>$refundComplete,       'color'=>'#c89b2a'],
];

// Build conic-gradient string
$conicParts = [];
$cumulative = 0;
foreach ($pieSlices as $slice) {
    $p = $pct($slice['count']);
    $conicParts[] = $slice['color'] . ' ' . $cumulative . '% ' . ($cumulative + $p) . '%';
    $cumulative += $p;
}
if ($cumulative < 100) {
    $conicParts[] = '#e5e7eb ' . $cumulative . '% 100%';
}
$conicGradient = 'conic-gradient(' . implode(', ', $conicParts) . ')';

$statusClasses = [
    'pending'               => 'bg-amber-50 text-amber-700',
    'received'              => 'bg-blue-50 text-blue-700',
    'university_applied'    => 'bg-violet-50 text-violet-700',
    'tuition_fee_confirmed' => 'bg-cyan-50 text-cyan-700',
    'visa_applied'          => 'bg-orange-50 text-orange-700',
    'visa_granted'          => 'bg-emerald-50 text-emerald-700',
    'visa_rejected'         => 'bg-red-50 text-red-700',
    'studying'              => 'bg-primary/10 text-primary',
    'refund_complete'       => 'bg-yellow-50 text-yellow-700',
];

$statusLabels = [
    'pending'               => 'Pending',
    'received'              => 'Received',
    'university_applied'    => 'Univ. Applied',
    'tuition_fee_confirmed' => 'Tuition Confirmed',
    'visa_applied'          => 'Visa Applied',
    'visa_granted'          => 'Visa Granted',
    'visa_rejected'         => 'Visa Rejected',
    'studying'              => 'Studying',
    'refund_complete'       => 'Refund Complete',
];
@endphp

<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

  {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary via-[#223d78] to-[#2b4d97] px-5 py-4 shadow-xl shadow-primary/10 border border-white/10 flex-1">

        {{-- Decorative Glow --}}
        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-gold/10 blur-2xl"></div>
        <div class="absolute right-8 bottom-0 w-16 h-16 rounded-full bg-white/5 blur-xl"></div>

        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10 flex items-center justify-center">
                <i class="fa-solid fa-user-shield text-gold text-lg"></i>
            </div>

            <div>
                <p class="text-[11px] uppercase tracking-[0.18em] text-white/60 font-bold mb-1">
                    Admin Dashboard
                </p>

                <h2 class="text-xl font-black text-white leading-none">
                    Welcome Back,
                    <span class="text-gold">{{ session('admin_name') }}</span>
                </h2>

                <p class="text-xs text-white/60 mt-1">
                    Manage programs, applications and student records with confidence.
                </p>
            </div>
        </div>
    </div>

</div>


    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3">
        @foreach($cards as $card)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3.5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl {{ $card['bg'] }} {{ $card['text'] }} flex items-center justify-center text-lg">
                    <i class="fa-solid {{ $card['icon'] }}"></i>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-gray-500">{{ $card['title'] }}</p>
                    <h3 class="text-xl font-black text-primary mt-0.5">{{ $card['value'] }}</h3>
                </div>
            </div>

            <svg class="w-full h-9 mt-2" viewBox="0 0 140 45" fill="none" preserveAspectRatio="none">
                <path d="M0 36 L18 30 L32 18 L48 25 L62 16 L78 28 L94 22 L110 15 L126 17 L140 8"
                    stroke="{{ $card['line'] }}" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M0 36 L18 30 L32 18 L48 25 L62 16 L78 28 L94 22 L110 15 L126 17 L140 8 L140 45 L0 45 Z"
                    fill="{{ $card['line'] }}" opacity=".12"/>
            </svg>
        </div>
        @endforeach
    </div>

    {{-- Charts Row --}}
    <div class="grid xl:grid-cols-[1.55fr_1fr] gap-4">

        {{-- Overview --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-base font-black text-primary">
                    <i class="fa-solid fa-chart-line text-blue-600 mr-2"></i>
                    Applications Overview
                </h3>

                <button class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-primary">
                    This Month
                </button>
            </div>

            <div class="flex gap-5 text-[11px] font-bold mb-2">
                <span><i class="inline-block w-2.5 h-2.5 rounded-full bg-primary mr-1"></i> Applications</span>
                <span><i class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 mr-1"></i> Visa Granted</span>
            </div>

            <div class="relative h-[215px]">
                <div class="absolute inset-0 grid grid-rows-5">
                    @for($i=0;$i<5;$i++)
                    <div class="border-t border-gray-100"></div>
                    @endfor
                </div>

                <svg class="relative w-full h-full" viewBox="0 0 800 230" preserveAspectRatio="none">
                    <path d="M0 190 C60 160 90 125 140 110 C180 95 210 65 260 85 C310 105 350 130 400 105 C455 78 490 110 540 82 C595 55 640 90 690 60 C730 38 760 42 800 25"
                        stroke="#061d43" stroke-width="4" fill="none" stroke-linecap="round"/>
                    <path d="M0 210 C60 200 95 175 140 160 C190 135 230 130 270 145 C325 168 360 125 410 140 C460 158 500 112 550 98 C610 76 645 118 695 92 C740 70 765 88 800 72"
                        stroke="#c89b2a" stroke-width="4" fill="none" stroke-linecap="round"/>
                    <path d="M0 210 C60 200 95 175 140 160 C190 135 230 130 270 145 C325 168 360 125 410 140 C460 158 500 112 550 98 C610 76 645 118 695 92 C740 70 765 88 800 72 L800 230 L0 230 Z"
                        fill="#c89b2a" opacity=".15"/>
                </svg>

                <div class="absolute bottom-0 left-0 right-0 flex justify-between text-[10px] font-bold text-gray-400">
                    <span>May 1</span><span>May 6</span><span>May 11</span><span>May 16</span><span>May 21</span><span>May 26</span><span>May 31</span>
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <h3 class="text-base font-black text-primary mb-4">
                    <i class="fa-solid fa-chart-pie text-primary mr-2"></i>
                    Applications by Status
                </h3>

                <div class="flex items-start gap-4">
                    {{-- Pie Chart --}}
                    <div class="w-32 h-32 rounded-full flex items-center justify-center shrink-0"
                        style="background: {{ $conicGradient }};">
                        <div class="w-20 h-20 bg-white rounded-full flex flex-col items-center justify-center">
                            <b class="text-xl text-primary">{{ number_format($totalApplications) }}</b>
                            <span class="text-[10px] text-gray-500 font-bold">Total</span>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="space-y-1.5 text-[11px] font-bold flex-1">
                        @foreach($pieSlices as $slice)
                        <div class="flex justify-between items-center">
                            <span>
                                <i class="inline-block w-2 h-2 rounded-full mr-1" style="background:{{ $slice['color'] }}"></i>
                                {{ $slice['label'] }}
                            </span>
                            <span class="text-gray-600">{{ $slice['count'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <h3 class="text-base font-black text-primary mb-3">Quick Actions</h3>

                <div class="grid grid-cols-4 gap-2">
                    <a href="{{ Route::has('admin.programs.create') ? route('admin.programs.create') : '#' }}" class="bg-blue-50 text-blue-600 rounded-xl p-3 text-center text-[11px] font-bold">
                        <i class="fa-solid fa-book-open text-lg block mb-1"></i>New
                    </a>
                    <a href="#" class="bg-emerald-50 text-emerald-600 rounded-xl p-3 text-center text-[11px] font-bold">
                        <i class="fa-solid fa-user-plus text-lg block mb-1"></i>User
                    </a>
                    <a href="#" class="bg-amber-50 text-gold rounded-xl p-3 text-center text-[11px] font-bold">
                        <i class="fa-solid fa-file-lines text-lg block mb-1"></i>Report
                    </a>
                    <a href="#" class="bg-red-50 text-red-500 rounded-xl p-3 text-center text-[11px] font-bold">
                        <i class="fa-solid fa-gear text-lg block mb-1"></i>Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Row --}}
    <div class="grid xl:grid-cols-[1.55fr_1fr] gap-4">

        {{-- Recent Applications --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-base font-black text-primary">
                    <i class="fa-solid fa-list text-primary mr-2"></i>
                    Recent Applications
                </h3>
                <a href="{{ Route::has('admin.applications.index') ? route('admin.applications.index') : '#' }}" class="text-xs font-bold bg-gray-50 px-3 py-1.5 rounded-lg">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-400">
                            <th class="py-2">Applicant</th>
                            <th class="py-2">Program</th>
                            <th class="py-2">Country</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Submitted</th>
                            <th class="py-2 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($recent_applications as $app)
                        <tr class="text-xs">
                            <td class="py-2.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary text-white flex items-center justify-center text-[10px] font-black">
                                        {{ strtoupper(substr($app->name, 0, 1)) }}
                                    </div>
                                    <b class="text-primary">{{ $app->name }}</b>
                                </div>
                            </td>
                            <td class="py-2.5 font-bold text-primary">{{ $app->program?->name ?? '-' }}</td>
                            <td class="py-2.5 font-bold text-primary">{{ $app->program?->country ?? '-' }}</td>
                            <td class="py-2.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black {{ $statusClasses[$app->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$app->status] ?? ucfirst(str_replace('_', ' ', $app->status)) }}
                                </span>
                            </td>
                            <td class="py-2.5 font-bold text-primary">{{ $app->created_at?->format('M d, Y') }}</td>
                            <td class="py-2.5 text-center">
                                <a href="{{ Route::has('admin.applications.show') ? route('admin.applications.show', $app) : '#' }}" class="inline-flex w-7 h-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                    <i class="fa-solid fa-eye text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 text-sm">No applications yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-base font-black text-primary">
                    <i class="fa-solid fa-arrows-spin text-primary mr-2"></i>
                    Recent Activity
                </h3>
                <button class="text-xs font-bold bg-gray-50 px-3 py-1.5 rounded-lg">View All</button>
            </div>

            <div class="space-y-3">
                @forelse($recent_applications->take(5) as $app)
                <div class="flex gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-black text-primary">New application received</p>
                        <p class="text-[11px] text-gray-500">by {{ $app->name }}</p>
                    </div>
                    <span class="text-[10px] text-gray-400">{{ $app->created_at?->format('h:i A') }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400 py-6 text-center">No recent activity yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
