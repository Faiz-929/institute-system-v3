<x-app-layout>
    <x-slot name="header">
        إدارة الصلاحيات والأدوار
    </x-slot>

    <!-- رسالة النجاح/الخطأ -->
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

    <!-- شريط البحث -->
    <div class="bg-white rounded-xl shadow-soft p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">إدارة أدوار المستخدمين</h2>
            <div class="text-sm text-gray-600">
                <span class="bg-primary-100 text-primary-800 px-3 py-1 rounded-full">
                    إجمالي المستخدمين: {{ $users->total() }}
                </span>
            </div>
        </div>
    </div>

    <!-- جدول إدارة الصلاحيات -->
    <div class="bg-white rounded-xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            #
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            الاسم
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            البريد الإلكتروني
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            الدور الحالي
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            تاريخ التسجيل
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
                                    {{ $roles[$user->role] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $user->created_at->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-2">
                                    <!-- نموذج تعديل الدور -->
                                    <form action="{{ route('admin.permissions.update', $user) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <select name="role" class="text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" onchange="this.form.submit()">
                                            @foreach($roles as $roleKey => $roleName)
                                                @if($roleKey !== 'admin') {{-- منع تغيير دور الإدارة --}}
                                                    <option value="{{ $roleKey }}" 
                                                        {{ $user->role == $roleKey ? 'selected' : '' }}>
                                                        {{ $roleName }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="role" value="{{ $user->role }}">
                                    </form>
                                    
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors"
                                       title="عرض">
                                        <x-icon-eye />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <x-icon-users class="w-16 h-16 text-gray-300" />
                                    <p class="text-gray-500 font-medium">لا يوجد مستخدمون لإدارة صلاحياتهم</p>
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

    <!-- ملاحظات مهمة -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <x-icon-info class="w-5 h-5 text-blue-400 mt-0.5 ml-3" />
            <div class="text-sm">
                <h3 class="text-blue-800 font-medium mb-2">ملاحظات مهمة:</h3>
                <ul class="text-blue-700 space-y-1">
                    <li>• لا يمكن تغيير دور المدير العام</li>
                    <li>• عند تغيير دور المستخدم، سيتم تطبيق الصلاحيات الجديدة فوراً</li>
                    <li>• تأكد من اختيار الدور المناسب لكل مستخدم</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
