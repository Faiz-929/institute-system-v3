<x-app-layout>
    <x-slot name="header">
        إضافة مستخدم جديد
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-soft p-8">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <!-- معلومات المستخدم الأساسية -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">المعلومات الأساسية</h3>
                        
                        <!-- الاسم -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                الاسم الكامل *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="block w-full border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-300 @enderror"
                                   placeholder="أدخل الاسم الكامل">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني *
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="block w-full border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-300 @enderror"
                                   placeholder="example@domain.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- كلمة المرور -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                كلمة المرور *
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="block w-full border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-300 @enderror"
                                   placeholder="أدخل كلمة المرور (8 أحرف على الأقل)">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- تأكيد كلمة المرور -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                تأكيد كلمة المرور *
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="block w-full border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="أعد كتابة كلمة المرور">
                        </div>
                    </div>

                    <!-- الدور -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">الدور والصلاحيات</h3>
                        
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                دور المستخدم *
                            </label>
                            <select id="role" 
                                    name="role" 
                                    class="block w-full border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('role') border-red-300 @enderror">
                                <option value="">اختر الدور</option>
                                @foreach($roles as $roleKey => $roleName)
                                    <option value="{{ $roleKey }}" {{ old('role') == $roleKey ? 'selected' : '' }}>
                                        {{ $roleName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- وصف الأدوار -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">وصف الأدوار:</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li><strong>مدير النظام:</strong> صلاحية كاملة في إدارة جميع البيانات والمستخدمين</li>
                                <li><strong>معلم:</strong> يمكنه إدارة الدرجات والحضور وعرض بيانات الطلاب</li>
                                <li><strong>طالب:</strong> يمكنه عرض بياناته الشخصية ودرجاته فقط</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- أزرار التحكم -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        <x-icon-arrow-right />
                        <span>إلغاء</span>
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                        <x-icon-save />
                        <span>إضافة المستخدم</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- نصائح مهمة -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <x-icon-info class="w-5 h-5 text-blue-400 mt-0.5 ml-3" />
                <div class="text-sm">
                    <h3 class="text-blue-800 font-medium mb-2">نصائح مهمة:</h3>
                    <ul class="text-blue-700 space-y-1">
                        <li>• تأكد من استخدام بريد إلكتروني صحيح وقابل للوصول</li>
                        <li>• كلمة المرور يجب أن تكون قوية (8 أحرف على الأقل)</li>
                        <li>• اختر الدور المناسب حسب احتياجات المستخدم</li>
                        <li>• يمكن تعديل الدور لاحقاً من صفحة إدارة الصلاحيات</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
