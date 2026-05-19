<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProgressReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;

// Guest routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard - direktur, admin, marketing, team_leader (tenaga_ahli tidak punya dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:direktur,admin,marketing,team_leader');

    // Projects - View (direktur, admin, marketing, team_leader)
    Route::middleware('role:direktur,admin,marketing,team_leader')->group(function () {
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    });

    // Project Create/Store - marketing & admin only (direktur TIDAK bisa buat proyek)
    Route::middleware('role:admin,marketing')->group(function () {
        Route::get('/projects-create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    });

    // Project Edit/Update/Delete - admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    });

    // Assign Team Leader - direktur & admin only
    Route::post('/projects/{project}/assign-leader', [ProjectController::class, 'assignTeamLeader'])
        ->name('projects.assignLeader')
        ->middleware('role:direktur,admin');

    // Project Members Management - team_leader & admin
    Route::middleware('role:admin,team_leader')->group(function () {
        Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.addMember');
        Route::delete('/projects/{project}/members/{member}', [ProjectController::class, 'removeMember'])->name('projects.removeMember');
    });

    // Clients - marketing & admin only (direktur TIDAK bisa akses klien)
    Route::middleware('role:admin,marketing')->group(function () {
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    });

    // Tasks - all authenticated users can view their tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    // Task creation - team_leader & admin only
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store')
        ->middleware('role:admin,team_leader');
    // Task status update - all auth (controller will check ownership for tenaga_ahli)
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    // Laporan - Consolidated all reports: direktur, admin & team_leader
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index')
        ->middleware('role:direktur,admin,team_leader');

    // Progress Reports - View: direktur, admin, team_leader
    Route::get('/projects/{project}/reports', [ProgressReportController::class, 'index'])
        ->name('reports.index')
        ->middleware('role:direktur,admin,team_leader');

    // Progress Reports - Create/Store: team_leader & admin only (direktur hanya lihat)
    Route::middleware('role:admin,team_leader')->group(function () {
        Route::get('/projects/{project}/reports/create', [ProgressReportController::class, 'create'])->name('reports.create');
        Route::post('/projects/{project}/reports', [ProgressReportController::class, 'store'])->name('reports.store');
    });

    // Schedules - team_leader, direktur, admin
    Route::middleware('role:direktur,admin,team_leader')->group(function () {
        Route::get('/projects/{project}/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::post('/projects/{project}/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    });

    // User Management - admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    });
});

