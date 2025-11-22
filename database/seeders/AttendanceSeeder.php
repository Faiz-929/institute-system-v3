<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;    // ✅ استخدام نموذج Teacher
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('بدء إنشاء سجلات الحضور...');

        // جلب المعلمين من جدول teachers (ليس users)
        $teachers = Teacher::all();
        
        // التأكد من وجود معلمين
        if ($teachers->count() === 0) {
            $this->command->error('❌ لا يوجد معلمين في جدول teachers! قم بتشغيل TeacherSeeder أولاً.');
            return;
        }

        $this->command->info("✅ تم العثور على {$teachers->count()} معلم");

        // جلب الطلاب
        $students = Student::all();
        if ($students->count() === 0) {
            $this->command->error('❌ لا يوجد طلاب! قم بتشغيل StudentSeeder أولاً.');
            return;
        }

        $this->command->info("✅ تم العثور على {$students->count()} طالب");

        // البيانات الأساسية
        $subjects = ['رياضيات', 'لغة عربية', 'علوم', 'إنجليزي', 'تاريخ', 'فيزياء', 'كيمياء'];
        $classes = ['A', 'B', 'C', 'D'];
        $statuses = ['حاضر', 'غائب', 'متأخر', 'مُعفى'];
        $recordedBy = ['teacher', 'admin'];

        $createdCount = 0;

        // إنشاء حضور للـ 30 يوم الماضية
        for ($d = 0; $d < 30; $d++) {
            $date = Carbon::now()->subDays($d)->toDateString();
            $dayName = Carbon::now()->subDays($d)->format('l');
            
            // تخطي أيام الجمعة والسبت (عطلة نهاية الأسبوع)
            if ($dayName === 'Friday' || $dayName === 'Saturday') {
                continue;
            }

            foreach ($students as $student) {
                // تخطي بعض السجلات عشوائياً لمحاكاة البيانات الحقيقية
                if (rand(0, 10) < 3) continue;
                
                $subject = $faker->randomElement($subjects);
                $class = $faker->randomElement($classes);
                $teacher = $teachers->random();
                $status = $faker->randomElement($statuses);
                $recorded = $faker->randomElement($recordedBy);
                
                // تحديد وقت الجلسة (من 8 صباحاً إلى 2 ظهراً)
                $sessionTime = sprintf('%02d:%02d', 
                    $faker->numberBetween(8, 14), 
                    $faker->numberBetween(0, 59)
                );
                
                // تحديد عدد دقائق التأخير
                $lateMinutes = $status === 'متأخر' ? $faker->numberBetween(1, 30) : null;
                
                // تحديد سبب الغياب
                $absenceReason = $status === 'غائب' ? $faker->randomElement([
                    'مرض', 'ظروف عائلية', 'طارئ', 'إذن', 'بدون عذر'
                ]) : null;
                
                // تعيين ملاحظات
                $notes = $status === 'مُعفى' ? 'معفي من المعلم' : null;

                // التحقق من عدم تكرار نفس سجل الحضور
                $existingAttendance = Attendance::where([
                    'student_id' => $student->id,
                    'teacher_id' => $teacher->id,
                    'session_date' => $date,
                    'subject_name' => $subject,
                    'class_name' => $class,
                    'session_time' => $sessionTime,
                ])->first();

                if (!$existingAttendance) {
                    try {
                        Attendance::create([
                            'student_id' => $student->id,
                            'teacher_id' => $teacher->id,     // ✅ استخدام teacher_id من جدول teachers
                            'subject_name' => $subject,
                            'class_name' => $class,
                            'session_date' => $date,
                            'session_time' => $sessionTime,
                            'status' => $status,
                            'absence_reason' => $absenceReason,
                            'late_minutes' => $lateMinutes,
                            'notes' => $notes,
                            'recorded_by' => $recorded
                        ]);
                        
                        $createdCount++;
                        
                        // طباعة تقدم كل 50 سجل
                        if ($createdCount % 50 === 0) {
                            $this->command->info("تم إنشاء {$createdCount} سجل حضور...");
                        }
                        
                    } catch (\Exception $e) {
                        $this->command->warning("❌ خطأ في إنشاء سجل حضور: " . $e->getMessage());
                    }
                }
            }
        }

        $this->command->info("✅ تم إنشاء {$createdCount} سجل حضور بنجاح!");
        
        // عرض إحصائيات
        $this->command->info("📊 إحصائيات الحضور:");
        $attendanceStats = Attendance::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        foreach ($attendanceStats as $stat) {
            $this->command->info("  {$stat->status}: {$stat->count} سجل");
        }
    }
}