# معمارية Fleet Management System - Modular Monolith

## نظرة عامة على المشروع

هذا المشروع يستخدم معمارية **Modular Monolith** مع **Laravel 12** و **SQL Server**.

المشروع مقسم إلى **8 موديولات مستقلة**:
1. **IAM** - إدارة الهويات والمستخدمين والأدوار والصلاحيات
2. **MaintenanceTracker** - تتبع صيانة المركبات (✅ كمثال شامل)
3. **DispatchAndRouting** - تتزيع المهام والتوجيه الذكي
4. **DriverOps** - عمليات السائقين والمراقبة
5. **FleetMonitoring** - مراقبة الأسطول والمركبات
6. **CustomerPortalAPI** - واجهة العملاء
7. **NotificationEngine** - نظام الإشعارات
8. **SystemAudit** - تدقيق النظام والسجلات

---

## معمارية الموديول الواحد

كل موديول يتبع البنية التالية:

```
app/Modules/MaintenanceTracker/
├── Models/
│   └── MaintenanceTrackerModel.php         # Eloquent Model
├── Repositories/
│   └── MaintenanceTrackerRepository.php    # Data Access Layer
├── Services/
│   └── MaintenanceTrackerService.php       # Business Logic Layer
├── Controllers/
│   └── MaintenanceTrackerController.php    # API Handler
├── Requests/
│   └── MaintenanceTrackerRequest.php       # Form Validation
└── routes.php                               # Module Routes
```

---

## الطبقات والمسؤوليات

### 1️⃣ **Model Layer** (Eloquent Models)
**المسؤولية**: تمثيل البيانات وتعريف الجداول والعلاقات

```php
// المميزات:
- تعريف الحقول والعلاقات (relationships)
- Scopes للاستعلامات الشائعة
- Casts للتحويل التلقائي
- SoftDeletes للحذف المنطقي
```

**مثال**:
```php
class MaintenanceTrackerModel extends Model
{
    use SoftDeletes; // حذف منطقي بدلاً من الحذف النهائي
    
    protected $table = 'maintenance_trackers';
    protected $fillable = ['vehicle_id', 'scheduled_date', 'status'];
    
    // Scopes
    public function scopePending($query) {
        return $query->where('status', 'pending');
    }
    
    // العلاقات
    public function vehicle() {
        return $this->belongsTo(VehicleModel::class);
    }
}
```

---

### 2️⃣ **Repository Layer** (Data Access)
**المسؤولية**: الوصول إلى البيانات فقط (Database Abstraction)

**الفوائد**:
- فصل منطق الوصول للبيانات عن منطق العمل
- إمكانية تغيير مصدر البيانات (DB, API, Cache) بسهولة
- اختبارات أسهل (Mocking)
- تجنب تكرار الأكواد (DRY)

**المتطلبات**:
✅ كل Repository يرث من `BaseRepository`
✅ يحتوي على جميع عمليات الوصول للبيانات
✅ لا يحتوي على Business Logic

**مثال الاستخدام**:
```php
class MaintenanceTrackerRepository extends BaseRepository
{
    // الأساسيات من BaseRepository:
    // - findById($id)
    // - getAll()
    // - create($data)
    // - update($id, $data)
    // - delete($id)
    
    // متخصصة:
    public function getByVehicleId(int $vehicleId)
    {
        return $this->model->where('vehicle_id', $vehicleId)->get();
    }
    
    public function getOverdue()
    {
        return $this->model->overdue()->get();
    }
}
```

---

### 3️⃣ **Service Layer** (Business Logic)
**المسؤولية**: تنفيذ كل Business Logic التطبيق

**القواعد**:
✅ تفويض جميع عمليات الوصول للبيانات للـ Repository
✅ التحقق من الصحة والشروط
✅ معالجة الأخطاء
✅ إدارة المعاملات (Transactions)
✅ لا يعرف أي شيء عن HTTP

**مثال**:
```php
class MaintenanceTrackerService
{
    public function __construct(MaintenanceTrackerRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function createMaintenance(array $data)
    {
        // التحقق من البيانات
        if (empty($data['vehicle_id'])) {
            throw new Exception('معرف المركبة مطلوب');
        }
        
        // تفويض الإنشاء للـ Repository
        return $this->repository->create($data);
    }
    
    public function completeMaintenance(int $maintenanceId, array $data)
    {
        // معالجة Business Logic
        $data['status'] = 'completed';
        $data['completion_date'] = now();
        
        return $this->repository->update($maintenanceId, $data);
    }
}
```

---

### 4️⃣ **Controller Layer** (Thin Controllers)
**المسؤولية**: استقبال الطلبات فقط والرد على الـ Client

**القاعدة الذهبية**: Controllers يجب أن تكون "نحيفة" جداً

✅ استقبال الطلب
✅ استدعاء الـ Service
✅ إرجاع الرد

❌ لا Business Logic
❌ لا قواعد Validation
❌ لا وصول مباشر للـ Database

**مثال**:
```php
class MaintenanceTrackerController extends Controller
{
    public function __construct(MaintenanceTrackerService $service)
    {
        $this->service = $service;
    }
    
    // Controller يسأل Service ويعيد الرد فقط
    public function index(Request $request)
    {
        try {
            $maintenances = $this->service->getAllMaintenances(
                $request->query('per_page', 15)
            );
            
            return response()->json([
                'success' => true,
                'data' => $maintenances
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
```

---

### 5️⃣ **Form Request Layer** (Validation)
**المسؤولية**: التحقق من صحة البيانات الواردة

**المميزات**:
✅ Validation Rules
✅ Custom Messages
✅ Authorization Checks
✅ Data Transformation

**مثال**:
```php
class MaintenanceTrackerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'scheduled_date' => 'required|date|after:today',
            'cost' => 'nullable|numeric|min:0',
        ];
    }
    
    public function messages()
    {
        return [
            'vehicle_id.required' => 'معرف المركبة مطلوب',
            'scheduled_date.after' => 'يجب أن يكون التاريخ بعد اليوم',
        ];
    }
}
```

---

## نمط Singleton في Service Provider

### لماذا Singleton؟

```php
// بدون Singleton (instance جديدة كل مرة) ❌
$repo = new MaintenanceTrackerRepository();
$repo2 = new MaintenanceTrackerRepository();
// repo !== repo2 ❌

// مع Singleton (instance واحدة فقط) ✅
$this->app->singleton(MaintenanceTrackerRepository::class, function() {
    return new MaintenanceTrackerRepository(new MaintenanceTrackerModel());
});

// في أي مكان:
$repo = app(MaintenanceTrackerRepository::class);
$repo2 = app(MaintenanceTrackerRepository::class);
// repo === repo2 ✅
```

**الفوائد**:
- الذاكرة أقل استهلاكاً
- الأداء أسرع
- State مشترك إذا احتاجه

### تسجيل في Service Provider

```php
public function register()
{
    // Singleton = instance واحدة فقط طوال حياة التطبيق
    $this->app->singleton(
        MaintenanceTrackerRepository::class,
        function ($app) {
            return new MaintenanceTrackerRepository(
                new MaintenanceTrackerModel()
            );
        }
    );
    
    // Bind Service مع Repository المسجل
    $this->app->singleton(
        MaintenanceTrackerService::class,
        function ($app) {
            return new MaintenanceTrackerService(
                $app->make(MaintenanceTrackerRepository::class)
            );
        }
    );
}
```

---

## تحميل الـ Routes تلقائياً

### قبل (اليدوي) ❌
```php
// routes/api.php
include app_path('Modules/MaintenanceTracker/routes.php');
include app_path('Modules/IAM/routes.php');
include app_path('Modules/DispatchAndRouting/routes.php');
// ... الخ (تكرار ممل)
```

### بعد (الآلي) ✅
```php
// app/Providers/ModuleServiceProvider.php
public function boot()
{
    $this->loadRoutes();
}

protected function loadRoutes()
{
    foreach ($this->modules as $module) {
        $routesPath = base_path("app/Modules/{$module}/routes.php");
        if (file_exists($routesPath)) {
            Route::group(['prefix' => 'api'], function () use ($routesPath) {
                require $routesPath;
            });
        }
    }
}
```

كل موديول يحتوي على ملف `routes.php`:
```php
// app/Modules/MaintenanceTracker/routes.php
Route::prefix('maintenance')->group(function () {
    Route::get('/', [MaintenanceTrackerController::class, 'index']);
    Route::post('/', [MaintenanceTrackerController::class, 'store']);
    Route::get('/{id}', [MaintenanceTrackerController::class, 'show']);
    // ...
});
```

---

## مثال عملي: إنشاء صيانة جديدة

### 1️⃣ **الطلب يأتي للـ Controller**
```
POST /api/maintenance
{
    "vehicle_id": 1,
    "maintenance_type": "oil_change",
    "scheduled_date": "2025-05-01"
}
```

### 2️⃣ **Validation (FormRequest)**
```php
class MaintenanceTrackerRequest extends FormRequest
{
    // التحقق من البيانات هنا
    public function rules() { ... }
}
```

### 3️⃣ **Controller يعيد التوجيه للـ Service**
```php
public function store(MaintenanceTrackerRequest $request)
{
    $maintenance = $this->service->createMaintenance(
        $request->validated() // بيانات محققة
    );
    return response()->json(['data' => $maintenance]);
}
```

### 4️⃣ **Service يتولى Business Logic**
```php
public function createMaintenance(array $data)
{
    // تحقق من المركبة موجودة
    if (!Vehicle::find($data['vehicle_id'])) {
        throw new Exception('المركبة غير موجودة');
    }
    
    // تفويض الإنشاء للـ Repository
    return $this->repository->create($data);
}
```

### 5️⃣ **Repository يتولى الوصول للبيانات**
```php
public function create(array $data): Model
{
    return $this->model->create($data); // إنشاء مباشر من Model
}
```

### 6️⃣ **النتيجة**
```json
{
    "success": true,
    "data": {
        "maintenance_id": 1,
        "vehicle_id": 1,
        "status": "pending",
        "created_at": "2025-01-15T10:30:00Z"
    }
}
```

---

## BaseRepository - العمليات الأساسية

```php
// البحث
$maintenance = $repo->findById(1);                  // بـ ID
$maintenance = $repo->findByIdOrFail(1);           // أو رفع Exception
$all = $repo->getAll();                            // الكل
$paginated = $repo->paginate(15);                  // مع Pagination
$one = $repo->findBy('status', 'pending');         // حسب شرط
$many = $repo->findAllBy(['status' => 'pending']); // شروط متعددة

// الإنشاء والتحديث والحذف
$maintenance = $repo->create($data);               // إنشاء
$updated = $repo->update(1, $data);                // تحديث
$deleted = $repo->delete(1);                       // حذف

// عمليات متقدمة
$exists = $repo->exists('id', 1);                  // التحقق من الوجود
$or = $repo->updateOrCreate($attr, $values);       // تحديث أو إنشاء
$count = $repo->count();                           // عدد السجلات

// البحث والترتيب
$results = $repo->search(
    ['status' => 'pending'],                       // فلاترات
    ['created_at' => 'desc']                       // ترتيب
);
```

---

## Eloquent Scopes (في Model)

```php
// استخدام Scopes لتبسيط الاستعلامات
$pending = Maintenance::pending()->get();          // بدلاً من where(...
$overdue = Maintenance::overdue()->get();
$scheduled = Maintenance::scheduled()->get();

// Scopes المتقدمة
$range = Maintenance::inDateRange($start, $end)->get();
```

---

## نقاط مهمة

### ✅ افعل هذا

```php
// ✅ استخدم Repository في Service
class MaintenanceService {
    public function __construct(MaintenanceRepository $repo) {
        $this->repository = $repo;
    }
}

// ✅ استخدم Service في Controller
class MaintenanceController {
    public function __construct(MaintenanceService $service) {
        $this->service = $service;
    }
}

// ✅ Validation في FormRequest
class MaintenanceRequest extends FormRequest {
    public function rules() { ... }
}

// ✅ قلّل كود Controller
public function store(MaintenanceRequest $request) {
    return response()->json($this->service->create($request->validated()));
}
```

### ❌ لا تفعل هذا

```php
// ❌ استخدام Model مباشرة في Controller
class MaintenanceController {
    public function store(Request $request) {
        MaintenanceModel::create($request->all()); // ❌❌❌
    }
}

// ❌ Business Logic في Controller
public function store(Request $request) {
    if ($request->input('cost') > 1000) {         // ❌ Logic في Controller
        // ...
    }
}

// ❌ Validation في Controller
public function store(Request $request) {
    $request->validate([...]);                     // ❌ استخدم FormRequest
}

// ❌ وصول مباشر للـ Database في Service
public function create($data) {
    DB::table('maintenance')->insert($data);       // ❌ استخدم Repository
}
```

---

## الملفات الرئيسية

| الملف | الوظيفة |
|------|--------|
| `BaseRepository.php` | الفئة الأم لجميع Repositories |
| `ModuleServiceProvider.php` | تسجيل كل الـ Repositories والـ Services والـ Routes |
| `MaintenanceTrackerModel.php` | Eloquent Model |
| `MaintenanceTrackerRepository.php` | Data Access Layer |
| `MaintenanceTrackerService.php` | Business Logic Layer |
| `MaintenanceTrackerController.php` | API Handler |
| `MaintenanceTrackerRequest.php` | Validation Rules |
| `routes.php` | API Routes |

---

## كيفية إضافة موديول جديد

1. **أنشئ المجلدات**:
   ```
   app/Modules/NewModule/
   ├── Models/
   ├── Repositories/
   ├── Services/
   ├── Controllers/
   ├── Requests/
   └── routes.php
   ```

2. **أنشئ الـ Model**:
   ```php
   class NewModuleModel extends Model { ... }
   ```

3. **أنشئ الـ Repository** (يرث من BaseRepository):
   ```php
   class NewModuleRepository extends BaseRepository { ... }
   ```

4. **أنشئ الـ Service**:
   ```php
   class NewModuleService {
       public function __construct(NewModuleRepository $repo) { ... }
   }
   ```

5. **أنشئ الـ Controller**:
   ```php
   class NewModuleController extends Controller { ... }
   ```

6. **أنشئ الـ FormRequest**:
   ```php
   class NewModuleRequest extends FormRequest { ... }
   ```

7. **أنشئ ملف الـ Routes**:
   ```php
   // routes.php
   Route::prefix('newmodule')->group(function () { ... });
   ```

8. **سجل في ModuleServiceProvider**:
   ```php
   $this->app->singleton(NewModuleRepository::class, ...);
   $this->app->singleton(NewModuleService::class, ...);
   ```

9. **أضف المودييول لقائمة الموديولات** في `ModuleServiceProvider`:
   ```php
   protected $modules = [..., 'NewModule'];
   ```

---

## الاختبار (Testing)

### اختبار Repository
```php
class MaintenanceRepositoryTest extends TestCase
{
    public function test_find_by_vehicle_id()
    {
        $repo = app(MaintenanceRepository::class);
        $maintenance = $repo->getByVehicleId(1);
        $this->assertIsCollection($maintenance);
    }
}
```

### اختبار Service
```php
class MaintenanceServiceTest extends TestCase
{
    public function test_create_maintenance()
    {
        $service = app(MaintenanceService::class);
        $data = ['vehicle_id' => 1, ...];
        $result = $service->createMaintenance($data);
        $this->assertNotNull($result->id);
    }
}
```

### اختبار API
```php
class MaintenanceAPITest extends TestCase
{
    public function test_store_endpoint()
    {
        $response = $this->postJson('/api/maintenance', [
            'vehicle_id' => 1,
            ...
        ]);
        $response->assertStatus(201);
    }
}
```

---

## الخلاصة

هذه المعمارية توفر:

✅ **Separation of Concerns** - كل طبقة لها مسؤولية واحدة
✅ **Scalability** - سهل إضافة موديولات جديدة
✅ **Testability** - سهل الاختبار (Units و Integration)
✅ **Maintainability** - سهل الصيانة والتطوير
✅ **Performance** - Singleton Repositories توفر أداء أفضل
✅ **Code Reusability** - BaseRepository يقلل التكرار
✅ **Type Safety** - Dependency Injection و Type Hints
✅ **Database Agnostic** - Repository يسهل تغيير مصدر البيانات

---

**تم كتابة هذه الوثيقة بواسطة**: Team Leader (Khalid)
**التاريخ**: 2025-01-15
**الإصدار**: 2.0
