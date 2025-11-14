<x-app-layout>
    <x-slot name="header">
        تفاصيل المستخدم: {{ $user->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- بطاقة معلومات المستخدم -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">معلومات المستخدم</h2>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                            @if($user->role == 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role == 'teacher') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif">
                            @if($user->role == 'admin') مدير النظام
                            @elseif($user->role == 'teacher') معلم
                            @else طالب @endif
                        </span>
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-success-100 text-success-800">
                            نشط
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- المعلومات الأساسية -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wider">المعلومات الأساسية</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">الاسم الكامل</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">البريد الإلكتروني</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">المعرف (ID)</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->id }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات النظام -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wider">معلومات النظام</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">تاريخ التسجيل</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('Y-m-d H:i:s') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">آخر تحديث</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('Y-m-d H:i:s') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">حالة البريد الإلكتروني</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                        تم التحقق
                                    </span>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإحصائيات المرتبطة -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">الإحصائيات المرتبطة</h2>
            </div>

            <div class="p-6">
                @if($user->role == 'teacher')
                    <!-- إحصائيات المعلم -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="p-3 bg-blue-100 rounded-lg w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                                <x-icon-chart class="w-8 h-8 text-blue-600" />
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->grades()->count() }}</p>
                            <p class="text-sm text-gray-500">درجات مُدخلة</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="p-3 bg-green-100 rounded-lg w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                                <x-icon-calendar class="w-8 h-8 text-green-600" />
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->attendance()->count() }}</p>
                            <p class="text-sm text-gray-500">حضور مُسجل</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="p-3 bg-purple-100 rounded-lg w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                                <x-icon-users class="w-8 h-8 text-purple-600" />
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->subjects()->count() }}</p>
                            <p class="text-sm text-gray-500">مواد مُدرَّسة</p>
                        </div>
                    </div>
                @elseif($user->role == 'student')
                    <!-- إحصائيات الطالب -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="p-3 bg-green-100 rounded-lg w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                                <x-icon-chart class="w-8 h-8 text-green-600" />
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->grades()->count() }}</p>
                            <p class="text-sm text-gray-500">درجات مُسجلة</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="p-3 bg-blue-100 rounded-lg w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                                <x-icon-calendar class="w-8 h-8 text-blue-600" />
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->attendance()->count() ?? 0 }}</p>
                            <p class="text-sm text-gray-500">سجلات حضور</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="p-3 bg-yellow-100 rounded-lg w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                                <x-icon-trophy class="w-8 h-8 text-yellow-600" />
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ round($user->grades()->avg('total') ?? 0) }}</p>
                            <p class="text-sm text-gray-500">المعدل العام</p>
                        </div>
                    </div>
                @else
                    <!-- إحصائيات المدير -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="p-3 bg-purple-100 rounded-lg w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                                <x-icon-users class="w-8 h-8 text-purple-600" />
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                            <p class="text-sm text-gray-500">إجمالي المستخدمين</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="p-3 bg-blue-100 rounded-lg w-16 h-16 mx-auto mb-2 flex items-center justify-center">
                                <x-icon-crown class="w-8 h-8 text-blue-600" />
                            </div>
                            <p class="text-2xl font-bold text-gray-900">مدير نظام</p>
                            <p class="text-sm text-gray-500">صلاحية كاملة</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- أزرار التحكم -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        <x-icon-arrow-right />
                        <span>العودة للقائمة</span>
                    </a>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-warning-500 hover:bg-warning-600 text-white font-medium rounded-lg transition-colors">
                        <x-icon-edit />
                        <span>تعديل</span>
                    </a>
                    
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" 
                              method="POST" 
                              class="inline-block"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-danger-600 hover:bg-danger-700 text-white font-medium rounded-lg transition-colors">
                                <x-icon-delete />
                                <span>حذف</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
