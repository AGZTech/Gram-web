<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\NewsController as FrontendNewsController;
use App\Http\Controllers\Frontend\NoticeController as FrontendNoticeController;
use App\Http\Controllers\Frontend\SchemeController as FrontendSchemeController;
use App\Http\Controllers\Frontend\WorkController as FrontendWorkController;
use App\Http\Controllers\Frontend\GalleryController as FrontendGalleryController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\SchemeController;
use App\Http\Controllers\Admin\WorkController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\BackupController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['track.visitor'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Static Pages
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/history', [PageController::class, 'history'])->name('history');
    Route::get('/members', [PageController::class, 'members'])->name('members');
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
    
    // News
    Route::get('/news', [FrontendNewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [FrontendNewsController::class, 'show'])->name('news.show');
    
    // Notices
    Route::get('/notices', [FrontendNoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/{id}', [FrontendNoticeController::class, 'show'])->name('notices.show');
    
    // Schemes
    Route::get('/schemes', [FrontendSchemeController::class, 'index'])->name('schemes.index');
    Route::get('/schemes/{slug}', [FrontendSchemeController::class, 'show'])->name('schemes.show');
    
    // Development Works
    Route::get('/works', [FrontendWorkController::class, 'index'])->name('works.index');
    Route::get('/works/{id}', [FrontendWorkController::class, 'show'])->name('works.show');
    
    // Gallery
    Route::get('/gallery', [FrontendGalleryController::class, 'index'])->name('gallery.index');
    Route::get('/gallery/{slug}', [FrontendGalleryController::class, 'show'])->name('gallery.show');
    
    // Services
    Route::get('/services', [FrontendServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{slug}', [FrontendServiceController::class, 'show'])->name('services.show');
    
    // Contact
    Route::get('/contact', [FrontendContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [FrontendContactController::class, 'store'])->name('contact.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        
        // Pages
        Route::resource('pages', AdminPageController::class);
        
        // Notices
        Route::resource('notices', NoticeController::class);
        
        // News
        Route::resource('news', NewsController::class);
        
        // Schemes
        Route::resource('schemes', SchemeController::class);
        
        // Development Works
        Route::resource('works', WorkController::class);
        
        // Gallery
        Route::resource('gallery', GalleryController::class);
        Route::post('/gallery/{album}/photos', [GalleryController::class, 'uploadPhotos'])->name('gallery.photos.upload');
        Route::delete('/gallery/photos/{photo}', [GalleryController::class, 'deletePhoto'])->name('gallery.photos.delete');
        
        // Services
        Route::resource('services', ServiceController::class);
        
        // Contact Inquiries
        Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.status');
        Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
        
        // Members
        Route::resource('members', MemberController::class);
        
        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        
        // Super Admin Only Routes
        Route::middleware(['super.admin'])->group(function () {
            // User Management
            Route::resource('users', UserController::class);
            
            // Backup
            Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
            Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
            Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
        });
    });
});
