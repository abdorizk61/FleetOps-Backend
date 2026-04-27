# 🚀 Fleet Management System - Laravel 12

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge)
![SQL Server](https://img.shields.io/badge/SQL%20Server-2019+-CC2927?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Production%20Ready-green?style=for-the-badge)

**معمارية Modular Monolith | Repository Pattern | Service Layer | Thin Controllers**

</div>

---

## 📖 المحتويات

- [نظرة عامة](#نظرة-عامة)
- [الميزات الرئيسية](#الميزات-الرئيسية)
- [المتطلبات](#المتطلبات)
- [البدء السريع](#البدء-السريع)
- [الهيكل](#الهيكل)
- [الموديولات](#الموديولات)
- [التوثيق](#التوثيق)
- [المساهمة](#المساهمة)

---

## 🎯 نظرة عامة

**Fleet Management System** هو نظام متكامل لإدارة الأسطول مع بنية معمارية متقدمة.

المشروع يستخدم **Modular Monolith Architecture** مع **Repository Pattern** و **Service Layer** لضمان:
- ✅ فصل الأدوار والمسؤوليات
- ✅ سهولة الصيانة والتطوير
- ✅ قابلية التوسع
- ✅ أداء عالي

---

## ✨ الميزات الرئيسية

### 🏗️ معمارية Modular Monolith
```
8 موديولات مستقلة:
├── IAM (إدارة الهويات)
├── MaintenanceTracker (تتبع الصيانة) ✅ كمثال شامل
├── DispatchAndRouting (التوجيه والتوزيع)
├── DriverOps (عمليات السائقين)
├── FleetMonitoring (مراقبة الأسطول)
├── CustomerPortalAPI (واجهة العملاء)
├── NotificationEngine (نظام الإشعارات)
└── SystemAudit (التدقيق والسجلات)
```

### 📦 Repository Pattern
```php
BaseRepository (20+ دالة CRUD)
  ├── findById()
  ├── getAll()
  ├── create()
  ├── update()
  ├── delete()
  ├── search()
  ├── paginate()
  └── ... و12 دالة أخرى!
```

### 🎯 Service Layer
```
Business Logic منفصل تماماً
  - Validation
  - Transaction Management
  - Error Handling
  - Complex Operations
```

### 🎛️ Thin Controllers
```
Controller = Request → Service → Response
  - لا Business Logic
  - لا Database Access
  - فقط التوجيه والـ Response
```

### 🔐 Form Requests
```
Validation موحدة وآمنة
  - Rules متقدمة
  - Custom Messages
  - Authorization
```

### 🚀 Singleton Repositories
```
Performance محسّن:
  - Instance واحدة فقط
  - استهلاك ذاكرة أقل
  - Response أسرع
```

---

## 📋 المتطلبات

```
PHP >= 8.2
Laravel >= 12.x
SQL Server >= 2019
Composer >= 2.0
Node.js >= 18 (اختياري)
```

---

## 🚀 البدء السريع

### 1️⃣ الاستنساخ والتثبيت
```bash
git clone <repository-url>
cd FleetOps-Backend
composer install
```

### 2️⃣ الإعدادات
```bash
cp .env.example .env
php artisan key:generate
```

### 3️⃣ قاعدة البيانات
```bash
# إنشاء قاعدة البيانات في SQL Server
# ثم تحديث .env
DB_CONNECTION=sqlsrv
DB_DATABASE=FleetManagementDB

# تشغيل Migrations
php artisan migrate
```

### 4️⃣ البدء
```bash
php artisan serve
# متاح على: http://localhost:8000/api
```

---

## 📁 الهيكل

```
FleetOps-Backend/
├── app/
│   ├── Modules/
│   │   ├── MaintenanceTracker/        ✅ كمثال شامل
│   │   │   ├── Models/
│   │   │   ├── Repositories/
│   │   │   ├── Services/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── routes.php
│   │   ├── IAM/
│   │   ├── DispatchAndRouting/
│   │   ├── DriverOps/
│   │   ├── FleetMonitoring/
│   │   ├── CustomerPortalAPI/
│   │   ├── NotificationEngine/
│   │   ├── SystemAudit/
│   │   └── Shared/
│   │       └── Repositories/
│   │           └── BaseRepository.php
│   └── Providers/
│       └── ModuleServiceProvider.php
├── database/
│   └── migrations/
├── routes/
│   └── api.php
├── ARCHITECTURE.md                    📖 المعمارية
├── API_DOCUMENTATION.md               📖 توثيق API
├── GETTING_STARTED.md                 📖 البدء السريع
├── DATABASE_SCHEMA.md                 📖 قاعدة البيانات
└── PROJECT_SUMMARY.md                 📖 الملخص
```

---

## 🎯 الموديولات

### MaintenanceTracker ✅ (كمثال شامل)

**الميزات**:
- 14 API Endpoint
- صيانات روتينية ومتقدمة
- تتبع تاريخ الصيانات
- الإحصائيات والتقارير
- جدولة الصيانات التالية

**Endpoints**:
```
GET    /api/maintenance              جميع الصيانات
POST   /api/maintenance              إنشاء جديدة
GET    /api/maintenance/{id}         الحصول على واحدة
PATCH  /api/maintenance/{id}         تحديث
DELETE /api/maintenance/{id}         حذف
GET    /api/maintenance/overdue      الصيانات المتأخرة
GET    /api/maintenance/upcoming     الصيانات القادمة
POST   /api/maintenance/search       البحث المتقدم
PATCH  /api/maintenance/{id}/status  تحديث الحالة
GET    /api/maintenance/stats        الإحصائيات
... و 4 endpoints أخرى
```

---

## 📚 التوثيق

| الملف | الوصف |
|------|--------|
| [ARCHITECTURE.md](./ARCHITECTURE.md) | **شرح المعمارية بالتفصيل** - كل شيء عن الـ Repositories و Services و Controllers |
| [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) | **توثيق API الكامل** - جميع الـ Endpoints مع أمثلة |
| [GETTING_STARTED.md](./GETTING_STARTED.md) | **دليل البدء السريع** - التثبيت والإعداد والأوامر |
| [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md) | **قاعدة البيانات** - جميع الجداول والعلاقات |
| [PROJECT_SUMMARY.md](./PROJECT_SUMMARY.md) | **ملخص المشروع** - الإحصائيات والنتائج |

---

## 💻 أمثلة الاستخدام

### الحصول على جميع الصيانات
```bash
curl -X GET http://localhost:8000/api/maintenance \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### إنشاء صيانة جديدة
```bash
curl -X POST http://localhost:8000/api/maintenance \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "vehicle_id": 1,
    "maintenance_type": "oil_change",
    "scheduled_date": "2025-02-01",
    "created_by": 2
  }'
```

### البحث المتقدم
```bash
curl -X POST http://localhost:8000/api/maintenance/search \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "status": "pending",
    "vehicle_id": 1,
    "start_date": "2025-01-01",
    "end_date": "2025-12-31"
  }'
```

---

## 🔄 تدفق الطلب

```
Client Request
    ↓
Route (routes.php)
    ↓
Controller (Thin)
    ↓
FormRequest (Validation)
    ↓
Service (Business Logic)
    ↓
Repository (Data Access)
    ↓
Model (Eloquent ORM)
    ↓
Database (SQL Server)
    ↓
JSON Response
```

---

## 🛠️ أوامر مفيدة

```bash
# Migrations
php artisan migrate                    # تشغيل
php artisan migrate:rollback          # التراجع
php artisan migrate:fresh --seed      # إعادة تشغيل

# Testing
php artisan test                       # جميع الاختبارات
php artisan test --testsuite=Feature  # Feature tests فقط

# Maintenance
php artisan cache:clear               # حذف الـ Cache
php artisan optimize:clear            # تنظيف
php artisan health                     # فحص الصحة

# Development
php artisan serve                      # خادم التطوير
php artisan tinker                     # Interactive Shell
```

---

## 📊 الإحصائيات

| المقياس | القيمة |
|--------|--------|
| Modules | 8 موديولات |
| Example Module | MaintenanceTracker |
| API Endpoints | 14+ endpoint |
| Repository Methods | 20+ دالة |
| Service Methods | 12+ دالة |
| Lines of Code | 3500+ سطر |
| Documentation | 5 ملفات شاملة |
| Test Coverage | في التطوير |

---

## 🔒 الأمان

- ✅ Form Requests للتحقق
- ✅ Exception Handling موحد
- ✅ SQL Injection Protection (Eloquent)
- ✅ CORS Configuration
- ✅ Rate Limiting (اختياري)
- ✅ JWT Authentication (اختياري)

---

## 📈 الأداء

- ✅ Singleton Repositories
- ✅ Eager Loading (with)
- ✅ Database Indexes
- ✅ Query Optimization
- ✅ Caching Support
- ✅ Pagination

---

## 🤝 المساهمة

### قواعس الكود
1. اتبع PSR-12
2. أضف Type Hints
3. اكتب Comments
4. استخدم العربية للـ Messages

### الخطوات
```bash
1. Fork المستودع
2. أنشئ Feature Branch
3. Commit التغييرات
4. Push للـ Branch
5. Open Pull Request
```

---

## 📞 الدعم

**Team Leader**: Khalid  
**Email**: khalid@fleetops.com

للأسئلة:
1. اقرأ التوثيق أولاً
2. افحص الأمثلة
3. تواصل مع الفريق

---

## 📝 الترخيص

هذا المشروع خاص بـ Fleet Management Company.

---

## 🎉 شكراً

شكراً لك على استخدام Fleet Management System!

---

<div align="center">

**[البدء السريع](./GETTING_STARTED.md)** • 
**[المعمارية](./ARCHITECTURE.md)** • 
**[API](./API_DOCUMENTATION.md)** • 
**[قاعدة البيانات](./DATABASE_SCHEMA.md)**

---

تم الإنشاء بـ ❤️ من قبل Team Leader (Khalid)

2025 © جميع الحقوق محفوظة

</div>
