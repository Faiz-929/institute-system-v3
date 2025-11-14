<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض فورم إضافة مستخدم جديد
     */
    public function create()
    {
        $roles = [
            'admin' => 'مدير النظام',
            'teacher' => 'معلم',
            'student' => 'طالب',
        ];
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,teacher,student',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * عرض بيانات المستخدم
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * عرض فورم تعديل المستخدم
     */
    public function edit(User $user)
    {
        $roles = [
            'admin' => 'مدير النظام',
            'teacher' => 'معلم',
            'student' => 'طالب',
        ];
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,teacher,student',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    /**
     * حذف المستخدم
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * عرض صفحة إدارة الصلاحيات
     */
    public function permissions()
    {
        $users = User::where('role', '!=', 'admin')->paginate(15);
        $roles = [
            'admin' => 'مدير النظام',
            'teacher' => 'معلم',
            'student' => 'طالب',
        ];
        
        return view('admin.permissions.index', compact('users', 'roles'));
    }

    /**
     * تحديث صلاحيات المستخدم
     */
    public function updatePermissions(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,teacher,student',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.permissions')
            ->with('success', 'تم تحديث صلاحيات المستخدم بنجاح');
    }
}
