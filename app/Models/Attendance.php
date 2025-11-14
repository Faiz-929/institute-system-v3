<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id', 
        'subject_name',
        'class_name',
        'session_date',
        'session_time',
        'status',
        'absence_reason',
        'late_minutes',
        'notes',
        'recorded_by'
    ];

    protected $casts = [
        'session_date' => 'date',
        'late_minutes' => 'integer'
    ];
    protected $table = 'attendance';

    // العلاقات
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    // دوال مساعدة
    public function isPresent()
    {
        return $this->status === 'حاضر';
    }

    public function isAbsent()
    {
        return $this->status === 'غائب';
    }

    public function isLate()
    {
        return $this->status === 'متأخر';
    }

    public function isExcused()
    {
        return $this->status === 'مُعفى';
    }

    // دالة للحصول على النسبة المئوية للحضور
    public static function getAttendancePercentage($studentId, $subject = null, $dateFrom = null, $dateTo = null)
    {
        $query = self::where('student_id', $studentId);

        if ($subject) {
            $query->where('subject_name', $subject);
        }

        if ($dateFrom && $dateTo) {
            $query->whereBetween('session_date', [$dateFrom, $dateTo]);
        }

        // Use clones so each count is executed from the same base query
        $totalSessions = (clone $query)->count();
        if ($totalSessions === 0) return 0;

        $presentSessions = (clone $query)->where('status', 'حاضر')->count();

        return round(($presentSessions / $totalSessions) * 100, 2);
    }

    // دالة للحصول على إحصائيات الحضور
    /**
     * Get attendance statistics. Optional date range supported.
     *
     * @param  int|null  $studentId
     * @param  int|null  $teacherId
     * @param  string|null  $subject
     * @param  string|null  $dateFrom
     * @param  string|null  $dateTo
     * @return array
     */
    public static function getAttendanceStats($studentId = null, $teacherId = null, $subject = null, $dateFrom = null, $dateTo = null)
    {
        $query = self::query();

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        if ($subject) {
            $query->where('subject_name', $subject);
        }

        if ($dateFrom && $dateTo) {
            $query->whereBetween('session_date', [$dateFrom, $dateTo]);
        }

        // Clone the query for each aggregate so filters don't accumulate
        $total = (clone $query)->count();
        $present = (clone $query)->where('status', 'حاضر')->count();
        $absent = (clone $query)->where('status', 'غائب')->count();
        $late = (clone $query)->where('status', 'متأخر')->count();
        $excused = (clone $query)->where('status', 'مُعفى')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0
        ];
    }

    // دالة للحصول على حضور الطالب في مادة معينة خلال فترة زمنية
    public static function getStudentSubjectAttendance($studentId, $subject, $fromDate = null, $toDate = null)
    {
        $query = self::where('student_id', $studentId)
                    ->where('subject_name', $subject);
        
        if ($fromDate && $toDate) {
            $query->whereBetween('session_date', [$fromDate, $toDate]);
        }
        
        return $query->orderBy('session_date', 'desc')
                    ->orderBy('session_time', 'desc')
                    ->get();
    }

    // دالة للحصول على حضور جميع الطلاب في حصة معينة
    public static function getClassAttendance($subject, $className, $date, $time)
    {
        return self::where('subject_name', $subject)
                  ->where('class_name', $className)
                  ->where('session_date', $date)
                  ->where('session_time', $time)
                  ->with(['student', 'teacher'])
                  ->get();
    }
}