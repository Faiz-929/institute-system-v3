<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('بدء إنشاء بيانات المعلمين...');

        // إنشاء معلمين محددين مسبقاً
        $specificTeachers = [
            [
                'name' => 'أحمد محمد الإدريسي',
                'email' => 'ahmed@institute.com',
                'qualification' => 'ماجستير رياضيات',
                'subject' => 'رياضيات',
                'phone' => '0501234567',
                'home_phone' => '0111234567',
                'address' => 'حي الملز، الرياض',
                'specialization' => 'رياضيات متقدمة',
                'experience_years' => 8,
                'salary' => 15000,
                'status' => 'active'
            ],
            [
                'name' => 'فاطمة أحمد السالم',
                'email' => 'fatima@institute.com',
                'qualification' => 'بكالوريوس لغة عربية',
                'subject' => 'لغة عربية',
                'phone' => '0509876543',
                'home_phone' => '0119876543',
                'address' => 'حي العليا، الرياض',
                'specialization' => 'أدب ونحو',
                'experience_years' => 12,
                'salary' => 14000,
                'status' => 'active'
            ],
            [
                'name' => 'محمد علي الحربي',
                'email' => 'mohamed@institute.com',
                'qualification' => 'بكالوريوس علوم',
                'subject' => 'علوم',
                'phone' => '0504567890',
                'home_phone' => '0114567890',
                'address' => 'حي النرجس، الرياض',
                'specialization' => 'كيمياء وفيزياء',
                'experience_years' => 6,
                'salary' => 13500,
                'status' => 'active'
            ],
            [
                'name' => 'سارة حسن المطيري',
                'email' => 'sara@institute.com',
                'qualification' => 'ماجستير لغة إنجليزية',
                'subject' => 'لغة إنجليزية',
                'phone' => '0503216549',
                'home_phone' => '0113216549',
                'address' => 'حي الياسمين، الرياض',
                'specialization' => 'اللغة الإنجليزية',
                'experience_years' => 10,
                'salary' => 16000,
                'status' => 'active'
            ],
            [
                'name' => 'عبدالله يوسف القحطاني',
                'email' => 'abdullah@institute.com',
                'qualification' => 'بكالوريوس تاريخ وجغرافيا',
                'subject' => 'تاريخ',
                'phone' => '0507890123',
                'home_phone' => '0117890123',
                'address' => 'حي الملك فهد، الرياض',
                'specialization' => 'التاريخ العربي والإسلامي',
                'experience_years' => 15,
                'salary' => 14500,
                'status' => 'active'
            ]
        ];

        $createdCount = 0;

        foreach ($specificTeachers as $teacherData) {
            try {
                // التحقق من عدم تكرار البريد الإلكتروني
                $existingTeacher = Teacher::where('email', $teacherData['email'])->first();
                if (!$existingTeacher) {
                    Teacher::create($teacherData);
                    $createdCount++;
                    
                    $this->command->info("✅ تم إنشاء المعلم: {$teacherData['name']}");
                } else {
                    $this->command->warning("⚠️ المعلم موجود مسبقاً: {$teacherData['name']}");
                }
            } catch (\Exception $e) {
                $this->command->error("❌ خطأ في إنشاء المعلم {$teacherData['name']}: " . $e->getMessage());
            }
        }

        // إنشاء معلمين إضافيين باستخدام Factory (إذا كان موجوداً)
        try {
            $additionalCount = 10;
            Teacher::factory()->count($additionalCount)->create();
            $createdCount += $additionalCount;
            
            $this->command->info("✅ تم إنشاء {$additionalCount} معلم إضافيين بشكل عشوائي");
        } catch (\Exception $e) {
            $this->command->warning("⚠️ Factory غير متوفر للمعلمين، سيتم إنشاء معلمين يدوياً");

            // إنشاء معلمين يدويين إضافيين
            $additionalTeachers = [
                [
                    'name' => 'خالد عبدالله الشمري',
                    'email' => 'khalid' . rand(100, 999) . '@institute.com',
                    'qualification' => 'بكالوريوس فيزياء',
                    'subject' => 'فيزياء',
                    'phone' => '050' . rand(1000000, 9999999),
                    'address' => 'حي الفيصلية، الرياض',
                    'status' => 'active'
                ],
                [
                    'name' => 'نورا محمد العتيبي',
                    'email' => 'nora' . rand(100, 999) . '@institute.com',
                    'qualification' => 'بكالوريوس كيمياء',
                    'subject' => 'كيمياء',
                    'phone' => '050' . rand(1000000, 9999999),
                    'address' => 'حي الروضة، الرياض',
                    'status' => 'active'
                ],
                [
                    'name' => 'سعد عبدالعزيز الدوسري',
                    'email' => 'saad' . rand(100, 999) . '@institute.com',
                    'qualification' => 'ماجستير حاسوب',
                    'subject' => 'حاسوب',
                    'phone' => '050' . rand(1000000, 9999999),
                    'address' => 'حي اليرموك، الرياض',
                    'status' => 'active'
                ]
            ];

            foreach ($additionalTeachers as $teacher) {
                try {
                    Teacher::create($teacher);
                    $createdCount++;
                } catch (\Exception $e) {
                    $this->command->error("❌ خطأ في إنشاء المعلم الإضافي: " . $e->getMessage());
                }
            }
        }

        // إحصائيات نهائية
        $totalTeachers = Teacher::count();
        $this->command->info("🎉 تم إنشاء إجمالي {$createdCount} معلم جديد");
        $this->command->info("📊 إجمالي المعلمين في النظام: {$totalTeachers}");

        // عرض توزيع المواد
        $subjectStats = Teacher::selectRaw('subject, COUNT(*) as count')
            ->groupBy('subject')
            ->get();
        
        $this->command->info("📚 توزيع المعلمين حسب المواد:");
        foreach ($subjectStats as $stat) {
            $this->command->info("  {$stat->subject}: {$stat->count} معلم");
        }

        // إنشاء مستخدمين إضافيين في جدول users إذا لزم الأمر
        $this->command->info('🔄 فحص وإنشاء مستخدمين معلمين في جدول users...');
        
        $usersCount = User::where('role', 'teacher')->count();
        if ($usersCount < $totalTeachers) {
            $neededUsers = $totalTeachers - $usersCount;
            
            for ($i = 0; $i < $neededUsers; $i++) {
                try {
                    $teacher = Teacher::skip($i)->first();
                    if ($teacher) {
                        // إنشاء مستخدم للمعلم في جدول users
                        User::create([
                            'name' => $teacher->name,
                            'email' => str_replace('@institute.com', '_user@institute.com', $teacher->email),
                            'password' => Hash::make('teacher123'),
                            'role' => 'teacher',
                            'email_verified_at' => now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->command->warning("⚠️ تعذر إنشاء مستخدم للمعلم: " . $e->getMessage());
                }
            }
            
            $finalUsersCount = User::where('role', 'teacher')->count();
            $this->command->info("✅ إجمالي مستخدمي المعلم في جدول users: {$finalUsersCount}");
        }
    }
}