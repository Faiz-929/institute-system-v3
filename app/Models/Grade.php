<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id','subject_id','teacher_id',
        'homework1','participation1','written_exam1',
        'homework2','participation2','written_exam2',
        'midterm1',
        'homework3','participation3','written_exam3',
        'homework4','participation4','written_exam4',
        'final_exam','total','semester','year'
    ];

    // العلاقات
    public function student()  { return $this->belongsTo(Student::class); }
    public function subject()  { return $this->belongsTo(Subject::class); }
    public function teacher()  { return $this->belongsTo(User::class, 'teacher_id'); }

    // دالة لحساب المجموع الكلي
    public function calculateTotal()
    {
        $this->total = ($this->homework1 ?? 0) + ($this->participation1 ?? 0) + ($this->written_exam1 ?? 0)
            + ($this->homework2 ?? 0) + ($this->participation2 ?? 0) + ($this->written_exam2 ?? 0)
            + ($this->midterm1 ?? 0)
            + ($this->homework3 ?? 0) + ($this->participation3 ?? 0) + ($this->written_exam3 ?? 0)
            + ($this->homework4 ?? 0) + ($this->participation4 ?? 0) + ($this->written_exam4 ?? 0)
            + ($this->final_exam ?? 0);

        // التأكد من أن المجموع لا يتجاوز 100
        $this->total = min($this->total, 100);

        return $this;
    }

    // دالة للتحقق من النجاح
    public function isPassed()
    {
        return $this->total >= 50;
    }

    // دالة للحصول على التقدير
    public function getGradeAttribute()
    {
        $total = $this->total;
        
        if ($total >= 95) return 'ممتاز';
        if ($total >= 90) return 'امتياز';
        if ($total >= 85) return 'جيد جداً';
        if ($total >= 75) return 'جيد';
        if ($total >= 65) return 'مقبول';
        if ($total >= 50) return 'ناجح';
        return 'راسب';
    }

    // دالة لحساب النسبة المئوية
    public function getPercentageAttribute()
    {
        return round($this->total, 2);
    }

    // Accessor لضمان أن المجموع يتم حسابه تلقائياً
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);
        
        // إذا تم تعديل أي من حقول الدرجات، إعادة حساب المجموع
        $gradeFields = [
            'homework1', 'participation1', 'written_exam1',
            'homework2', 'participation2', 'written_exam2',
            'midterm1',
            'homework3', 'participation3', 'written_exam3',
            'homework4', 'participation4', 'written_exam4',
            'final_exam'
        ];

        if (in_array($key, $gradeFields)) {
            $this->calculateTotal();
        }

        return $this;
    }

    // Boot method لحساب المجموع عند الإنشاء والتحديث
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($grade) {
            $grade->calculateTotal();
        });
    }
}
