@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Website Manage</h1>
    <p class="text-gray-500 mt-2">Manage all dynamic content sections for your website</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    {{-- Services Card --}}
    <a href="{{ route('admin.services.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
        <div class="relative p-6 flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-200 transition">
                <i class="fa-solid fa-briefcase text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Services</h3>
            <p class="text-sm text-gray-600 mt-2">Manage services</p>
            <div class="mt-4 text-2xl font-bold text-blue-600">{{ $counts['services'] }}</div>
        </div>
    </a>

    {{-- Features Card --}}
    <a href="{{ route('admin.features.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
        <div class="relative p-6 flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center mb-4 group-hover:bg-purple-200 transition">
                <i class="fa-solid fa-star text-purple-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Features</h3>
            <p class="text-sm text-gray-600 mt-2">Homepage features</p>
            <div class="mt-4 text-2xl font-bold text-purple-600">{{ $counts['features'] }}</div>
        </div>
    </a>

    {{-- Success Stories Card --}}
    <a href="{{ route('admin.success-stories.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-50 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
        <div class="relative p-6 flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center mb-4 group-hover:bg-orange-200 transition">
                <i class="fa-solid fa-quote-left text-orange-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Success Stories</h3>
            <p class="text-sm text-gray-600 mt-2">Testimonials</p>
            <div class="mt-4 text-2xl font-bold text-orange-600">{{ $counts['success_stories'] }}</div>
        </div>
    </a>

    {{-- Partners Card --}}
    <a href="{{ route('admin.partners.index') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-pink-50 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
        <div class="relative p-6 flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-full bg-pink-100 flex items-center justify-center mb-4 group-hover:bg-pink-200 transition">
                <i class="fa-solid fa-handshake text-pink-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Partners</h3>
            <p class="text-sm text-gray-600 mt-2">Partner logos</p>
            <div class="mt-4 text-2xl font-bold text-pink-600">{{ $counts['partners'] }}</div>
        </div>
    </a>

    {{-- Site Settings Card --}}
    <a href="{{ route('admin.site.settings.edit') }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
        <div class="relative p-6 flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center mb-4 group-hover:bg-gray-300 transition">
                <i class="fa-solid fa-cog text-gray-700 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Site Settings</h3>
            <p class="text-sm text-gray-600 mt-2">Global settings</p>
            <div class="mt-4 text-sm font-semibold text-gray-600">Configure</div>
        </div>
    </a>
</div>

@endsection
