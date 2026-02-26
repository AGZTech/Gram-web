<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::with('creator')
            ->withCount('photos')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.gallery.index', compact('albums'));
    }
    
    public function create()
    {
        return view('admin.gallery.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $filename = time() . '_cover_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/gallery'), $filename);
            $validated['cover_image'] = 'uploads/gallery/' . $filename;
        }
        
        $validated['created_by'] = Auth::guard('admin')->id();
        $validated['is_published'] = $request->has('is_published');
        
        $album = GalleryAlbum::create($validated);
        
        return redirect()->route('admin.gallery.edit', $album)
            ->with('success', 'अल्बम यशस्वीरित्या तयार झाला. आता फोटो जोडा.');
    }
    
    public function edit(GalleryAlbum $gallery)
    {
        $gallery->load('photos');
        return view('admin.gallery.edit', compact('gallery'));
    }
    
    public function update(Request $request, GalleryAlbum $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        if ($request->hasFile('cover_image')) {
            if ($gallery->cover_image && file_exists(public_path($gallery->cover_image))) {
                unlink(public_path($gallery->cover_image));
            }
            
            $image = $request->file('cover_image');
            $filename = time() . '_cover_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/gallery'), $filename);
            $validated['cover_image'] = 'uploads/gallery/' . $filename;
        }
        
        $validated['is_published'] = $request->has('is_published');
        
        $gallery->update($validated);
        
        return redirect()->route('admin.gallery.index')
            ->with('success', 'अल्बम यशस्वीरित्या अपडेट झाला');
    }
    
    public function destroy(GalleryAlbum $gallery)
    {
        // Delete all photos
        foreach ($gallery->photos as $photo) {
            if (file_exists(public_path($photo->image_path))) {
                unlink(public_path($photo->image_path));
            }
            if ($photo->thumbnail_path && file_exists(public_path($photo->thumbnail_path))) {
                unlink(public_path($photo->thumbnail_path));
            }
        }
        
        // Delete cover image
        if ($gallery->cover_image && file_exists(public_path($gallery->cover_image))) {
            unlink(public_path($gallery->cover_image));
        }
        
        $gallery->delete();
        
        return redirect()->route('admin.gallery.index')
            ->with('success', 'अल्बम यशस्वीरित्या हटवला गेला');
    }
    
    public function uploadPhotos(Request $request, GalleryAlbum $album)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        
        $uploaded = 0;
        
        foreach ($request->file('photos') as $image) {
            $filename = time() . '_' . $uploaded . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/gallery'), $filename);
            
            GalleryPhoto::create([
                'album_id' => $album->id,
                'image_path' => 'uploads/gallery/' . $filename,
                'sort_order' => $uploaded,
            ]);
            
            $uploaded++;
        }
        
        return redirect()->back()
            ->with('success', $uploaded . ' फोटो यशस्वीरित्या अपलोड झाले');
    }
    
    public function deletePhoto(GalleryPhoto $photo)
    {
        if (file_exists(public_path($photo->image_path))) {
            unlink(public_path($photo->image_path));
        }
        if ($photo->thumbnail_path && file_exists(public_path($photo->thumbnail_path))) {
            unlink(public_path($photo->thumbnail_path));
        }
        
        $photo->delete();
        
        return redirect()->back()
            ->with('success', 'फोटो यशस्वीरित्या हटवला गेला');
    }
}
