<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // أسماء عربية للمعلمين
        $arabicFirstNames = [
            'أحمد', 'محمد', 'علي', 'حسن', 'خالد', 'سعد', 'عبدالله', 'يوسف', 'إبراهيم', 'محمود',
            'فاطمة', 'عائشة', 'خديجة', 'مريم', 'زينب', 'أسماء', 'رملة', 'صفية', 'رقية', 'ميمونة',
            'نورا', 'سارة', 'رانيا', 'لينا', 'منى', 'هند', 'أمل', 'رنا', 'ندى', 'ريم'
        ];

        $arabicLastNames = [
            'الرياض', 'الدمام', 'جدة', 'مكة', 'المدينة', 'الطائف', 'بريدة', 'تبوك', 'خميس', 'جازان',
            'نجران', 'الباحة', 'القصيم', 'حائل', 'عسير', 'الجوف', 'الحدود الشمالية',
            'الأدريسي', 'السالم', 'الحربي', 'المطيري', 'القحطاني', 'الشمري', 'العتيبي', 'الدوسري'
        ];

        // المؤهلات
        $qualifications = [
            'بكالوريوس رياضيات', 'ماجستير رياضيات', 'دكتوراه رياضيات',
            'بكالوريوس علوم', 'ماجستير علوم', 'دكتوراه علوم',
            'بكالوريوس لغة عربية', 'ماجستير لغة عربية', 'دكتوراه لغة عربية',
            'بكالوريوس لغة إنجليزية', 'ماجستير لغة إنجليزية', 'دكتوراه لغة إنجليزية',
            'بكالوريوس تاريخ', 'ماجستير تاريخ', 'دكتوراه تاريخ',
            'بكالوريوس فيزياء', 'ماجستير فيزياء', 'دكتوراه فيزياء',
            'بكالوريوس كيمياء', 'ماجستير كيمياء', 'دكتوراه كيمياء',
            'بكالوريوس حاسوب', 'ماجستير حاسوب', 'دكتوراه حاسوب',
            'بكالوريوس تعليم', 'ماجستير تعليم', 'دكتوراه تعليم'
        ];

        // المواد
        $subjects = [
            'رياضيات', 'علوم', 'لغة عربية', 'لغة إنجليزية', 'تاريخ', 'جغرافيا',
            'فيزياء', 'كيمياء', 'أحياء', 'حاسوب', 'تربية إسلامية', 'تربوية',
            'عربي أولى', 'رياضيات أولى', 'علوم أولى', 'فيزياء متقدمة', 'كيمياء متقدمة'
        ];

        // التخصصات
        $specializations = [
            'رياضيات متقدمة', 'إحصاء وتطبيقات', 'جبر ورياضيات متقطعة',
            'علوم طبيعية', 'أحياء وبيئة', 'كيمياء تحليلية',
            'أدب ونحو', 'بلاغة', 'لغة إنجليزية متقدمة',
            'تاريخ عربي', 'تاريخ إسلامي', 'جغرافيا طبيعية',
            'فيزياء نظرية', 'فيزياء تطبيقية', 'ميكانيكا',
            'كيمياء عضوية', 'كيمياء غير عضوية', 'كيمياء فيزيائية',
            'برمجة', 'شبكات', 'ذكاء اصطناعي', 'قواعد بيانات',
            'تربية إسلامية', 'عقيدة', 'حديث', 'فقه'
        ];

        return [
            'name' => $this->faker->randomElement($arabicFirstNames) . ' ' . 
                     $this->faker->randomElement($arabicLastNames),
            'email' => $this->faker->unique()->safeEmail(),
            'qualification' => $this->faker->randomElement($qualifications),
            'subject' => $this->faker->randomElement($subjects),
            'phone' => '05' . $this->faker->numerify('########'),
            'home_phone' => $this->faker->optional(0.7)->numerify('011########'),
            'address' => $this->faker->optional()->address(),
            'specialization' => $this->faker->randomElement($specializations),
            'experience_years' => $this->faker->numberBetween(1, 25),
            'salary' => $this->faker->numberBetween(10000, 25000),
            'status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'hire_date' => $this->faker->dateTimeBetween('-10 years', '-1 month'),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the user should have a active status.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user should have a inactive status.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the user should have a suspended status.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    /**
     * Indicate that the teacher is senior (more than 10 years experience).
     */
    public function senior(): static
    {
        return $this->state(fn (array $attributes) => [
            'experience_years' => $this->faker->numberBetween(11, 25),
        ]);
    }

    /**
     * Indicate that the teacher is junior (less than 5 years experience).
     */
    public function junior(): static
    {
        return $this->state(fn (array $attributes) => [
            'experience_years' => $this->faker->numberBetween(1, 5),
        ]);
    }

    /**
     * Indicate that the teacher teaches mathematics.
     */
    public function mathematics(): static
    {
        return $this->state(fn (array $attributes) => [
            'subject' => 'رياضيات',
            'specialization' => $this->faker->randomElement([
                'رياضيات متقدمة', 'إحصاء وتطبيقات', 'جبر ورياضيات متقطعة'
            ]),
        ]);
    }

    /**
     * Indicate that the teacher teaches science.
     */
    public function science(): static
    {
        return $this->state(fn (array $attributes) => [
            'subject' => $this->faker->randomElement(['علوم', 'فيزياء', 'كيمياء', 'أحياء']),
            'specialization' => $this->faker->randomElement([
                'علوم طبيعية', 'أحياء وبيئة', 'كيمياء تحليلية', 'فيزياء نظرية'
            ]),
        ]);
    }

    /**
     * Indicate that the teacher teaches languages.
     */
    public function language(): static
    {
        return $this->state(fn (array $attributes) => [
            'subject' => $this->faker->randomElement(['لغة عربية', 'لغة إنجليزية']),
            'specialization' => $this->faker->randomElement([
                'أدب ونحو', 'بلاغة', 'لغة إنجليزية متقدمة'
            ]),
        ]);
    }

    /**
     * Indicate that the teacher has a doctorate degree.
     */
    public function doctorate(): static
    {
        return $this->state(fn (array $attributes) => [
            'qualification' => $this->faker->randomElement([
                'دكتوراه رياضيات', 'دكتوراه علوم', 'دكتوراه لغة عربية',
                'دكتوراه لغة إنجليزية', 'دكتوراه تاريخ', 'دكتوراه فيزياء',
                'دكتوراه كيمياء', 'دكتوراه حاسوب'
            ]),
        ]);
    }

    /**
     * Indicate that the teacher has a master's degree.
     */
    public function masters(): static
    {
        return $this->state(fn (array $attributes) => [
            'qualification' => $this->faker->randomElement([
                'ماجستير رياضيات', 'ماجستير علوم', 'ماجستير لغة عربية',
                'ماجستير لغة إنجليزية', 'ماجستير تاريخ', 'ماجستير فيزياء',
                'ماجستير كيمياء', 'ماجستير حاسوب', 'ماجستير تعليم'
            ]),
        ]);
    }
}