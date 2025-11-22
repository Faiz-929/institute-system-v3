<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;      // ✅ تصحيح - استخدام User بدلاً من Teacher
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('ar_SA');

        // جلب المستخدمين الذين دورهم معلم
        $teachers = User::where('role', 'teacher')->get();
        
        // التأكد من وجود معلمين
        if ($teachers->count() === 0) {
            $this->command->error('لا يوجد معلمين في قاعدة البيانات! قم بتشغيل UserSeeder أولاً.');
            return;
        }

        // التأكد من وجود طلاب
        $students = Student::all();
        if ($students->count() === 0) {
            $this->command->error('لا يوجد طلاب في قاعدة البيانات! قم بتشغيل StudentSeeder أولاً.');
            return;
        }

        $subjects = ['رياضيات', 'لغة عربية', 'علوم', 'إنجليزي', 'تاريخ'];
        $classes = ['A', 'B', 'C'];
        $statuses = ['حاضر', 'غائب', 'متأخر', 'مُعفى'];

        // إنشاء حضور لـ 14 يوم الماضية
        for ($d = 0; $d < 14; $d++) {
            $date = Carbon::now()->subDays($d)->toDateString();
            
            foreach ($students as $student) {
                // تخطي بعض السجلات عشوائياً لمحاكاة البيانات الحقيقية
                if (rand(0, 10) < 2) continue;
                
                $subject = $faker->randomElement($subjects);
                $class = $faker->randomElement($classes);
                $teacher = $teachers->random();
                $status = $faker->randomElement($statuses);
                
                // تحديد عدد دقائق التأخير
                $late_minutes = $status === 'متأخر' ? rand(1, 30) : null;
                
                // تحديد سبب الغياب
                $absence_reason = $status === 'غائب' ? $faker->sentence : null;

                // التحقق من عدم تكرار نفس سجل الحضور
                $existingAttendance = Attendance::where([
                    'student_id' => $student->id,
                    'teacher_id' => $teacher->id,
                    'session_date' => $date,
                    'subject_name' => $subject,
                    'class_name' => $class,
                ])->first();

                if (!$existingAttendance) {
                    Attendance::create([
                        'student_id' => $student->id,
                        'teacher_id' => $teacher->id,
                        'subject_name' => $subject,
                        'class_name' => $class,
                        'session_date' => $date,
                        'session_time' => $faker->time('H:i'),
                        'status' => $status,
                        'absence_reason' => $absence_reason,
                        'late_minutes' => $late_minutes,
                        'notes' => null,
                        'recorded_by' => rand(0, 1) ? 'teacher' : 'admin'
                    ]);
                }
            }
        }

        $this->command->info('تم إنشاء سجلات الحضور بنجاح!');
    }
}