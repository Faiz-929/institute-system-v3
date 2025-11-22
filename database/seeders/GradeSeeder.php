<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;


class GradeSeeder extends Seeder
{
    
private Faker $faker;

public function __construct()
{
    $this->faker = \Faker\Factory::create();
}

    public function run(): void
    {
        // إنشاء مستخدمين (معلمين)
        $teachers = User::factory()->count(5)->create([
            'role' => 'teacher',
            'password' => Hash::make('password123')
        ]);

        // إنشاء طلاب
        $students = Student::factory()->count(20)->create();

        // إنشاء مواد
        $subjects = Subject::factory()->count(8)->create([
            'is_active' => true
        ]);

        // إنشاء درجات متنوعة للطلاب
        foreach ($students as $student) {
            // اختيار 4-6 مواد عشوائية للطالب
            $studentSubjects = $subjects->random(rand(4, 6));
            
            foreach ($studentSubjects as $subject) {
                $teacher = $teachers->random();
                $semester = $this->faker->randomElement(['الأول', 'الثاني', 'الثالث', 'الرابع']);
                $year = $this->faker->numberBetween(2023, 2025);
                
                // التأكد من عدم تكرار الدرجة
                $existingGrade = Grade::where([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'semester' => $semester,
                    'year' => $year,
                ])->first();
                
                if (!$existingGrade) {
                    $grade = new Grade([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $teacher->id,
                        'semester' => $semester,
                        'year' => $year,
                        
                        // توليد درجات واقعية
                        'homework1' => $this->generateRealisticGrade(),
                        'participation1' => $this->generateRealisticGrade(),
                        'written_exam1' => $this->generateRealisticGrade(),
                        'homework2' => $this->generateRealisticGrade(),
                        'participation2' => $this->generateRealisticGrade(),
                        'written_exam2' => $this->generateRealisticGrade(),
                        'midterm1' => $this->generateRealisticGrade(),
                        'homework3' => $this->generateRealisticGrade(),
                        'participation3' => $this->generateRealisticGrade(),
                        'written_exam3' => $this->generateRealisticGrade(),
                        'homework4' => $this->generateRealisticGrade(),
                        'participation4' => $this->generateRealisticGrade(),
                        'written_exam4' => $this->generateRealisticGrade(),
                        'final_exam' => $this->generateRealisticGrade(),
                    ]);
                    
                    $grade->calculateTotal();
                    $grade->save();
                }
            }
        }

        // إنشاء بعض الدرجات الناجحة والراسبة بشكل متعمد
        $this->createSpecificGrades($students, $subjects, $teachers);
    }

    private function generateRealisticGrade(): int
    {
        // توليد درجات واقعية (معظمها بين 60-95)
        $chance = mt_rand() / mt_getrandmax();
        
        if ($chance < 0.1) {
            return mt_rand(0, 40); // 10% راسب بدرجة ضعيفة
        } elseif ($chance < 0.3) {
            return mt_rand(40, 60); // 20% متوسط
        } elseif ($chance < 0.7) {
            return mt_rand(60, 85); // 40% جيد
        } else {
            return mt_rand(85, 100); // 30% ممتاز
        }
    }

    private function createSpecificGrades($students, $subjects, $teachers)
    {
        $student = $students->first();
        $subject = $subjects->first();
        $teacher = $teachers->first();

        // درجة ممتازة
        $excellentGrade = new Grade([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
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
        $excellentGrade->calculateTotal();
        $excellentGrade->save();

        // درجة راسبة
        $failedGrade = new Grade([
            'student_id' => $student->id,
            'subject_id' => $subjects->skip(1)->first()->id,
            'teacher_id' => $teacher->id,
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
        $failedGrade->calculateTotal();
        $failedGrade->save();
    }

    private function getFaker()
    {
        return \Faker\Factory::create('ar_SA');
    }
}