<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController; 
use App\Http\Controllers\Admin\MaintenanceController; 
use App\Models\Car;
use App\Models\Category;
use App\Models\Booking;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\BookingController; 
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\ReviewController; 
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use Illuminate\Support\Facades\Artisan; 
use Illuminate\Support\Facades\Mail;

// 🚀 NEW: DB Classes for Bot Setup Hack
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

// ==========================================
// 🛠️ THE EMERGENCY DATABASE FIX ROUTE
// ==========================================
Route::get('/fix-db', function() {
    try {
        Artisan::call('migrate:fresh --force');
        return "<h1>Success!</h1><p>Database tables have been created successfully.</p><a href='/'>Go to Home</a>";
    } catch (\Exception $e) {
        return "<h1>Error!</h1><p>" . $e->getMessage() . "</p>";
    }
});

// 🔐 SECURE ONE-TIME ADMIN PROMOTION ROUTE (Moved outside the admin group)
Route::get('/noor-elite-access-vault-secure-9911', function() {
    // Replace with your registered email address
    $email = 'noor@gmail.com'; 
    
    $user = \App\Models\User::where('email', $email)->first();
    
    if($user) {
        $user->update(['role' => 'admin']);
        return "<div style='text-align:center; padding:50px; font-family:sans-serif; background:#0b1120; color:white; min-height:100vh;'>
                    <h1 style='color:#f97316;'>ACCESS GRANTED</h1>
                    <p>Status: Account successfully promoted to Administrator.</p>
                    <br>
                    <a href='/noor-secure-vault-786/dashboard' style='color:#fff; background:#f97316; padding:12px 25px; text-decoration:none; border-radius:10px; font-weight:bold;'>ENTER ADMIN PANEL</a>
                </div>";
    }
    
    return "<h1 style='color:red;'>ACCESS DENIED</h1><p>Security protocols active. User not found in database.</p>";
});

// ==========================================
// 1. PUBLIC FRONTEND ROUTES
// ==========================================
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/our-fleet', [FrontendController::class, 'fleet'])->name('fleet');
Route::get('/car-details/{car}', [FrontendController::class, 'showCar'])->name('car.show');
Route::get('/about', [FrontendController::class, 'about'])->name('about');

// 🚀 NEW: SERVICES PAGE ROUTE (Assessment Requirement)
Route::get('/services', [FrontendController::class, 'services'])->name('services');

// 🌟 THE NEW CONTACT ENGINE ROUTES 🌟
Route::get('/contact', [ContactController::class, 'index'])->name('contact'); 
Route::post('/contact-submit', [ContactController::class, 'store'])->name('contact.store'); 

// 🌟 THE DYNAMIC REVIEW ROUTE 🌟
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// 🚀 NEWSLETTER SUBSCRIPTION ROUTE
Route::post('/newsletter-subscribe', [FrontendController::class, 'subscribe'])->name('newsletter.subscribe');

// 🤖 CUSTOM AI CHATBOT ROUTE
Route::post('/chatbot-reply', [FrontendController::class, 'chatbotReply'])->name('chatbot.reply');


// ==========================================
// 2. THE TRAFFIC POLICE (Login logic)
// ==========================================
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('frontend.customer_portal'); 
})->middleware(['auth', 'verified'])->name('dashboard');


// ==========================================
// 3. PROTECTED USER & BOOKING ROUTES (Must be logged in)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // THE NEW CHECKOUT ENGINE
    Route::post('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    // THE BOOKING STORAGE
    Route::post('/book-now', [BookingController::class, 'store'])->name('bookings.store.web');

    // NEW: Customer Invoice Download Route
    Route::get('/my-bookings/{booking}/invoice', [BookingController::class, 'downloadInvoice'])->name('user.invoice');
    
    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==========================================
// 4. TOP SECRET ADMIN VAULT (Only for Admin)
// ==========================================

// 🚀 1. CUSTOM ADMIN LOGIN PAGE ROUTE
Route::get('/admin-login-elite', function () {
    return view('admin.login'); 
})->name('admin.login.page');

// 🚀 2. CUSTOM ADMIN LOGIN SUBMIT ROUTE
Route::post('/admin-login-elite', [SettingController::class, 'adminLogin'])->name('admin.login.submit');

Route::middleware(['auth', 'admin'])->prefix('noor-secure-vault-786')->group(function () {
    
    Route::get('/dashboard', function () {
        $total_cars = Car::count();
        $total_categories = Category::count();
        $total_bookings = Booking::count();
        $total_revenue = Booking::where('status', 'Completed')->sum('total_price');
        $pending_bookings = Booking::where('status', 'Pending')->count();

        return view('admin.dashboard', compact(
            'total_cars', 
            'total_categories', 
            'total_bookings', 
            'total_revenue', 
            'pending_bookings'
        ));
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('cars', CarController::class);
    
    // Reservations Management
    Route::get('/bookings/{booking}/invoice', [AdminBookingController::class, 'invoice'])->name('bookings.invoice');
    Route::resource('bookings', AdminBookingController::class);
    
    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('users', UserController::class)->only(['index', 'destroy']);
    
    // 📊 PAYMENTS & FINANCIAL ROUTES
    Route::get('/payments', [AdminBookingController::class, 'payments'])->name('admin.payments');
    Route::get('/payments/export', [AdminBookingController::class, 'exportLeads'])->name('admin.payments.export'); // 🚀 NEW EXPORT ROUTE
    
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // THE NEW ADMIN INBOX ROUTE
    Route::resource('contacts', AdminContactController::class)->only(['index', 'update', 'destroy']);

    // THE NEW ADMIN REVIEW MANAGEMENT ROUTE
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'update', 'destroy']);

    // 🚀 NEW: ADMIN NEWSLETTER EXPORT & MANAGEMENT (Step 2 Update) 🚀
    Route::get('newsletters/export', [AdminNewsletterController::class, 'export'])->name('newsletters.export');
    Route::resource('newsletters', AdminNewsletterController::class)->only(['index', 'destroy']);

});

// 🚀 UPGRADED: STORAGE & CACHE EMERGENCY FIX (Added at the end)
Route::get('/run-storage-link', function() {
    try {
        // 1. Create Storage Link
        if (file_exists(public_path('storage'))) {
            @unlink(public_path('storage'));
        }
        Artisan::call('storage:link');

        // 2. Clear All Caches (Fixes 419/403/Image Issues)
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        
        return "<h1>Success!</h1><p>Storage linked and System Cache cleared successfully.</p><a href='/'>Go to Home</a>";
    } catch (\Exception $e) {
        return "<h1>Error!</h1><p>" . $e->getMessage() . "</p>";
    }
});

// 🚀 NEW: THE SPEED BOOST ROUTE (To fix slow loading on Railway)
Route::get('/speed-boost', function() {
    try {
        // Sab kuch clear karke fresh cache banayein
        Artisan::call('optimize:clear');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        
        // 🚀 NEW: Event aur Optimization cache
        Artisan::call('event:cache');
        
        return "<h1>ULTIMATE BOOST ACTIVATED! ⚡</h1><p>Website is now running from pre-compiled memory.</p>";
    } catch (\Exception $e) {
        return "<h1>Error:</h1> " . $e->getMessage();
    }
});

// 🤖 THE SECRET BOT DB SETUP HACK (Run this on Live Server)
Route::get('/setup-bot-db', function () {
    try {
        // 1. Agar table nahi hai toh banayega
        if (!Schema::hasTable('bot_responses')) {
            Schema::create('bot_responses', function (Blueprint $table) {
                $table->id();
                $table->string('keyword')->unique();
                $table->text('response');
                $table->timestamps();
            });
        }

        // 2. Initial Testing Responses Daalega
        DB::table('bot_responses')->insertOrIgnore([
            ['keyword' => 'pricing', 'response' => 'Our daily rent starts from 5,000 PKR for economy cars.', 'created_at' => now(), 'updated_at' => now()],
            ['keyword' => 'rent', 'response' => 'Daily rent is for a 24-hour cycle. We offer discounts for weekly rentals.', 'created_at' => now(), 'updated_at' => now()],
            ['keyword' => 'booking', 'response' => 'To book, pick a car from the Fleet page and click Book Now.', 'created_at' => now(), 'updated_at' => now()],
            ['keyword' => 'civic', 'response' => 'Honda Civic is available for 12,000 PKR/day.', 'created_at' => now(), 'updated_at' => now()],
            ['keyword' => 'hi', 'response' => 'Hello! I am the Elite Bot. How can I help you today?', 'created_at' => now(), 'updated_at' => now()]
        ]);

        return "<h1>Database Ready! 🚀</h1><p>Chatbot table created and initial data inserted successfully.</p><a href='/'>Go to Home</a>";
    } catch (\Exception $e) {
        return "<h1>Error:</h1> " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';