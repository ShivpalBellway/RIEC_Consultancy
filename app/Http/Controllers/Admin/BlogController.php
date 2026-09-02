<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    use LogsActivity;
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);

        return view('pages.admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('pages.admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'required|boolean',
        ]);

        // Generate unique slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image' => $imagePath,
            'is_published' => $request->is_published,
            'published_at' => $request->is_published ? now() : null,
        ]);
        $this->log('created', 'Blog', 'Added: ' . $request->title);
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        return view('pages.admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'required|boolean',
        ]);

        // Re-generate slug if title has changed
        if ($blog->title !== $request->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $counter = 1;
            while (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $blog->slug = $slug;
        }

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->image = $request->file('image')->store('blogs', 'public');
        }

        // Set published_at if publishing status changes
        if ($request->is_published && !$blog->is_published) {
            $blog->published_at = now();
        } elseif (!$request->is_published) {
            $blog->published_at = null;
        }

        $blog->title = $request->title;
        $blog->excerpt = $request->excerpt;
        $blog->content = $request->content;
        $blog->is_published = $request->is_published;
        $blog->save();
        $this->log('updated', 'Blog', 'Updated: ' . $blog->title);
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function togglePublish(Blog $blog)
    {
        $blog->is_published = !$blog->is_published;
        $blog->published_at = $blog->is_published ? now() : null;
        $blog->save();
        $status = $blog->is_published ? 'published' : 'unpublished';
        $this->log('toggled', 'Blog', ucfirst($status) . ': ' . $blog->title);

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success'      => true,
                'is_published' => $blog->is_published,
                'published_at' => $blog->published_at
                    ? $blog->published_at->format('M d, Y h:i A')
                    : null,
                'message'      => "Blog post {$status} successfully.",
            ]);
        }

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', "Blog post {$status} successfully.");
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();
        $this->log('deleted', 'Blog', 'Deleted: ' . $blog->title);
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully.');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('blogs/media', 'public');
            $url = asset('storage/' . $path);
            
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg');</script>";
            
            return response($response)->header('Content-Type', 'text/html');
        }
        
        return response('Upload failed')->header('Content-Type', 'text/plain');
    }
}
