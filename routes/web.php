<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

// トップページ → ログインへリダイレクト
Route::get('/', function () {
    return redirect('/login');
});

// 認証不要ルート
Route::get('/login',  [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/password/reset', fn() => view('auth.forgot'))->name('password.request');

Route::get('/reflection/create', [ReflectionController::class, 'create'])->name('reflection.create');
Route::post('/reflection', [ReflectionController::class, 'store'])->name('reflection.store');

Route::get('/settings',  [SettingsController::class, 'index'])->name('settings');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

// 認証が必要なルート
Route::middleware('auth')->group(function () {

    // ログアウト
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ダッシュボード
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/activity/log', [ActivityController::class, 'create'])->name('activity.create');
    Route::post('/activity/log', [ActivityController::class, 'store'])->name('activity.store');

    Route::get('/goals',           [GoalController::class, 'index'])->name('goal.index');
    Route::get('/goals/create',    [GoalController::class, 'create'])->name('goal.create');
    Route::post('/goals',          [GoalController::class, 'store'])->name('goal.store');
    Route::patch('/goals/{goal}',  [GoalController::class, 'update'])->name('goal.update');
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goal.destroy');

    Route::get('/reflection/create',      [ReflectionController::class, 'create'])->name('reflection.create');
    Route::post('/reflection',             [ReflectionController::class, 'store'])->name('reflection.store');
    Route::get('/reflection/{reflection}/edit', [ReflectionController::class, 'edit'])->name('reflection.edit');
    Route::put('/reflection/{reflection}', [ReflectionController::class, 'update'])->name('reflection.update');
    Route::delete('/reflection/{reflection}', [ReflectionController::class, 'destroy'])->name('reflection.destroy');

    // プロフィール
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
