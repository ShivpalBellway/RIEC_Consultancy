@extends('layouts.admin')

@section('title', 'Blogs')
@section('page-title', 'Manage Blogs')

@section('header-actions')
<a href="{{ route('admin.blogs.create') }}"
    class="inline-flex items-center gap-2 bg-[#1a2f5e] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#132247] transition">
    <i class="fa-solid fa-plus text-xs"></i>
    Add Blog Post
</a>
@endsection

@section('content')

{{-- Toast Notification --}}
<div id="toast-msg" style="
    display:none;
    position:fixed;
    top:24px;
    right:24px;
    z-index:9999;
    color:#fff;
    padding:12px 20px;
    border-radius:12px;
    font-size:13px;
    font-weight:600;
    box-shadow:0 4px 20px rgba(0,0,0,0.15);
    transition:opacity 0.3s ease;
"></div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-900">All Blog Posts</h2>
        <p class="text-sm text-gray-500 mt-1">Publish, update, or delete blog articles displayed on the website.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Cover Image</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Title</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Published Date</th>
                    <th class="px-6 py-4 text-right font-bold text-gray-600">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($blogs as $blog)
                <tr class="hover:bg-gray-50 transition" id="blog-row-{{ $blog->id }}">

                    <td class="px-6 py-4">
                        <div class="w-20 h-12 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden">
                            @if($blog->image)
                            <img src="{{ asset('storage/'.$blog->image) }}"
                                class="w-full h-full object-cover"
                                alt="Blog Cover">
                            @else
                            <span class="text-xs text-gray-400 font-bold">No Cover</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4 font-semibold text-gray-900 max-w-xs truncate">
                        {{ $blog->title }}
                    </td>

                    <td class="px-6 py-4" id="status-cell-{{ $blog->id }}">
                        @if($blog->is_published)
                        <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">Published</span>
                        @else
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">Draft</span>
                        @endif
                    </td>

                    {{-- Published Date --}}
                    <td class="px-6 py-4 text-gray-600 font-medium" id="date-cell-{{ $blog->id }}">
                        {{ $blog->published_at ? $blog->published_at->format('M d, Y h:i A') : 'N/A' }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2 items-center">

                            {{-- Edit --}}
                            <a href="{{ route('admin.blogs.edit', $blog) }}"
                                class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition"
                                title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>

                            <button
                                type="button"
                                class="toggle-btn"
                                data-id="{{ $blog->id }}"
                                data-url="{{ route('admin.blogs.toggle-publish', $blog) }}"
                                data-published="{{ $blog->is_published ? '1' : '0' }}"
                                title="{{ $blog->is_published ? 'Click to Unpublish' : 'Click to Publish' }}"
                                style="background:none;border:none;padding:0;cursor:pointer;display:flex;align-items:center;">
                                @if($blog->is_published)
                                <span class="toggle-track" style="display:inline-flex;align-items:center;width:44px;height:24px;border-radius:999px;background-color:#16a34a;position:relative;box-shadow:inset 0 1px 3px rgba(0,0,0,0.2);transition:background-color 0.3s ease;">
                                    <span class="toggle-knob" style="display:block;width:18px;height:18px;border-radius:50%;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,0.25);position:absolute;top:3px;left:23px;transition:left 0.3s ease;"></span>
                                </span>
                                @else
                                <span class="toggle-track" style="display:inline-flex;align-items:center;width:44px;height:24px;border-radius:999px;background-color:#d1d5db;position:relative;box-shadow:inset 0 1px 3px rgba(0,0,0,0.2);transition:background-color 0.3s ease;">
                                    <span class="toggle-knob" style="display:block;width:18px;height:18px;border-radius:50%;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,0.25);position:absolute;top:3px;left:3px;transition:left 0.3s ease;"></span>
                                </span>
                                @endif
                            </button>

                            {{-- Delete --}}
                            <form action="{{ route('admin.blogs.destroy', $blog) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this blog post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    title="Delete"
                                    class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        No blog posts added yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($blogs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $blogs->links() }}
    </div>
    @endif
</div>

<script>
    document.querySelectorAll('.toggle-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = btn.dataset.id;
            const url = btn.dataset.url;
            const track = btn.querySelector('.toggle-track');
            const knob = btn.querySelector('.toggle-knob');
            const isOn = btn.dataset.published === '1';

            // Optimistically flip UI right away
            if (isOn) {
                track.style.backgroundColor = '#d1d5db';
                knob.style.left = '3px';
                btn.dataset.published = '0';
                btn.title = 'Click to Publish';
            } else {
                track.style.backgroundColor = '#16a34a';
                knob.style.left = '23px';
                btn.dataset.published = '1';
                btn.title = 'Click to Unpublish';
            }

            // Send AJAX — no page reload
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    if (!data.success) {
                        // Revert on failure
                        if (isOn) {
                            track.style.backgroundColor = '#16a34a';
                            knob.style.left = '23px';
                            btn.dataset.published = '1';
                        } else {
                            track.style.backgroundColor = '#d1d5db';
                            knob.style.left = '3px';
                            btn.dataset.published = '0';
                        }
                        return;
                    }

                    // Update status badge in the same row
                    const statusCell = document.getElementById('status-cell-' + id);
                    statusCell.innerHTML = data.is_published ?
                        '<span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">Published</span>' :
                        '<span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">Draft</span>';

                    // Update published date
                    document.getElementById('date-cell-' + id).textContent = data.published_at ?? 'N/A';

                    showToast(data.message);
                })
                .catch(function() {
                    showToast('Something went wrong. Please try again.', true);
                });
        });
    });

    function showToast(msg, isError) {
        const toast = document.getElementById('toast-msg');
        toast.textContent = msg;
        toast.style.backgroundColor = isError ? '#dc2626' : '#16a34a';
        toast.style.display = 'block';
        toast.style.opacity = '1';
        setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() {
                toast.style.display = 'none';
            }, 300);
        }, 2500);
    }
</script>

@endsection
