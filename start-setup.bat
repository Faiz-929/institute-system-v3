@echo off
chcp 65001 >nul
echo ======================================
echo    نظام إدارة المعهد - الإعداد السريع
echo ======================================
echo.

REM التحقق من وجود PHP
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP غير مثبت. يرجى تثبيت PHP 8.2+ أولاً
    pause
    exit /b 1
)

REM التحقق من وجود Composer
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Composer غير مثبت. يرجى تثبيت Composer أولاً
    pause
    exit /b 1
)

echo ✅ PHP و Composer متوفران

REM تثبيت التبعيات
echo.
echo 📦 تثبيت حزم PHP...
composer install --no-dev --optimize-autoloader

REM إنشاء ملف البيئة إذا لم يكن موجوداً
if not exist .env (
    echo.
    echo 📄 إنشاء ملف البيئة...
    copy .env.example .env
    echo ✅ تم إنشاء ملف .env
) else (
    echo ✅ ملف .env موجود بالفعل
)

REM توليد مفتاح التطبيق
echo.
echo 🔑 توليد مفتاح التطبيق...
php artisan key:generate
echo ✅ تم توليد المفتاح

REM إنشاء قاعدة البيانات (SQLite)
if not exist database\database.sqlite (
    echo.
    echo 💾 إنشاء قاعدة البيانات SQLite...
    type nul > database\database.sqlite
    echo ✅ تم إنشاء database.sqlite
) else (
    echo ✅ قاعدة البيانات موجودة بالفعل
)

REM تشغيل migrations و seeders
echo.
echo 🗄️  تشغيل قاعدة البيانات...
php artisan migrate:fresh --seed
echo ✅ تم تشغيل migrations و seeders

REM إنشاء رابط التخزين
echo.
echo 🔗 إنشاء رابط التخزين...
php artisan storage:link
echo ✅ تم إنشاء رابط التخزين

REM تحسين الأداء للإنتاج
echo.
echo ⚡ تحسين الأداء للإنتاج...
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo ✅ تم تحسين الأداء

echo.
echo 🎉 تم إعداد النظام بنجاح!
echo.
echo ======================================
echo    معلومات تسجيل الدخول
echo ======================================
echo الأدمن:
echo   البريد: admin@institute.com
echo   كلمة المرور: admin123
echo.
echo المعلم:
echo   البريد: ahmed@institute.com
echo   كلمة المرور: teacher123
echo.
echo الطالب:
echo   البريد: student1@example.com
echo   كلمة المرور: student123
echo.
echo ======================================
echo.
echo 🚀 لتشغيل الخادم استخدم:
echo    php artisan serve
echo.
echo 🌐 ثم افتح المتصفح على:
echo    http://localhost:8000
echo.
echo 💻 للحصول على المساعدة:
echo    راجع ملف SETUP_GUIDE.md
echo.
pause