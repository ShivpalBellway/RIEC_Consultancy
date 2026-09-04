@extends('layouts.app')

@section('title', $page->title)

@section('content')
<section class="bg-gray-50 py-16 md:py-24">
    <div class="max-w-4xl mx-auto px-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-12">
            <h1 class="text-3xl md:text-4xl font-bold text-[#1a2f5e] mb-8">{{ $page->title }}</h1>
            <div class="prose prose-lg max-w-none prose-headings:text-[#1a2f5e] prose-a:text-[#c89b2a]">
                {!! $page->content ?: '<p>Content for this page will be updated soon.</p>' !!}
            </div>
        </div>
    </div>
</section>
@endsection