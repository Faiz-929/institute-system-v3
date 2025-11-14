<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;


class StudentController extends Controller
{
    // عرض قائمة الطلاب مع البحث والفلترة
public function index(Request $request)
{
    // نبدأ بكويري فاضي على جدول الطلاب
    $query = Student::query();

    // 🔍 البحث باسم الطالب أو اسم ولي الأمر
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('parent_name', 'like', "%{$request->search}%");
        });
    }

    // 🎓 فلترة حسب المستوى
    if ($request->filled('level')) {
        $query->where('level', $request->level);
    }

    // 📘 فلترة حسب التخصص
    if ($request->filled('major')) {
        $query->where('major', $request->major);
    }

    // ⚡ نجلب النتائج بترتيب الأحدث مع تقسيم الصفحات
    $students = $query->latest()->paginate(10);

    // نرسل البيانات للعرض
    return view('students.index', compact('students'));
}

    

    // عرض فورم إضافة طالب جديد
    public function create()
    {
        return view('students.create');
    }

    // تخزين بيانات الطالب الجديد
    public function store(Request $request)
    {
        // ✅ تحقق من صحة البيانات القادمة من الفورم
        $data = $request->validate([
            'name' => 'required',
            'status' => 'nullable',
            'gender' => 'nullable',
            'photo' => 'nullable|image|max:2048',
            'address' => 'nullable',
            'home_phone' => 'nullable',
            'mobile_phone' => 'nullable',
            'level' => 'nullable',
            'major' => 'nullable',
            'notes' => 'nullable',

            // ✅ الحقول الجديدة (ولي الأمر)
            'parent_name' => 'nullable|string|max:255',
            'parent_mobile' => 'nullable|string|max:20',
            'parent_whatsapp' => 'nullable|string|max:20',
            'parent_home_phone' => 'nullable|string|max:20',
            'parent_job' => 'nullable|string|max:255',
        ]);

        // ✅ رفع الصورة إذا تم رفعها
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        // ✅ إنشاء الطالب
        Student::create($data);

        return redirect()->route('students.index')->with('success', 'تمت إضافة الطالب بنجاح');
    }

    // عرض فورم تعديل بيانات طالب
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    // تحديث بيانات الطالب
    public function update(Request $request, Student $student)
    {
        // ✅ تحقق من صحة البيانات
        $data = $request->validate([
            'name' => 'required',
            'status' => 'nullable',
            'gender' => 'nullable',
            'photo' => 'nullable|image|max:2048',
            'address' => 'nullable',
            'home_phone' => 'nullable',
            'mobile_phone' => 'nullable',
            'level' => 'nullable',
            'major' => 'nullable',
            'notes' => 'nullable',

            // ✅ الحقول الجديدة (ولي الأمر)
            'parent_name' => 'nullable|string|max:255',
            'parent_mobile' => 'nullable|string|max:20',
            'parent_whatsapp' => 'nullable|string|max:20',
            'parent_home_phone' => 'nullable|string|max:20',
            'parent_job' => 'nullable|string|max:255',
        ]);

        // ✅ تحديث الصورة إن وُجدت
        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        // ✅ تحديث بيانات الطالب
        $student->update($data);

        return redirect()->route('students.index')->with('success', 'تم تحديث بيانات الطالب');
    }
    
    // عرض بيانات الطالب و ولي الامر
        public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }


    // حذف الطالب
    public function destroy(Student $student)
    {
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();
        return redirect()->route('students.index')->with('success', 'تم حذف الطالب');
    }

    // عرض نسخة الطباعة للطلاب
    public function print(Request $request)
    {
        // نفس فلترة البحث الموجودة في index
        $query = Student::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('parent_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }

        $students = $query->latest()->get(); // كل النتائج بدون pagination للطباعة

        return view('students.print', compact('students'));
    }
     /**
     * تصدير نفس البيانات إلى ملف Excel
     */
    public function printExport(Request $request)
    {
        // نفس الاستعلام حتى يحافظ على الفلترة
        $query = Student::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('parent_name', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $students = $query->get();

        // اسم الملف حسب الفلترة
        $fileName = 'students';
        if ($request->filled('level')) $fileName .= "_{$request->level}";
        if ($request->filled('major')) $fileName .= "_{$request->major}";
        $fileName .= '.xlsx';

        // إنشاء ملف Excel
        $writer = SimpleExcelWriter::streamDownload($fileName);

        foreach ($students as $student) {
            $writer->addRow([
                'الاسم'          => $student->name,
                'الحالة'         => $student->status,
                'الجنس'          => $student->gender,
                'المستوى'        => $student->level,
                'التخصص'         => $student->major,
                'جوال ولي الأمر' => $student->parent_mobile,
                'رقم البيت'      => $student->parent_home_phone,
            ]);
        }

        return $writer->toBrowser();
    }

}
