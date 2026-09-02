<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserNoticeController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $type = (string) $request->query('type', '');
        $priority = (string) $request->query('priority', '');

        $type = in_array($type, ['general', 'important', 'urgent', 'event', 'update'], true) ? $type : '';
        $priority = in_array($priority, ['low', 'medium', 'high', 'urgent'], true) ? $priority : '';

        $filteredNotices = Notice::published()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($type !== '', fn ($query) => $query->where('type', $type))
            ->when($priority !== '', fn ($query) => $query->where('priority', $priority));

        $notices = (clone $filteredNotices)
                         ->orderBy('is_pinned', 'desc')
                         ->orderBy('priority', 'desc')
                         ->orderBy('published_at', 'desc')
                         ->paginate(12)
                         ->withQueryString();

        $pinnedNotices = (clone $filteredNotices)
                               ->pinned()
                               ->orderBy('priority', 'desc')
                               ->orderBy('published_at', 'desc')
                               ->get();

        return view('pages.web.notices.index', compact('notices', 'pinnedNotices'));
    }

    public function show(Notice $notice)
    {
        // Only frontend views increment count
        $notice->increment('views');

        $relatedNotices = Notice::published()
                               ->where('id', '!=', $notice->id)
                               ->where(function($q) use ($notice) {
                                   $q->where('type', $notice->type)
                                     ->orWhere('priority', $notice->priority);
                               })
                               ->orderBy('is_pinned', 'desc')
                               ->orderBy('published_at', 'desc')
                               ->limit(5)
                               ->get();

        return view('pages.web.notices.show', compact('notice', 'relatedNotices'));
    }

    public function downloadFile(Notice $notice, $index)
    {
        try {
            // Get file paths as array
            $filePaths = $notice->file_paths;
            if (is_string($filePaths)) {
                $filePaths = json_decode($filePaths, true) ?? [];
            }
            if (!is_array($filePaths)) {
                $filePaths = [];
            }
            
            // Check if index exists
            if (!isset($filePaths[$index])) {
                abort(404, 'File not found.');
            }

            $filePath = $filePaths[$index];
            
            // Get file names
            $fileNames = $notice->file_names;
            if (is_string($fileNames)) {
                $fileNames = json_decode($fileNames, true) ?? [];
            }
            if (!is_array($fileNames)) {
                $fileNames = [];
            }
            
            $fileName = isset($fileNames[$index]) ? $fileNames[$index] : basename($filePath);

            // ✅ Check if file exists before downloading
            if (!Storage::disk('public')->exists($filePath)) {
                return back()->with('error', 'File not found in storage. Please contact administrator.');
            }

            // Increment downloads
            $notice->increment('downloads');

            return Storage::disk('public')->download($filePath, $fileName);

        } catch (\Exception $e) {
            \Log::error('Download error: ' . $e->getMessage());
            return back()->with('error', 'Failed to download file. Please try again.');
        }
    }
}