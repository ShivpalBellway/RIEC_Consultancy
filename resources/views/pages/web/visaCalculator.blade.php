@extends('layouts.app')

@section('title', 'F-2-7 Visa Points Calculator')

@section('content')

<div id="main-content-area">
    {{-- ================= CALCULATOR HERO ================= --}}
    <section class="relative overflow-hidden bg-[#061d43] py-20 text-white">
        {{-- Background elements for premium feel --}}
        <div class="absolute inset-0 bg-gradient-to-r from-[#061d43]/95 via-[#0c2a5c]/85 to-[#061d43]/90"></div>
        <div class="absolute top-0 right-0 -mt-12 -mr-12 w-96 h-96 bg-[#dca737]/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6">
            <div class="max-w-2xl">
                <div class="text-xs text-white/70 mb-6 flex items-center gap-2">
                    <a href="{{ route('home') }}" class="hover:text-[#dca737] transition">Home</a>
                    <span class="text-white/40">›</span>
                    <span class="text-[#dca737] font-medium">Visa Calculator</span>
                </div>

                <p class="text-[#dca737] text-xs uppercase tracking-[0.25em] font-extrabold mb-3">F-2-7 Points System</p>
                <h1 class="text-4xl md:text-5xl font-black leading-tight mb-4">
                    South Korea F-2-7<br>
                    <span class="text-[#dca737]">Points Calculator</span>
                </h1>
                <div class="w-16 h-1.5 bg-[#dca737] rounded mb-6"></div>
                <p class="text-white/80 text-sm md:text-base leading-7">
                    Assess your eligibility for the F-2-7 Points-Based Residence Visa. Select your criteria below to instantly calculate your score. A minimum of <strong>80 points</strong> out of 120+ is required to qualify.
                </p>
            </div>
        </div>
    </section>

    {{-- ================= CALCULATOR WORKSPACE ================= --}}
    <section class="py-16 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-12 gap-10">

                {{-- LEFT COLUMN: INPUT FORM --}}
                <div class="lg:col-span-8 space-y-8">

                    {{-- SECTION 1: Core Criteria --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 transition hover:shadow-md duration-300">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-lg">1</div>
                            <div>
                                <h3 class="text-xl font-bold text-[#102b5c]">Core Criteria</h3>
                                <p class="text-xs text-gray-500">Essential parameters for the points assessment</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            {{-- Age --}}
                            <div class="space-y-2">
                                <label for="age" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>Age (나이)</span>
                                    <span id="points-age" class="text-xs text-primary font-bold bg-primary/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="age" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="" data-points="0" disabled selected>Select Age Range</option>
                                    <option value="18-24" data-points="23">18 – 24 years (23 points)</option>
                                    <option value="25-29" data-points="25">25 – 29 years (25 points)</option>
                                    <option value="30-34" data-points="23">30 – 34 years (23 points)</option>
                                    <option value="35-39" data-points="20">35 – 39 years (20 points)</option>
                                    <option value="40-44" data-points="12">40 – 44 years (12 points)</option>
                                    <option value="45-50" data-points="8">45 – 50 years (8 points)</option>
                                    <option value="51+" data-points="3">51+ years (3 points)</option>
                                </select>
                            </div>

                            {{-- Education --}}
                            <div class="space-y-2">
                                <label for="education" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>Education (학력)</span>
                                    <span id="points-education" class="text-xs text-primary font-bold bg-primary/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="education" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="" data-points="0" disabled selected>Select Highest Education</option>
                                    <option value="phd-stem" data-points="25">PhD - STEM Field or Dual Degree (25 points)</option>
                                    <option value="phd-non-stem" data-points="20">PhD - Non-STEM Field (20 points)</option>
                                    <option value="master-stem" data-points="20">Master - STEM Field or Dual Degree (20 points)</option>
                                    <option value="master-non-stem" data-points="17">Master - Non-STEM Field (17 points)</option>
                                    <option value="bachelor-stem" data-points="17">Bachelor - STEM Field or Dual Degree (17 points)</option>
                                    <option value="bachelor-non-stem" data-points="15">Bachelor - Non-STEM Field (15 points)</option>
                                    <option value="associate-stem" data-points="15">Associate - STEM Field (15 points)</option>
                                    <option value="associate-non-stem" data-points="10">Associate - Non-STEM Field (10 points)</option>
                                </select>
                            </div>

                            {{-- Basic Qualifications (TOPIK / KIIP) --}}
                            <div class="space-y-2">
                                <label for="qualifications" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>Basic Competence (기본소양)</span>
                                    <span id="points-qualifications" class="text-xs text-primary font-bold bg-primary/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="qualifications" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="" data-points="0" disabled selected>Select Language Level</option>
                                    <option value="level-5" data-points="20">TOPIK Level 5+ or KIIP Level 5 Completed (20 points)</option>
                                    <option value="level-4" data-points="15">TOPIK Level 4 or KIIP Level 4 Completed (15 points)</option>
                                    <option value="level-3" data-points="10">TOPIK Level 3 or KIIP Level 3 Completed (10 points)</option>
                                    <option value="level-2" data-points="5">TOPIK Level 2 or KIIP Level 2 Completed (5 points)</option>
                                    <option value="level-1" data-points="3">TOPIK Level 1 or KIIP Level 1 Completed (3 points)</option>
                                    <option value="none" data-points="0">None / No Certification (0 points)</option>
                                </select>
                            </div>

                            {{-- Annual Income --}}
                            <div class="space-y-2">
                                <label for="income" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>Annual Income (연간소득)</span>
                                    <span id="points-income" class="text-xs text-primary font-bold bg-primary/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="income" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="" data-points="0" disabled selected>Select Annual Income Range</option>
                                    <option value="100m" data-points="60">KRW 100 Million+ (60 points)</option>
                                    <option value="90m-100m" data-points="58">KRW 90M – 100M (58 points)</option>
                                    <option value="80m-90m" data-points="56">KRW 80M – 90M (56 points)</option>
                                    <option value="70m-80m" data-points="53">KRW 70M – 80M (53 points)</option>
                                    <option value="60m-70m" data-points="50">KRW 60M – 70M (50 points)</option>
                                    <option value="50m-60m" data-points="45">KRW 50M – 60M (45 points)</option>
                                    <option value="40m-50m" data-points="40">KRW 40M – 50M (40 points)</option>
                                    <option value="30m-40m" data-points="30">KRW 30M – 40M (30 points)</option>
                                    <option value="min-30m" data-points="10">Minimum Wage – 30M (10 points)</option>
                                    <option value="below-min" data-points="0">Below Minimum Wage (0 points)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Bonus Criteria --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 transition hover:shadow-md duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#dca737]/10 text-[#dca737] flex items-center justify-center font-bold text-lg">2</div>
                                <div>
                                    <h3 class="text-xl font-bold text-[#102b5c]">Bonus Criteria</h3>
                                    <p class="text-xs text-gray-500">Additional elements to boost your score</p>
                                </div>
                            </div>
                            <span class="text-[10px] md:text-xs font-bold text-[#dca737] bg-[#dca737]/10 border border-[#dca737]/20 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                Max 40 points total limit
                            </span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            {{-- Bonus 5-1: Allied Country/Recommendation --}}
                            <div class="space-y-2">
                                <label for="bonus-allied" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>Allied Country / Recommendation</span>
                                    <span id="points-bonus-allied" class="text-xs text-primary font-bold bg-primary/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="bonus-allied" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="none" data-points="0" selected>Not Applicable (0 points)</option>
                                    <option value="outstanding" data-points="20">Outstanding Talent / Korean War Allied Country (20 points)</option>
                                    <option value="recommendation" data-points="20">Central Government Recommendation (20 points)</option>
                                    <option value="both" data-points="40">Allied Country Talent + Gov Recommendation (40 points)</option>
                                </select>
                            </div>

                            {{-- Bonus 5-2: Degree Bonus --}}
                            <div class="space-y-2">
                                <label for="bonus-degree" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>Degree Bonus (Top Univ / Korea)</span>
                                    <span id="points-bonus-degree" class="text-xs text-primary font-bold bg-primary/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="bonus-degree" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="none" data-points="0" selected>Not Applicable (0 points)</option>
                                    <option value="phd-top" data-points="30">PhD from Top Global University (30 points)</option>
                                    <option value="phd-korea" data-points="10">PhD from a Korean University (10 points)</option>
                                    <option value="master-top" data-points="20">Master's from Top Global University (20 points)</option>
                                    <option value="master-korea" data-points="7">Master's from a Korean University (7 points)</option>
                                    <option value="bachelor-top" data-points="15">Bachelor's from Top Global University (15 points)</option>
                                    <option value="bachelor-korea" data-points="5">Bachelor's from a Korean University (5 points)</option>
                                </select>
                            </div>

                            {{-- Bonus 5-3: KIIP --}}
                            <div class="space-y-2">
                                <label for="bonus-kiip" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>KIIP </span>
                                    <span id="points-bonus-kiip" class="text-xs text-primary font-bold bg-primary/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="bonus-kiip" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="none" data-points="0" selected>Not Applicable (0 points)</option>
                                    <option value="completed" data-points="10">Completed KIIP Level 5 or higher (10 points)</option>
                                </select>
                            </div>

                            {{-- Bonus 5-4: Volunteer --}}
                            <div class="space-y-2">
                                <label for="bonus-volunteer" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>Volunteer Service (봉사활동)</span>
                                    <span id="points-bonus-volunteer" class="text-xs text-primary font-bold bg-primary/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="bonus-volunteer" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="none" data-points="0" selected>Not Applicable (0 points)</option>
                                    <option value="3yr" data-points="7">3+ Years of Volunteer Service (7 points)</option>
                                    <option value="2-3yr" data-points="5">2 – 3 Years of Volunteer Service (5 points)</option>
                                    <option value="1-2yr" data-points="1">1 – 2 Years of Volunteer Service (1 point)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: Deductions --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 transition hover:shadow-md duration-300">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center font-bold text-lg">3</div>
                            <div>
                                <h3 class="text-xl font-bold text-[#102b5c]">Deductions (Minus Points)</h3>
                                <p class="text-xs text-gray-500">Penalties for immigration or criminal violations</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            {{-- Immigration Violations --}}
                            <div class="space-y-2">
                                <label for="deduction-immigration" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>Immigration Violations (출입국관리법 위반)</span>
                                    <span id="points-deduction-immigration" class="text-xs text-red-500 font-bold bg-red-500/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="deduction-immigration" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="none" data-points="0" selected>Not Applicable (0 points)</option>
                                    <option value="fine-3m" data-points="-30">Fine ≥ KRW 3 Million or Departure Order (-30 points)</option>
                                    <option value="fine-2m-3m" data-points="-20">Fine KRW 2 Million – 3 Million (-20 points)</option>
                                    <option value="fine-under-2m" data-points="-10">Fine < KRW 2 Million (-10 points)</option>
                                </select>
                            </div>

                            {{-- Criminal Record --}}
                            <div class="space-y-2">
                                <label for="deduction-criminal" class="block text-sm font-semibold text-gray-700 flex justify-between">
                                    <span>Criminal Record (형사처벌)</span>
                                    <span id="points-deduction-criminal" class="text-xs text-red-500 font-bold bg-red-500/5 px-2 py-0.5 rounded">0 pts</span>
                                </label>
                                <select id="deduction-criminal" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-lg px-4 py-3 text-sm focus:border-gold focus:ring-1 focus:ring-gold transition duration-200 outline-none">
                                    <option value="none" data-points="0" selected>Not Applicable (0 points)</option>
                                    <option value="fine-3m" data-points="-40">Fine ≥ KRW 3 Million (-40 points)</option>
                                    <option value="fine-1m-3m" data-points="-30">Fine KRW 1 Million – 3 Million (-30 points)</option>
                                    <option value="fine-0.5m-1m" data-points="-20">Fine KRW 0.5 Million – 1 Million (-20 points)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: STICKY SCORE SUMMARY --}}
                <div class="lg:col-span-4">
                    <div class="lg:sticky lg:top-8 space-y-6">

                        {{-- MAIN SCORE DISPLAY CARD --}}
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                            {{-- Top Header Accent --}}
                            <div class="h-2 bg-gradient-to-r from-primary via-[#dca737] to-primary"></div>

                            <div class="p-6 md:p-8 text-center">
                                <h3 class="text-lg font-bold text-[#102b5c] mb-6">Your Total Score</h3>

                                {{-- Circular Progress / Radial Dial --}}
                                <div class="relative w-40 h-40 mx-auto mb-6 flex items-center justify-center">
                                    <svg class="w-full h-full transform -rotate-90">
                                        {{-- Outer background circle --}}
                                        <circle cx="80" cy="80" r="70" stroke="#f3f4f6" stroke-width="12" fill="transparent" />
                                        {{-- Highlighted progress circle --}}
                                        <circle id="score-ring" cx="80" cy="80" r="70" stroke="#c89b2a" stroke-width="12" fill="transparent"
                                                stroke-dasharray="440" stroke-dashoffset="440" stroke-linecap="round" class="transition-all duration-500 ease-out" />
                                    </svg>
                                    <div class="absolute flex flex-col items-center">
                                        <span id="total-score" class="text-4xl md:text-5xl font-black text-[#102b5c]">0</span>
                                        <span class="text-xs text-gray-400 mt-1">/ 80 Passing</span>
                                    </div>
                                </div>

                                {{-- Pass/Fail Badge --}}
                                <div id="status-badge" class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-sm mb-8 transition-colors duration-300 bg-gray-100 text-gray-600">
                                    <i class="fa-solid fa-circle-question"></i>
                                    <span id="status-text">Incomplete Form</span>
                                </div>

                                {{-- Capped Bonus Notification Box --}}
                                <div id="bonus-alert" class="hidden text-left mb-6 p-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg text-xs flex gap-2">
                                    <i class="fa-solid fa-triangle-exclamation text-amber-600 shrink-0 text-base"></i>
                                    <span><strong>Bonus Cap Applied:</strong> Your raw bonus points exceed 40. Under F-2-7 rules, bonus points are capped at a maximum of 40 points.</span>
                                </div>

                                {{-- Breakdown list --}}
                                <div class="border-t border-b border-gray-100 py-4 space-y-3 text-left text-sm mb-8">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Base Points:</span>
                                        <span id="summary-base" class="font-semibold text-[#102b5c]">0</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-500">Bonus Points:</span>
                                        <div class="flex items-center gap-1.5">
                                            <span id="summary-bonus" class="font-semibold text-[#102b5c]">0</span>
                                            <span id="raw-bonus-wrapper" class="hidden text-xs text-gray-400 italic">(Raw: <span id="raw-bonus">0</span>)</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Deductions:</span>
                                        <span id="summary-deductions" class="font-semibold text-red-500">0</span>
                                    </div>
                                </div>

                                {{-- Interactive Action Buttons --}}
                                <div class="space-y-3">
                                    <button onclick="window.print()" class="w-full bg-primary text-white hover:bg-[#15274d] py-3 rounded-lg font-bold text-sm transition-all duration-300 shadow-md shadow-primary/10 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-print"></i>
                                        Print / Save Summary
                                    </button>
                                    <button onclick="resetCalculator()" class="w-full border border-gray-200 text-gray-600 hover:bg-gray-50 py-2.5 rounded-lg font-semibold text-sm transition duration-200 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-arrow-rotate-left"></i>
                                        Reset Calculator
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- FAQ QUICK CARD --}}
                        <div class="bg-gradient-to-br from-[#102b5c] to-[#1a2f5e] text-white rounded-2xl p-6 shadow-md text-left">
                            <h4 class="font-bold text-[#dca737] mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-info-circle"></i> F-2-7 Visa Tip
                            </h4>
                            <p class="text-xs text-white/80 leading-relaxed mb-3">
                                The F-2-7 points-based resident visa gives you more freedom to work and reside in South Korea. Preparing your TOPIK test scores and KIIP Level 5 certification ahead of time is the most secure way to hit the passing mark.
                            </p>
                            <a href="{{ route('contact') }}" class="text-xs text-[#dca737] hover:underline font-bold flex items-center gap-1.5 transition">
                                Need professional consulting? Contact Us <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

{{-- Print Styles --}}
@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #main-content-area, #main-content-area * {
            visibility: visible;
        }
        nav, footer, .lg:sticky, header, section.relative, .bg-gray-50\/50 button, .bg-gradient-to-br {
            display: none !important;
        }
        .lg:col-span-8, .lg:col-span-4 {
            width: 100% !important;
            float: none !important;
        }
        .grid {
            display: block !important;
        }
        .bg-white {
            box-shadow: none !important;
            border: none !important;
        }
        select {
            appearance: none;
            -webkit-appearance: none;
            border: none !important;
            border-bottom: 1px solid #ddd !important;
            background: transparent !important;
            padding-left: 0 !important;
            pointer-events: none;
        }
    }
</style>
@endpush

{{-- ================= CALCULATION JS ================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectIds = [
            'age', 'education', 'qualifications', 'income',
            'bonus-allied', 'bonus-degree', 'bonus-kiip', 'bonus-volunteer',
            'deduction-immigration', 'deduction-criminal'
        ];

        // Add event listeners to all selectors
        selectIds.forEach(id => {
            const selectEl = document.getElementById(id);
            if (selectEl) {
                selectEl.addEventListener('change', calculatePoints);
            }
        });

        // Initial run
        calculatePoints();
    });

    function calculatePoints() {
        // Base categories
        const ageVal = getSelectPoints('age');
        const eduVal = getSelectPoints('education');
        const qualVal = getSelectPoints('qualifications');
        const incVal = getSelectPoints('income');

        // Update single badges for display
        updatePointsBadge('age', ageVal);
        updatePointsBadge('education', eduVal);
        updatePointsBadge('qualifications', qualVal);
        updatePointsBadge('income', incVal);

        // Calculate Base Sum
        const baseSum = ageVal + eduVal + qualVal + incVal;

        // Bonus categories
        const bonAllied = getSelectPoints('bonus-allied');
        const bonDegree = getSelectPoints('bonus-degree');
        const bonKiip = getSelectPoints('bonus-kiip');
        const bonVolunteer = getSelectPoints('bonus-volunteer');

        updatePointsBadge('bonus-allied', bonAllied);
        updatePointsBadge('bonus-degree', bonDegree);
        updatePointsBadge('bonus-kiip', bonKiip);
        updatePointsBadge('bonus-volunteer', bonVolunteer);

        // Sum Raw Bonus
        const rawBonus = bonAllied + bonDegree + bonKiip + bonVolunteer;

        // Cap Bonus Points at 40
        const cappedBonus = Math.min(rawBonus, 40);

        // Deductions
        const dedImmigration = getSelectPoints('deduction-immigration');
        const dedCriminal = getSelectPoints('deduction-criminal');

        updatePointsBadge('deduction-immigration', dedImmigration);
        updatePointsBadge('deduction-criminal', dedCriminal);

        const deductionsSum = dedImmigration + dedCriminal;

        // Calculate Final Score
        const finalScore = baseSum + cappedBonus + deductionsSum;

        // Update UI Breakdown
        document.getElementById('summary-base').textContent = baseSum;
        document.getElementById('summary-bonus').textContent = cappedBonus;
        document.getElementById('summary-deductions').textContent = deductionsSum;

        // Bonus Alert logic
        const bonusAlert = document.getElementById('bonus-alert');
        const rawBonusWrapper = document.getElementById('raw-bonus-wrapper');
        const rawBonusEl = document.getElementById('raw-bonus');

        if (rawBonus > 40) {
            bonusAlert.classList.remove('hidden');
            rawBonusWrapper.classList.remove('hidden');
            rawBonusEl.textContent = rawBonus;
        } else {
            bonusAlert.classList.add('hidden');
            rawBonusWrapper.classList.add('hidden');
        }

        // Update Total Score Display
        const scoreEl = document.getElementById('total-score');
        scoreEl.textContent = finalScore;

        // Update SVG circle dial progress
        // SVG circle radius is 70. Circumference is 2 * PI * r = ~439.82 -> round to 440
        const maxExpectedScore = 150;
        const progressPercent = Math.max(0, Math.min(finalScore / maxExpectedScore, 1));
        const strokeDashOffset = 440 - (440 * progressPercent);
        document.getElementById('score-ring').style.strokeDashoffset = strokeDashOffset;

        // Update Pass/Fail status banner
        const statusBadge = document.getElementById('status-badge');
        const statusText = document.getElementById('status-text');

        // Check if any core parameter is unselected
        const ageSelect = document.getElementById('age').value;
        const eduSelect = document.getElementById('education').value;
        const qualSelect = document.getElementById('qualifications').value;
        const incSelect = document.getElementById('income').value;

        if (!ageSelect || !eduSelect || !qualSelect || !incSelect) {
            statusBadge.className = "inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-sm mb-8 bg-gray-100 text-gray-500 transition-all duration-300";
            statusText.textContent = "Incomplete Form";
            statusBadge.querySelector('i').className = "fa-solid fa-circle-question";
        } else if (finalScore >= 80) {
            statusBadge.className = "inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-sm mb-8 bg-emerald-50 text-emerald-700 border border-emerald-200 transition-all duration-300 shadow-sm";
            statusText.textContent = "Eligible to Apply (Pass)";
            statusBadge.querySelector('i').className = "fa-solid fa-circle-check text-emerald-600";
        } else {
            statusBadge.className = "inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-sm mb-8 bg-rose-50 text-rose-700 border border-rose-200 transition-all duration-300";
            statusText.textContent = `Needs ${80 - finalScore} more points to Pass`;
            statusBadge.querySelector('i').className = "fa-solid fa-circle-xmark text-rose-600";
        }
    }

    function getSelectPoints(id) {
        const selectEl = document.getElementById(id);
        if (!selectEl) return 0;
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        if (!selectedOption) return 0;
        const ptsAttr = selectedOption.getAttribute('data-points');
        return ptsAttr ? parseInt(ptsAttr, 10) : 0;
    }

    function updatePointsBadge(id, value) {
        const badge = document.getElementById(`points-${id}`);
        if (!badge) return;

        if (value < 0) {
            badge.textContent = `${value} pts`;
            badge.className = "text-xs text-red-500 font-bold bg-red-500/5 px-2 py-0.5 rounded";
        } else if (value > 0) {
            badge.textContent = `+${value} pts`;
            badge.className = "text-xs text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded";
        } else {
            badge.textContent = `0 pts`;
            badge.className = "text-xs text-gray-400 font-medium bg-gray-100 px-2 py-0.5 rounded";
        }
    }

    function resetCalculator() {
        const selectIds = [
            'age', 'education', 'qualifications', 'income',
            'bonus-allied', 'bonus-degree', 'bonus-kiip', 'bonus-volunteer',
            'deduction-immigration', 'deduction-criminal'
        ];

        selectIds.forEach(id => {
            const selectEl = document.getElementById(id);
            if (selectEl) {
                // If it is a core category, reset to the disabled default option
                if (['age', 'education', 'qualifications', 'income'].includes(id)) {
                    selectEl.selectedIndex = 0;
                } else {
                    // For bonus/deduction, default to 'none' option
                    selectEl.value = 'none';
                }
            }
        });

        // Recalculate
        calculatePoints();
    }
</script>
@endsection
