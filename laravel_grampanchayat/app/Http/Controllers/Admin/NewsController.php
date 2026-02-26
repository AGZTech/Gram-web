<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.news.index', compact('news'));
    }
    
    public function create()
    {
        return view('admin.news.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'published_date' => 'required|date',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            
            // Compress and save image
            $this->compressAndSaveImage($image, 'uploads/news/' . $filename);
            $validated['featured_image'] = 'uploads/news/' . $filename;
        }
        
        $validated['created_by'] = Auth::guard('admin')->id();
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_published'] = $request->has('is_published');
        
        News::create($validated);
        
        return redirect()->route('admin.news.index')
            ->with('success', 'बातमी यशस्वीरित्या जोडली गेली');
    }
    
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }
    
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'published_date' => 'required|date',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($news->featured_image && file_exists(public_path($news->featured_image))) {
                unlink(public_path($news->featured_image));
            }
            
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            
            $this->compressAndSaveImage($image, 'uploads/news/' . $filename);
            $validated['featured_image'] = 'uploads/news/' . $filename;
        }
        
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_published'] = $request->has('is_published');
        
        $news->update($validated);
        
        return redirect()->route('admin.news.index')
            ->with('success', 'बातमी यशस्वीरित्या अपडेट झाली');
    }
    
    public function destroy(News $news)
    {
        if ($news->featured_image && file_exists(public_path($news->featured_image))) {
            unlink(public_path($news->featured_image));
        }
        
        $news->delete();
        
        return redirect()->route('admin.news.index')
            ->with('success', 'बातमी यशस्वीरित्या हटवली गेली');
    }
    
    private function compressAndSaveImage($image, $path)
    {
        $fullPath = public_path($path);
        $directory = dirname($fullPath);
        
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Using GD library for compression
        $extension = strtolower($image->getClientOriginalExtension());
        
        if (in_array($extension, ['jpg', 'jpeg'])) {
            $img = imagecreatefromjpeg($image->getPathname());
            imagejpeg($img, $fullPath, 75);
            imagedestroy($img);
        } elseif ($extension === 'png') {
            $img = imagecreatefrompng($image->getPathname());
            imagepng($img, $fullPath, 6);
            imagedestroy($img);
        } else {
            $image->move($directory, basename($path));
        }
    }
}
