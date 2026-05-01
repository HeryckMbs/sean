<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadAdminController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/solucoes', [PublicController::class, 'services'])->name('services.index');
Route::get('/solucoes/{service:slug}', [PublicController::class, 'service'])->name('services.show');
Route::get('/beneficios', [PublicController::class, 'benefits'])->name('benefits.index');
Route::get('/nichos', [PublicController::class, 'audiences'])->name('audiences.index');
Route::get('/processo', [PublicController::class, 'process'])->name('process.index');
Route::get('/cases', [PublicController::class, 'cases'])->name('cases.index');
Route::get('/cases/{agencyCase}', [PublicController::class, 'case'])->name('cases.show');
Route::get('/depoimentos', [PublicController::class, 'testimonials'])->name('testimonials.index');
Route::get('/perguntas-frequentes', [PublicController::class, 'faqs'])->name('faqs.index');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.store');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/configuracoes', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/configuracoes', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/leads', [LeadAdminController::class, 'index'])->name('leads.index');
    Route::get('/leads/{lead}', [LeadAdminController::class, 'show'])->name('leads.show');
    Route::patch('/leads/{lead}/status', [LeadAdminController::class, 'updateStatus'])->name('leads.status');
    Route::delete('/leads/{lead}', [LeadAdminController::class, 'destroy'])->name('leads.destroy');

    Route::get('/{resource}', [ContentController::class, 'index'])
        ->whereIn('resource', ContentController::resources())
        ->name('content.index');
    Route::get('/{resource}/novo', [ContentController::class, 'create'])
        ->whereIn('resource', ContentController::resources())
        ->name('content.create');
    Route::post('/{resource}', [ContentController::class, 'store'])
        ->whereIn('resource', ContentController::resources())
        ->name('content.store');
    Route::get('/{resource}/{item}/editar', [ContentController::class, 'edit'])
        ->whereIn('resource', ContentController::resources())
        ->name('content.edit');
    Route::put('/{resource}/{item}', [ContentController::class, 'update'])
        ->whereIn('resource', ContentController::resources())
        ->name('content.update');
    Route::delete('/{resource}/{item}', [ContentController::class, 'destroy'])
        ->whereIn('resource', ContentController::resources())
        ->name('content.destroy');
});
