<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ==========================================
// 1. PUBLIC BASE VIEWS & AUTH ROUTES
// ==========================================
Route::get('/', [DashboardController::class, 'showLogin'])->name('login');

// Advanced Secure Web Login Authorization
Route::post('/web-login', function(Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'login_role' => 'required|in:admin,abstractor'
    ]);

    $loginRole = $credentials['login_role'];
    unset($credentials['login_role']); // Remove from database check

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        
        // Ensure user belongs to the requested role panel mode chosen on screen
        if ($user->role !== $loginRole) {
            Auth::logout();
            return back()->withErrors(['email' => 'Access Denied: Role mismatch for selected console node.']);
        }

        $request->session()->regenerate();
        return redirect()->intended($user->role === 'admin' ? '/admin/dashboard' : '/dashboard');
    }

    return back()->withErrors(['email' => 'Invalid identity records or password match.']);
});

Route::post('/web-logout', function(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
});

// Independent Route for Admin AJAX logs lookup (Bypasses session limits)
Route::get('/fetch-ticket-logs/{id}', [DashboardController::class, 'getActivities']);


// ==========================================
// 2. PROTECTED WEB CORE PIPELINES (Requires Login)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Portal Root Dashboards (Guarded via Role Middlewares)
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('role:admin');
    Route::get('/dashboard', [DashboardController::class, 'abstractorDashboard'])->name('abstractor.dashboard')->middleware('role:abstractor');
    
    // 🔥 FIXED: Admin User Management Endpoints
    Route::post('/web/admin/create-user', [DashboardController::class, 'createAbstractor'])->middleware('role:admin');
    
    // Abstractor/User Execution Workflow Triggers
    Route::post('/web/tasks/{id}/accept', [DashboardController::class, 'acceptTask']);
    Route::post('/web/tasks/{id}/update-status', [DashboardController::class, 'updateStatusDropdown']);
    Route::post('/web/tasks/{id}/halt', [DashboardController::class, 'haltTask']);
    Route::post('/web/tasks/{id}/log-activity', [DashboardController::class, 'logActivity']);
    Route::post('/web/tasks/{id}/submit', [DashboardController::class, 'submitReport']);
    
    // Local routing fallback container
    Route::get('/web/tickets/{id}/activities', [DashboardController::class, 'getActivities']);
    

    // routes/web.php ke protected group ke andar is line ko add karo:
Route::post('/web/admin/tickets/{id}/reassign', [DashboardController::class, 'reassignTask'])->middleware('role:admin');
});