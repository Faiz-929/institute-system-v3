<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('بدء إنشاء الدرجات...');

        // جلب البيانات الموجودة
        $teachers = User::where('role', 'teacher')->get();
        $students = Student::all();
        $subjects = Subject::all();

        // فحص توفر البيانات الأساسية
        if ($teachers->isEmpty()) {
            $this->command->error('لا يوجد معلمين! قم بتشغيل UserSeeder أولاً.');
            return;
        }

        if ($students->isEmpty()) {
            $this->command->error('لا يوجد طلاب! قم بتشغيل StudentSeeder أولاً.');
            return;
        }

        if ($subjects->isEmpty()) {
            $this->command->error('لا يوجد مواد! قم بتشغيل SubjectSeeder أولاً.');
            return;
        }

        $this->command->info("المعلمين: {$teachers->count()}, الطلاب: {$students->count()}, المواد: {$subjects->count()}");

        $semesters = ['الأول', 'الثاني', 'الثالث', 'الرابع'];
        $years = [2023, 2024, 2025];

        foreach ($students as $student) {
            // اختيار عدد عشوائي من المواد (3-6 مواد)
            $numSubjects = min(rand(3, 6), $subjects->count());
            $studentSubjects = $subjects->random($numSubjects);
            
            foreach ($studentSubjects as $subject) {
                foreach ($semesters as $semester) {
                    foreach ($years as $year) {
                        // التحقق من عدم تكرار الدرجة
                        $existingGrade = Grade::where([
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'semester' => $semester,
                            'year' => $year,
                        ])->first();

                        if (!$existingGrade) {
                            try {
                                $grade = Grade::factory()->create([
                                    'student_id' => $student->id,
                                    'subject_id' => $subject->id,
                                    'teacher_id' => $teachers->random()->id,
                                    'semester' => $semester,
                                    'year' => $year,
                                ]);
                            } catch (\Exception $e) {
                                $this->command->warning("خطأ في إنشاء درجة: " . $e->getMessage());
                            }
                        }
                    }
                }
            }
        }

        // إنشاء بعض الدرجات المحددة مسبقاً
        $this->createSpecificGrades($students->take(5), $subjects, $teachers);

        $this->command->info('تم إنشاء الدرجات بنجاح!');
    }

    private function createSpecificGrades($students, $subjects, $teachers)
    {
        $firstStudent = $students->first();
        $firstSubject = $subjects->first();
        $firstTeacher = $teachers->first();

        if ($firstStudent && $firstSubject && $firstTeacher) {
            try {
                // إنشاء درجة ممتازة
                $excellentGrade = new Grade([
                    'student_id' => $firstStudent->id,
                    'subject_id' => $firstSubject->id,
                    'teacher_id' => $firstTeacher->id,
                    'semester' => 'الأول',
                    'year' => '2025',
                    'homework1' => 95,
                    'participation1' => 98,
                    'written_exam1' => 92,
                    'homework2' => 94,
                    'participation2' => 96,
                    'written_exam2' => 90,
                    'midterm1' => 93,
                    'homework3' => 97,
                    'participation3' => 95,
                    'written_exam3' => 91,
                    'homework4' => 96,
                    'participation4' => 94,
                    'written_exam4' => 89,
                    'final_exam' => 98,
                ]);

                if (method_exists($excellentGrade, 'calculateTotal')) {
                    $excellentGrade->calculateTotal();
                }
                $excellentGrade->save();

                // إنشاء درجة راسبة
                if ($subjects->skip(1)->first()) {
                    $failedGrade = new Grade([
                        'student_id' => $firstStudent->id,
                        'subject_id' => $subjects->skip(1)->first()->id,
                        'teacher_id' => $firstTeacher->id,
                        'semester' => 'الثاني',
                        'year' => '2025',
                        'homework1' => 25,
                        'participation1' => 30,
                        'written_exam1' => 20,
                        'homework2' => 28,
                        'participation2' => 35,
                        'written_exam2' => 22,
                        'midterm1' => 26,
                        'homework3' => 32,
                        'participation3' => 28,
                        'written_exam3' => 24,
                        'homework4' => 30,
                        'participation4' => 26,
                        'written_exam4' => 21,
                        'final_exam' => 18,
                    ]);

                    if (method_exists($failedGrade, 'calculateTotal')) {
                        $failedGrade->calculateTotal();
                    }
                    $failedGrade->save();
                }

            } catch (\Exception $e) {
                $this->command->warning("خطأ في إنشاء درجات محددة: " . $e->getMessage());
            }
        }
    }
}