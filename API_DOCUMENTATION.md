# API Documentation - Maintenance Tracker Module

## نظرة عامة

هذا الملف يوضح جميع **Endpoints** الخاصة بموديول الصيانة (Maintenance Tracker).

**Base URL**: `http://localhost:8000/api`  
**Authentication**: Bearer Token (JWT)  
**Response Format**: JSON

---

## Endpoints

### 1️⃣ الحصول على جميع الصيانات

#### Request
```
GET /api/maintenance?per_page=15&page=1
```

#### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | عدد النتائج بالصفحة (افتراضي: 15) |
| page | integer | No | رقم الصفحة (افتراضي: 1) |

#### Response
```json
{
    "success": true,
    "message": "تم استرجاع قائمة الصيانات بنجاح",
    "data": {
        "current_page": 1,
        "data": [
            {
                "maintenance_id": 1,
                "vehicle_id": 1,
                "maintenance_type": "oil_change",
                "scheduled_date": "2025-02-01T00:00:00Z",
                "status": "pending",
                "cost": 150.00,
                "created_at": "2025-01-15T10:30:00Z"
            }
        ],
        "total": 50,
        "per_page": 15,
        "last_page": 4
    }
}
```

---

### 2️⃣ الحصول على صيانة محددة

#### Request
```
GET /api/maintenance/{id}
```

#### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | معرف الصيانة |

#### Response
```json
{
    "success": true,
    "message": "تم استرجاع الصيانة بنجاح",
    "data": {
        "maintenance_id": 1,
        "vehicle_id": 1,
        "maintenance_type": "oil_change",
        "scheduled_date": "2025-02-01T00:00:00Z",
        "completion_date": null,
        "status": "pending",
        "cost": 150.00,
        "description": "تغيير الزيت والفلتر",
        "technician_id": 5,
        "technician": {
            "user_id": 5,
            "name": "أحمد محمد"
        },
        "vehicle": {
            "vehicle_id": 1,
            "license_plate": "ABC123",
            "brand": "Toyota"
        },
        "parts_replaced": null,
        "next_maintenance_date": null,
        "created_by": 2,
        "updated_by": null,
        "created_at": "2025-01-15T10:30:00Z",
        "updated_at": "2025-01-15T10:30:00Z"
    }
}
```

---

### 3️⃣ إنشاء صيانة جديدة

#### Request
```
POST /api/maintenance
Content-Type: application/json

{
    "vehicle_id": 1,
    "maintenance_type": "oil_change",
    "scheduled_date": "2025-02-01",
    "cost": 150.00,
    "description": "تغيير الزيت والفلتر",
    "technician_id": 5,
    "created_by": 2
}
```

#### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| vehicle_id | integer | Yes | معرف المركبة |
| maintenance_type | string | Yes | نوع الصيانة (oil_change, repair, inspection, etc.) |
| scheduled_date | date | Yes | تاريخ الصيانة المجدول |
| cost | decimal | No | التكلفة |
| description | string | No | وصف الصيانة |
| technician_id | integer | No | معرف الفني |
| created_by | integer | Yes | معرف منشئ السجل |

#### Response
```json
{
    "success": true,
    "message": "تم إنشاء الصيانة بنجاح",
    "data": {
        "maintenance_id": 51,
        "vehicle_id": 1,
        "maintenance_type": "oil_change",
        "status": "pending",
        "scheduled_date": "2025-02-01T00:00:00Z",
        "cost": 150.00,
        "created_at": "2025-01-15T10:35:00Z"
    }
}
```

#### Validation Errors
```json
{
    "success": false,
    "message": "خطأ في البيانات المدخلة",
    "errors": {
        "vehicle_id": ["معرف المركبة مطلوب"],
        "scheduled_date": ["يجب أن يكون التاريخ بعد اليوم"]
    }
}
```

---

### 4️⃣ تحديث صيانة

#### Request
```
PUT /api/maintenance/{id}
Content-Type: application/json

{
    "cost": 200.00,
    "description": "تم تغيير الزيت والفلتر والفتيل",
    "technician_id": 6,
    "updated_by": 2
}
```

#### Response
```json
{
    "success": true,
    "message": "تم تحديث الصيانة بنجاح",
    "data": {
        "maintenance_id": 1,
        "cost": 200.00,
        "updated_at": "2025-01-15T11:00:00Z"
    }
}
```

---

### 5️⃣ تحديث حالة الصيانة

#### Request
```
PATCH /api/maintenance/{id}/status
Content-Type: application/json

{
    "status": "completed",
    "completion_date": "2025-02-01"
}
```

#### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| status | string | Yes | الحالة (pending, scheduled, in-progress, completed, cancelled) |

#### Response
```json
{
    "success": true,
    "message": "تم تحديث حالة الصيانة بنجاح",
    "data": {
        "maintenance_id": 1,
        "status": "completed",
        "completion_date": "2025-02-01T00:00:00Z",
        "updated_at": "2025-01-15T11:00:00Z"
    }
}
```

---

### 6️⃣ حذف صيانة

#### Request
```
DELETE /api/maintenance/{id}
```

#### Response
```json
{
    "success": true,
    "message": "تم حذف الصيانة بنجاح"
}
```

---

### 7️⃣ الحصول على صيانات مركبة معينة

#### Request
```
GET /api/maintenance/vehicle/{vehicleId}
```

#### Response
```json
{
    "success": true,
    "message": "تم استرجاع صيانات المركبة بنجاح",
    "data": [
        {
            "maintenance_id": 1,
            "vehicle_id": 1,
            "maintenance_type": "oil_change",
            "status": "pending",
            "scheduled_date": "2025-02-01T00:00:00Z"
        }
    ]
}
```

---

### 8️⃣ الصيانات المعلقة لمركبة معينة

#### Request
```
GET /api/maintenance/vehicle/{vehicleId}/pending
```

#### Response
```json
{
    "success": true,
    "message": "تم استرجاع الصيانات المعلقة بنجاح",
    "data": [...]
}
```

---

### 9️⃣ الصيانات المتأخرة

#### Request
```
GET /api/maintenance/overdue
```

#### Response
```json
{
    "success": true,
    "message": "تم استرجاع الصيانات المتأخرة بنجاح",
    "count": 5,
    "data": [
        {
            "maintenance_id": 2,
            "vehicle_id": 3,
            "scheduled_date": "2025-01-10T00:00:00Z",
            "status": "pending",
            "vehicle": { "vehicle_id": 3, "license_plate": "XYZ789" }
        }
    ]
}
```

---

### 🔟 الصيانات القادمة

#### Request
```
GET /api/maintenance/upcoming?date=2025-02-01
```

#### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| date | date | No | التاريخ (افتراضي: اليوم) |

#### Response
```json
{
    "success": true,
    "message": "تم استرجاع الصيانات القادمة بنجاح",
    "data": [...]
}
```

---

### 1️⃣1️⃣ البحث المتقدم

#### Request
```
POST /api/maintenance/search
Content-Type: application/json

{
    "vehicle_id": 1,
    "status": "pending",
    "maintenance_type": "oil_change",
    "start_date": "2025-01-01",
    "end_date": "2025-12-31",
    "min_cost": 0,
    "max_cost": 500
}
```

#### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| vehicle_id | integer | No | معرف المركبة |
| status | string | No | الحالة |
| maintenance_type | string | No | نوع الصيانة |
| start_date | date | No | تاريخ البداية |
| end_date | date | No | تاريخ النهاية |
| min_cost | decimal | No | الحد الأدنى للتكلفة |
| max_cost | decimal | No | الحد الأقصى للتكلفة |

#### Response
```json
{
    "success": true,
    "message": "تم البحث بنجاح",
    "count": 3,
    "data": [...]
}
```

---

### 1️⃣2️⃣ الإحصائيات

#### Request
```
GET /api/maintenance/stats
```

#### Response
```json
{
    "success": true,
    "message": "تم استرجاع الإحصائيات بنجاح",
    "data": {
        "total": 100,
        "pending": 25,
        "completed": 70,
        "scheduled": 5,
        "overdue": 3,
        "total_cost": 15000.00
    }
}
```

---

### 1️⃣3️⃣ جدولة الصيانة التالية

#### Request
```
POST /api/maintenance/{id}/schedule-next
Content-Type: application/json

{
    "maintenance_type": "oil_change",
    "next_date": "2025-05-01"
}
```

#### Response
```json
{
    "success": true,
    "message": "تم جدولة الصيانة التالية بنجاح",
    "data": {
        "maintenance_id": 52,
        "vehicle_id": 1,
        "maintenance_type": "oil_change",
        "scheduled_date": "2025-05-01T00:00:00Z",
        "status": "scheduled"
    }
}
```

---

### 1️⃣4️⃣ إتمام الصيانة

#### Request
```
POST /api/maintenance/{id}/complete
Content-Type: application/json

{
    "cost": 200.00,
    "parts_replaced": [
        "Oil Filter",
        "Air Filter"
    ],
    "description": "تم استبدال الفلاتر بنجاح"
}
```

#### Response
```json
{
    "success": true,
    "message": "تم إتمام الصيانة بنجاح",
    "data": {
        "maintenance_id": 1,
        "status": "completed",
        "completion_date": "2025-01-15T11:00:00Z",
        "cost": 200.00,
        "parts_replaced": ["Oil Filter", "Air Filter"]
    }
}
```

---

## كود الأخطاء (Status Codes)

| Code | Description |
|------|-------------|
| 200 | نجح ✅ |
| 201 | تم الإنشاء بنجاح ✅ |
| 400 | بيانات غير صحيحة ❌ |
| 401 | لم يتم المصادقة ❌ |
| 403 | ممنوع الوصول ❌ |
| 404 | غير موجود ❌ |
| 422 | بيانات غير صالحة للمعالجة ❌ |
| 500 | خطأ في الخادم ❌ |

---

## أمثلة استخدام cURL

### إنشاء صيانة
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

### الحصول على الصيانات
```bash
curl -X GET http://localhost:8000/api/maintenance?per_page=10 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### تحديث حالة
```bash
curl -X PATCH http://localhost:8000/api/maintenance/1/status \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "status": "completed"
  }'
```

---

## أمثلة استخدام JavaScript/Axios

```javascript
import axios from 'axios';

const API_BASE = 'http://localhost:8000/api';
const token = localStorage.getItem('auth_token');

const api = axios.create({
    baseURL: API_BASE,
    headers: {
        'Authorization': `Bearer ${token}`
    }
});

// إنشاء صيانة
async function createMaintenance(data) {
    try {
        const response = await api.post('/maintenance', data);
        console.log('تم الإنشاء:', response.data);
    } catch (error) {
        console.error('خطأ:', error.response.data);
    }
}

// الحصول على الصيانات
async function getMaintenances(perPage = 15) {
    try {
        const response = await api.get('/maintenance', {
            params: { per_page: perPage }
        });
        console.log('الصيانات:', response.data.data);
    } catch (error) {
        console.error('خطأ:', error.response.data);
    }
}

// تحديث الحالة
async function updateStatus(maintenanceId, status) {
    try {
        const response = await api.patch(
            `/maintenance/${maintenanceId}/status`,
            { status }
        );
        console.log('تم التحديث:', response.data);
    } catch (error) {
        console.error('خطأ:', error.response.data);
    }
}
```

---

## ملاحظات مهمة

1. **Authentication**: جميع الطلبات تحتاج Bearer Token
2. **Validation**: البيانات يتم التحقق منها في FormRequest
3. **Pagination**: الحد الأقصى لكل صفحة 100 سجل
4. **Timezone**: جميع التواريخ بـ UTC (Z)
5. **Errors**: جميع الأخطاء تأتي بـ JSON مع رسالة واضحة

---

**وثقة API**: Maintenance Tracker Module
**الإصدار**: 1.0
**آخر تحديث**: 2025-01-15
