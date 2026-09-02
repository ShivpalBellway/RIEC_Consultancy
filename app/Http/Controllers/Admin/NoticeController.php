<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NoticeController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of notices.
     */
    public function index(Request $request)
    {
        $query = Notice::with('creator');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $notices = $query->latest()->paginate(15);
        
        $stats = [
            'total' => Notice::count(),
            'published' => Notice::where('status', 'published')->count(),
            'draft' => Notice::where('status', 'draft')->count(),
            'archived' => Notice::where('status', 'archived')->count(),
            'pinned' => Notice::where('is_pinned', true)->count(),
        ];

        return view('pages.admin.notices.index', compact('notices', 'stats'));
    }

    /**
     * Show the form for creating a new notice.
     */
    public function create()
    {
        return view('pages.admin.notices.create');
    }

    /**
     * Store a newly created notice.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,important,urgent,event,update',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'is_pinned' => 'nullable|boolean',
            'files.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $data = $request->except(['files']);
        
        // Handle file uploads
        $filePaths = [];
        $fileNames = [];
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('notices/' . date('Y/m'), 'public');
                $filePaths[] = $path;
                $fileNames[] = $file->getClientOriginalName();
            }
        }

        // Convert to JSON for storage
        $data['file_paths'] = !empty($filePaths) ? json_encode($filePaths) : null;
        $data['file_names'] = !empty($fileNames) ? json_encode($fileNames) : null;
        $data['created_by'] = session('admin_id');
        $data['is_pinned'] = $request->has('is_pinned');
        // Admin South Korea ka hai — form se aane wali date KST (Asia/Seoul) mein parse karo
        // now() bhi KST dega kyunki app timezone Asia/Seoul set hai
        $data['published_at'] = !empty($request->published_at)
            ? Carbon::parse($request->published_at, 'Asia/Seoul')
            : ($request->status == 'published' ? now() : null);

        $notice = Notice::create($data);

        $this->log('created', 'notice', 'Created notice: ' . $notice->title);

        return redirect()->route('admin.notices.index')
                         ->with('success', 'Notice created successfully.');
    }

    /**
     * Display the specified notice.
     */
    public function show(Notice $notice)
    {
        // Admin view - NO view count increment
        $this->log('view', 'notice', 'Admin viewed notice: ' . $notice->title);

        return view('pages.admin.notices.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified notice.
     */
    public function edit(Notice $notice)
    {
        return view('pages.admin.notices.edit', compact('notice'));
    }

    /**
     * Update the specified notice.
     */
    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,important,urgent,event,update',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'is_pinned' => 'nullable|boolean',
            'files.*' => 'nullable|file|max:10240',
            'delete_files' => 'nullable|array',
        ]);

        $data = $request->except(['files', 'delete_files']);
        
        // Get existing file paths and names
        $filePaths = $notice->file_paths;
        if (is_string($filePaths)) {
            $filePaths = json_decode($filePaths, true) ?? [];
        }
        if (!is_array($filePaths)) {
            $filePaths = [];
        }
        
        $fileNames = $notice->file_names;
        if (is_string($fileNames)) {
            $fileNames = json_decode($fileNames, true) ?? [];
        }
        if (!is_array($fileNames)) {
            $fileNames = [];
        }
        
        // Handle new file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('notices/' . date('Y/m'), 'public');
                $filePaths[] = $path;
                $fileNames[] = $file->getClientOriginalName();
            }
        }

        // Delete files
        if ($request->filled('delete_files')) {
            foreach ($request->delete_files as $index) {
                if (isset($filePaths[$index])) {
                    // Delete file from storage
                    if (Storage::disk('public')->exists($filePaths[$index])) {
                        Storage::disk('public')->delete($filePaths[$index]);
                    }
                    unset($filePaths[$index]);
                    unset($fileNames[$index]);
                }
            }
            
            // Re-index arrays
            $filePaths = array_values($filePaths);
            $fileNames = array_values($fileNames);
        }

        // Convert to JSON for storage
        $data['file_paths'] = !empty($filePaths) ? json_encode($filePaths) : null;
        $data['file_names'] = !empty($fileNames) ? json_encode($fileNames) : null;
        
        $data['is_pinned'] = $request->has('is_pinned');
        // Admin South Korea ka hai — form se aane wali date KST (Asia/Seoul) mein parse karo
        // now() bhi KST dega kyunki app timezone Asia/Seoul set hai
        $data['published_at'] = !empty($request->published_at)
            ? Carbon::parse($request->published_at, 'Asia/Seoul')
            : ($request->status == 'published' ? now() : null);

        $notice->update($data);

        $this->log('updated', 'notice', 'Updated notice: ' . $notice->title);

        return redirect()->route('admin.notices.index')
                         ->with('success', 'Notice updated successfully.');
    }

    /**
     * Remove the specified notice.
     */
    public function destroy(Notice $notice)
    {
        // Delete files
        $filePaths = $notice->file_paths;
        if (is_string($filePaths)) {
            $filePaths = json_decode($filePaths, true) ?? [];
        }
        if (is_array($filePaths)) {
            foreach ($filePaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $title = $notice->title;
        $notice->delete();

        $this->log('deleted', 'notice', 'Deleted notice: ' . $title);

        return redirect()->route('admin.notices.index')
                         ->with('success', 'Notice deleted successfully.');
    }

    /**
     * Download file from notice.
     */
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

            // Check if file exists
            if (!Storage::disk('public')->exists($filePath)) {
                return back()->with('error', 'File not found in storage.');
            }

            // Increment downloads
            $notice->increment('downloads');

            $this->log('download', 'notice', 'Downloaded file from notice: ' . $notice->title);

            return Storage::disk('public')->download($filePath, $fileName);

        } catch (\Exception $e) {
            \Log::error('Download error: ' . $e->getMessage());
            return back()->with('error', 'Failed to download file.');
        }
    }

    /**
     * Toggle notice status.
     */
    public function toggleStatus(Notice $notice)
    {
        $statuses = ['draft', 'published', 'archived'];
        $currentIndex = array_search($notice->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        $newStatus = $statuses[$nextIndex];

        $notice->update([
            'status' => $newStatus,
            'published_at' => $newStatus == 'published' ? now() : $notice->published_at,
        ]);

        $this->log('toggled', 'notice', 'Changed notice status to ' . $newStatus . ': ' . $notice->title);

        return response()->json([
            'success' => true,
            'message' => 'Status updated to ' . ucfirst($newStatus),
            'status' => $newStatus,
        ]);
    }

    /**
     * Toggle notice pinned status.
     */
    public function togglePinned(Notice $notice)
    {
        $notice->update(['is_pinned' => !$notice->is_pinned]);

        $this->log('toggled', 'notice', ($notice->is_pinned ? 'Pinned' : 'Unpinned') . ' notice: ' . $notice->title);

        return response()->json([
            'success' => true,
            'message' => ($notice->is_pinned ? 'Pinned' : 'Unpinned') . ' notice successfully.',
            'is_pinned' => $notice->is_pinned,
        ]);
    }

    /**
     * Bulk delete notices.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:notices,id',
        ]);

        $notices = Notice::whereIn('id', $request->ids)->get();
        
        foreach ($notices as $notice) {
            $filePaths = $notice->file_paths;
            if (is_string($filePaths)) {
                $filePaths = json_decode($filePaths, true) ?? [];
            }
            if (is_array($filePaths)) {
                foreach ($filePaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
        }

        Notice::whereIn('id', $request->ids)->delete();

        $this->log('bulk_delete', 'notice', 'Bulk deleted ' . count($request->ids) . ' notices');

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' notices deleted successfully.',
        ]);
    }
}