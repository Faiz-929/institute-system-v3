#!/bin/bash

echo "======================================"
echo "   نظام إدارة المعهد - الإعداد السريع"
echo "======================================"
echo

# التحقق من وجود PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP غير مثبت. يرجى تثبيت PHP 8.2+ أولاً"
    exit 1
fi

# التحقق من وجود Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer غير مثبت. يرجى تثبيت Composer أولاً"
    exit 1
fi

echo "✅ PHP و Composer متوفران"

# تثبيت التبعيات
echo
echo "📦 تثبيت حزم PHP..."
composer install --no-dev --optimize-autoloader

# إنشاء ملف البيئة إذا لم يكن موجوداً
if [ ! -f .env ]; then
    echo
    echo "📄 إنشاء ملف البيئة..."
    cp .env.example .env
    echo "✅ تم إنشاء ملف .env"
else
    echo "✅ ملف .env موجود بالفعل"
fi

# توليد مفتاح التطبيق
echo
echo "🔑 توليد مفتاح التطبيق..."
php artisan key:generate
echo "✅ تم توليد المفتاح"

# إنشاء قاعدة البيانات (SQLite)
if [ ! -f database/database.sqlite ]; then
    echo
    echo "💾 إنشاء قاعدة البيانات SQLite..."
    touch database/database.sqlite
    echo "✅ تم إنشاء database.sqlite"
else
    echo "✅ قاعدة البيانات موجودة بالفعل"
fi

# تشغيل migrations و seeders
echo
echo "🗄️  تشغيل قاعدة البيانات..."
php artisan migrate:fresh --seed
echo "✅ تم تشغيل migrations و seeders"

# إنشاء رابط التخزين
echo
echo "🔗 إنشاء رابط التخزين..."
php artisan storage:link
echo "✅ تم إنشاء رابط التخزين"

# تحسين الأداء للإنتاج
echo
echo "⚡ تحسين الأداء للإنتاج..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo "✅ تم تحسين الأداء"

echo
echo "🎉 تم إعداد النظام بنجاح!"
echo
echo "======================================"
echo "   معلومات تسجيل الدخول"
echo "======================================"
echo "الأدمن:"
echo "  البريد: admin@institute.com"
echo "  كلمة المرور: admin123"
echo
echo "المعلم:"
echo "  البريد: ahmed@institute.com"
echo "  كلمة المرور: teacher123"
echo
echo "الطالب:"
echo "  البريد: student1@example.com"
echo "  كلمة المرور: student123"
echo
echo "======================================"
echo
echo "🚀 لتشغيل الخادم استخدم:"
echo "   php artisan serve"
echo
echo "🌐 ثم افتح المتصفح على:"
echo "   http://localhost:8000"
echo
echo "💻 للحصول على المساعدة:"
echo "   راجع ملف SETUP_GUIDE.md"
echo