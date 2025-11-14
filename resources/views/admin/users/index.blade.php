<x-app-layout>
    <x-slot name="header">
        إدارة المستخدمين
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.permissions') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors text-sm">
                <x-icon-lock />
                <span>إدارة الصلاحيات</span>
            </a>
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-success-600 hover:bg-success-700 text-white rounded-lg transition-colors text-sm">
                <x-icon-plus />
                <span>إضافة مستخدم</span>
            </a>
        </div>
    </x-slot>

    <!-- رسائل النجاح/الخطأ -->
    @if(session('success'))
        <div class="bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-danger-100 border border-danger-400 text-danger-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        @php
            $totalUsers = $users->total();
            $adminCount = \App\Models\User::where('role', 'admin')->count();
            $teacherCount = \App\Models\User::where('role', 'teacher')->count();
            $studentCount = \App\Models\User::where('role', 'student')->count();
        @endphp
        
        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <x-icon-user class="w-6 h-6 text-purple-600" />
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">إجمالي المستخدمين</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <x-icon-crown class="w-6 h-6 text-purple-600" />
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">المدراء</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $adminCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <x-icon-teacher class="w-6 h-6 text-blue-600" />
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">المعلمون</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $teacherCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <x-icon-student class="w-6 h-6 text-green-600" />
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">الطلاب</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $studentCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول المستخدمين -->
    <div class="bg-white rounded-xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            #
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            المستخدم
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            البريد الإلكتروني
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            الدور
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            تاريخ التسجيل
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            آخر تحديث
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary-600">
                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-semibold">
                                        {{ mb_substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="mr-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                    @if($user->role == 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role == 'teacher') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    @if($user->role == 'admin') مدير النظام
                                    @elseif($user->role == 'teacher') معلم
                                    @else طالب @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $user->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $user->updated_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors"
                                       title="عرض">
                                        <x-icon-eye />
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-2 bg-warning-500 hover:bg-warning-600 text-white rounded-lg transition-colors"
                                       title="تعديل">
                                        <x-icon-edit />
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-danger-600 hover:bg-danger-700 text-white rounded-lg transition-colors"
                                                    title="حذف">
                                                <x-icon-delete />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <x-icon-users class="w-16 h-16 text-gray-300" />
                                    <p class="text-gray-500 font-medium">لا يوجد مستخدمون حتى الآن</p>
                                    <a href="{{ route('admin.users.create') }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                                        <x-icon-plus />
                                        <span>إضافة مستخدم جديد</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</x-app-layout>
