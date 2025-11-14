<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'qualification',
        'subject',
        'phone',
        'home_phone',
        'address',
        'user_id',
    ];

    /**
     * العلاقة مع User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع الدرجات
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * العلاقة مع الحضور
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * العلاقة مع المواد
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
}
