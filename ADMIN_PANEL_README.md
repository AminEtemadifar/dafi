# پنل ادمین دافی

این پنل ادمین با استفاده از قالب Convex Bootstrap Admin Dashboard Template طراحی شده است.

## ویژگی ها

### 🔐 احراز هویت
- صفحه ورود ادمین
- سیستم احراز هویت با ایمیل و رمز عبور
- خروج امن از سیستم

### 📊 داشبورد
- آمار پرداخت های موفق
- مجموع مبلغ پرداخت ها
- تعداد درخواست های انجام شده
- تعداد درخواست های در انتظار
- نمودارهای آماری
- لیست آخرین درخواست های در انتظار
- لیست نام ها با pagination

### 👥 مدیریت نام ها
- لیست نام ها با قابلیت جستجو و مرتب سازی
- افزودن نام جدید با آپلود فایل موسیقی
- ویرایش نام ها
- مشاهده جزئیات نام
- حذف نام ها
- آپلودر زیبا و کاربر پسند برای فایل های موسیقی
- جستجوی خودکار درخواست های مطابق

### 📝 مدیریت درخواست ها
- لیست درخواست ها با فیلتر بر اساس وضعیت
- جستجو بر اساس نام یا شماره موبایل
- مرتب سازی بر اساس تاریخ، نام و موبایل
- تغییر وضعیت درخواست ها
- مشاهده جزئیات درخواست

### 💳 مدیریت پرداخت ها
- لیست پرداخت ها با فیلتر بر اساس وضعیت و درگاه
- جستجو بر اساس نام یا شماره موبایل
- مرتب سازی بر اساس تاریخ و مبلغ
- تغییر وضعیت پرداخت ها
- مشاهده جزئیات پرداخت

## نصب و راه اندازی

### 1. اجرای Migration ها
```bash
php artisan migrate
```

### 2. اجرای Seeder ها
```bash
php artisan db:seed
```

### 3. ایجاد لینک Symbolic برای Storage
```bash
php artisan storage:link
```

### 4. اطلاعات ورود ادمین
- **ایمیل:** admin@dafi.com
- **رمز عبور:** 123456

## مسیرهای API

### احراز هویت
- `GET /admin/login` - صفحه ورود
- `POST /admin/login` - ورود به سیستم
- `POST /admin/logout` - خروج از سیستم

### داشبورد
- `GET /admin` - صفحه اصلی داشبورد

### نام ها
- `GET /admin/names` - لیست نام ها
- `GET /admin/names/create` - فرم افزودن نام
- `POST /admin/names` - ذخیره نام جدید
- `GET /admin/names/{id}` - مشاهده نام
- `GET /admin/names/{id}/edit` - فرم ویرایش نام
- `PUT /admin/names/{id}` - بروزرسانی نام
- `DELETE /admin/names/{id}` - حذف نام
- `GET /admin/submits/by-name` - جستجوی درخواست های مطابق

### درخواست ها
- `GET /admin/submits` - لیست درخواست ها
- `GET /admin/submits/{id}` - مشاهده درخواست
- `PATCH /admin/submits/{id}/status` - تغییر وضعیت درخواست

### پرداخت ها
- `GET /admin/transactions` - لیست پرداخت ها
- `GET /admin/transactions/{id}` - مشاهده پرداخت
- `PATCH /admin/transactions/{id}/status` - تغییر وضعیت پرداخت

## فایل های مهم

### Controllers
- `app/Http/Controllers/Admin/AdminDashboardController.php`
- `app/Http/Controllers/Admin/AdminNamesController.php`
- `app/Http/Controllers/Admin/AdminSubmitsController.php`
- `app/Http/Controllers/Admin/AdminTransactionsController.php`
- `app/Http/Controllers/AdminAuthController.php`

### Views
- `resources/views/admin/layouts/app.blade.php` - قالب اصلی
- `resources/views/admin/auth/login.blade.php` - صفحه ورود
- `resources/views/admin/dashboard.blade.php` - داشبورد
- `resources/views/admin/names/` - صفحات مدیریت نام ها
- `resources/views/admin/submits/` - صفحات مدیریت درخواست ها
- `resources/views/admin/transactions/` - صفحات مدیریت پرداخت ها

### Assets
- `public/admin-assets/` - فایل های CSS، JS و تصاویر قالب

## نکات مهم

1. **فایل های موسیقی:** حداکثر حجم 10 مگابایت، فرمت های MP3، WAV و OGG
2. **احراز هویت:** از guard `admin` استفاده می شود
3. **زبان:** تمام صفحات به فارسی طراحی شده اند
4. **Responsive:** قالب کاملاً responsive است
5. **DataTables:** از DataTables برای نمایش جداول استفاده می شود

## مشکلات احتمالی و راه حل

### خطای 404 در مسیرها
- مطمئن شوید که route cache پاک شده: `php artisan route:clear`

### خطای احراز هویت
- مطمئن شوید که guard `admin` در `config/auth.php` تعریف شده

### عدم نمایش فایل های موسیقی
- مطمئن شوید که `php artisan storage:link` اجرا شده

### خطاهای JavaScript
- مطمئن شوید که فایل های CSS و JS در `public/admin-assets/` کپی شده اند
