<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Models\Teacher;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\WorkshopController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AttendanceController;

// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});

// مصادقة Laravel Breeze / Jetstream
require __DIR__.'/auth.php';

// جميع المسارات المحمية بالمصادقة
Route::middleware(['auth'])->group(function () {
    // لوحة التحكم
    Route::get('/dashboard', function () {
        $teachersCount = Teacher::count();
        $latestTeachers = Teacher::latest()->take(5)->get();

        return view('dashboard', compact('teachersCount', 'latestTeachers'));
    })->name('dashboard');

    // إدارة الصلاحيات (للإدارة فقط)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/permissions', [UserController::class, 'permissions'])->name('permissions');
        Route::post('/permissions/{user}', [UserController::class, 'updatePermissions'])->name('permissions.update');
    });

    // المسارات الخاصة بالمعلمين (CRUD)
    Route::resource('teachers', TeacherController::class);

    // المسارات الخاصة بالطلاب (CRUD)
    Route::resource('students', StudentController::class);

    // المسار الخاص بطباعة الطلاب
    Route::get('students/print', [StudentController::class, 'print'])->name('students.print');

    // تصدير Excel من صفحة الطباعة
    Route::get('/students/print/export', [StudentController::class, 'printExport'])
        ->name('students.print.export');
    
    // مسار دفعات رسوم الطلاب 
    Route::resource('fees', StudentFeeController::class);

    // دفعات الرسوم (مسار متداخل مبسط)
    Route::post('fees/{fee}/payments', [FeePaymentController::class, 'store'])->name('fees.payments.store');
    Route::delete('payments/{payment}', [FeePaymentController::class, 'destroy'])->name('fees.payments.destroy');

    // مسارات الورش
    Route::resource('workshops', WorkshopController::class);

    // مسارات اضافة مواد الورش
    Route::resource('consumables', ConsumableController::class);

    // مسارات الاصول الثابتة
    Route::resource('assets', AssetController::class);

    // مسارات العهد
    Route::resource('assignments', AssignmentController::class);
    
    // مسار الدرجات 
    Route::resource('grades', GradeController::class);
    
    // مسارات إضافية للدرجات
    Route::get('/grades/export', [GradeController::class, 'export'])->name('grades.export');
    Route::get('/grades/reports', [GradeController::class, 'reports'])->name('grades.reports');
    Route::get('/grades/student/{student}/stats', [GradeController::class, 'studentStats'])->name('grades.student-stats');

    // مسارات الحضور
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/bulk-store', [AttendanceController::class, 'bulkStore'])->name('attendance.bulk-store');
    Route::get('/attendance/reports', [AttendanceController::class, 'reports'])->name('attendance.reports');

    // مسار تقارير الورش
    Route::prefix('reports')->group(function () {
        Route::get('/consumables', [ReportController::class, 'consumablesByWorkshop'])->name('reports.consumables');
        Route::get('/assets', [ReportController::class, 'assetsByWorkshop'])->name('reports.assets');
        Route::get('/assignments', [ReportController::class, 'assignmentsReport'])->name('reports.assignments');
    });

    // إدارة الملف الشخصي للمستخدم
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

