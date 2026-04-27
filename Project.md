# Fleet Tracking Platform — Full Project Reference

> **AI CONTEXT BLOCK — READ THIS FIRST**
> This document is the single source of truth for the Fleet Tracking Platform project.
> It contains every functional requirement, every frontend app, every backend microservice,
> all user roles, and all system flows. When assisting with this project, always refer back
> to this file. Do not make assumptions about features that are not listed here.
> The tech stack is Laravel 12 + SQL Server + Redis + Laravel Reverb (WebSockets).

---

## TABLE OF CONTENTS

1. [Project Overview](#1-project-overview)
2. [User Roles](#2-user-roles)
3. [System Architecture](#3-system-architecture)
4. [Frontend Applications](#4-frontend-applications)
   - 4.1 [Driver App](#41-driver-app)
   - 4.2 [Customer Portal](#42-customer-portal)
   - 4.3 [Main Dashboard](#43-main-dashboard)
   - 4.4 [Maintenance App](#44-maintenance-app)
5. [All Functional Requirements (42 Functions)](#5-all-functional-requirements-42-functions)
6. [Backend Microservices (8 Services)](#6-backend-microservices-8-services)
7. [Non-Functional Requirements](#7-non-functional-requirements)
8. [Data Models & Key Entities](#8-data-models--key-entities)
9. [System Flows (End-to-End)](#9-system-flows-end-to-end)
10. [Glossary](#10-glossary)

---

## 1. PROJECT OVERVIEW

**Project Name:** Fleet Tracking Platform
**Type:** Multi-app logistics & fleet management system
**Purpose:** End-to-end platform to manage vehicle fleets, delivery routes, real-time driver tracking, customer delivery notifications, and vehicle maintenance.

**Summary:**
- 5 applications (4 frontend + 1 backend layer)
- 42 named functional requirements
- 8 backend microservices
- Built with Laravel 12, SQL Server, Redis, Laravel Reverb, Google Maps/Routes API

**Key Numbers:**
| Item | Count |
|------|-------|
| Total functional requirements | 42 |
| Frontend apps | 4 |
| Backend microservices | 8 |
| User roles | 4 (Driver, Dispatcher, Fleet Manager, Mechanic) |
| Public-facing portals | 1 (Customer Portal — no login required) |

---

## 2. USER ROLES

The system has 4 authenticated roles plus 1 public (unauthenticated) role.

### 2.1 Fleet Manager
- Has full access to all system features
- Manages users (CRUD for drivers, dispatchers, mechanics)
- Manages vehicles (CRUD)
- Reviews all reports, analytics, and audit trails
- Makes decisions on vehicle replacement based on cost analysis
- Approves maintenance work orders

### 2.2 Dispatcher
- Creates and manages delivery routes
- Assigns drivers and vehicles to routes
- Monitors live ETAs and receives delay alerts
- Handles emergency express insertions and dynamic rerouting
- Imports bulk orders from CSV/XML files
- Sends the tracking link to customers

### 2.3 Driver
- Mobile-only user (Driver App)
- Completes pre-trip safety inspections before starting a route
- Views assigned route and stop sequence
- Scans QR codes on parcels before delivery
- Captures proof of delivery (photo + digital signature)
- Reports incidents and breakdowns
- Logs cash-on-delivery (COD) collections
- Works in offline mode when no internet is available

### 2.4 Mechanic
- Uses the Maintenance App
- Receives maintenance work orders assigned by Fleet Manager
- Updates vehicle status (in-repair, available)
- Records spare parts used
- Logs repair details and part replacement lifecycle

### 2.5 Customer (Public — No Login)
- Accesses a temporary signed tracking link (no account required)
- Views live vehicle location on a map
- Views order status timeline
- Sets delivery preferences (e.g., "leave at door", "ring bell")
- Receives SMS/Email/Push notifications at each delivery milestone
- Signs digitally on the driver's device upon delivery
- Submits post-delivery feedback/rating

---

## 3. SYSTEM ARCHITECTURE

```
┌─────────────────────────────────────────────────────────────────┐
│                        FRONTEND APPS                            │
│                                                                  │
│  [Driver App]   [Customer Portal]  [Main Dashboard]  [Maintenance App] │
│   (Mobile)       (Public, no login)  (Web, internal)   (Web/Mobile)    │
└────────────────────────────┬────────────────────────────────────┘
                             │ REST API + WebSocket (Reverb)
┌────────────────────────────▼────────────────────────────────────┐
│                    BACKEND MICROSERVICES                        │
│                                                                  │
│  Auth & Identity │ Real-time GPS │ Route & Dispatch             │
│  Order Mgmt      │ Notifications  │ Maintenance                 │
│  Reporting       │ Logging & Audit                              │
└────────────────────────────┬────────────────────────────────────┘
                             │
┌────────────────────────────▼────────────────────────────────────┐
│                       DATA LAYER                                │
│  SQL Server (primary) │ Redis (cache/queues/sessions)           │
│  Azure Blob Storage (POD photos/signatures)                     │
│  SQL Server Read Replica (analytics queries)                    │
└─────────────────────────────────────────────────────────────────┘
```

**Tech Stack:**
| Layer | Technology |
|-------|-----------|
| Backend framework | Laravel 12 |
| Primary database | SQL Server |
| Cache / Queues / Sessions | Redis |
| WebSocket / Real-time | Laravel Reverb |
| Authentication | Laravel Sanctum |
| File storage | Azure Blob Storage |
| Maps & routing | Google Maps Platform / Google Routes API |
| Push notifications | Firebase FCM |
| SMS | Twilio |
| Email | SMTP / AWS SES |
| Error tracking | Sentry |
| PDF generation | Snappy (wkhtmltopdf) |
| Image processing | Intervention Image |
| Log aggregation | ELK Stack |

---

## 4. FRONTEND APPLICATIONS

### 4.1 Driver App

**Platform:** Mobile (iOS/Android)
**Users:** Drivers only
**Accent color:** Amber
**Purpose:** The driver's day-to-day operational tool. All delivery-related actions happen here.

**User flow (in order):**
1. Login → 2. Pre-Trip Inspection → 3. View Active Route → 4. QR Scan parcel → 5. Proximity alert at 500m → 6. Capture POD (photo + signature) → 7. RTB if undelivered

**Functions in this app:**

| # | Function Name | Type | Description |
|---|--------------|------|-------------|
| N | Driver Login & Auth | NEW | Driver logs in with email + password to access routes and orders |
| N | Active Route View | NEW | Screen showing the assigned route: stops in order, orders, ETA per stop |
| 12 | Pre-Trip Safety Inspection | Required | Mandatory digital checklist (tires, brakes, lights, documents) before "Start Route" is unlocked |
| 13 | Digital Signature Capture | Required | Customer signs on driver's screen; stored encrypted with timestamp and GPS |
| 14 | Photo-Evidence Attachment | Required | Driver takes photo of drop location for "Safe Drop" when customer is absent; GPS auto-tagged |
| 15 | Incident Reporting Workflow | Required | Driver reports accident/breakdown; triggers tow request; Dispatcher gets alert; Dynamic Rerouting activates |
| 16 | Break & Rest Manager | Required | System tracks driving hours; locks "Complete Delivery" button when rest is required by law |
| 17 | QR-Code Parcel Verification | Required | Driver must scan parcel QR before completing delivery; wrong QR shows error |
| 18 | COD Reconciliation | Required | Driver logs cash collected; accumulated in ledger; reconciled at warehouse drop-off |
| 19 | Offline Progress Queue | Required | When internet is lost, driver continues working; data stored locally and synced automatically when connection resumes |
| 20 | Proximity-Based Auto Check-in | Required | When driver is within 500m of customer, system auto-sends customer notification |
| 21 | Return-to-Base (RTB) Logic | Required | If delivery fails, driver selects reason; parcel status changes to "Returned"; enters warehouse again |
| 22 | Driver Performance Score | Required | Driver sees own composite score: delivery speed + fuel efficiency + customer ratings |

---

### 4.2 Customer Portal

**Platform:** Web (public URL, no login required)
**Users:** End customers
**Accent color:** Gray
**Purpose:** Read-only transparency portal. Customer receives a temporary signed link and can view their delivery progress, set preferences, and give feedback.

**User flow (in order):**
1. Receives tracking link → 2. Views order status → 3. Sets delivery preferences → 4. Receives notifications → 5. Signs on driver's device → 6. Submits feedback

**Functions in this app:**

| # | Function Name | Type | Description |
|---|--------------|------|-------------|
| 33 | Live Tracking Public Link | Required | System generates a temporary signed link per order; customer opens it with no account; sees vehicle location on map updating every X seconds |
| N | Order Status Page | NEW | Timeline page showing step-by-step order history: "Picked up → In transit → Arrived → Delivered" |
| 34 | Delivery Preference Manager | Required | Customer sets special delivery instructions ("Leave at neighbor", "Ring quietly", "No safe drop"); driver sees this on Driver App |
| 35 | Multi-Channel Notifications | Required | Customer receives notifications via SMS, Email, or Web Push at each delivery milestone |
| 13 | Signature (Customer Side) | Required | Customer signs digitally on the driver's device upon delivery; this is the customer-facing side of function #13 |
| 36 | Post-Delivery Feedback | Required | After delivery, customer rates driver and parcel condition with stars + comment; feeds into Driver Performance Score |

---

### 4.3 Main Dashboard

**Platform:** Web (internal, authenticated)
**Users:** Fleet Manager + Dispatcher
**Accent color:** Teal
**Purpose:** Central operational hub. Dispatcher manages routes and orders; Fleet Manager has full system visibility.

**Dispatcher flow (in order):**
1. Login → 2. Import orders (CSV) → 3. Geospatial Clustering → 4. Route Optimization → 5. Load Capacity Check → 6. Assign Driver → 7. Monitor ETAs

**Fleet Manager additional access:**
- Vehicle CRUD, User CRUD, RBAC management
- Historical route playback
- Sustainability/CO2 reports
- System audit trail
- Database archiving

**Functions in this app:**

| # | Function Name | Role | Type | Description |
|---|--------------|------|------|-------------|
| N | Login & Authentication | Both | NEW | Email + password login; system detects role and shows appropriate permissions |
| N | Vehicle CRUD Management | Manager | NEW | Add/edit/delete vehicles (type, capacity, plate number) |
| N | User Management (CRUD) | Manager | NEW | Add/edit/deactivate drivers, dispatchers, mechanics |
| 01 | Route Priority Balancer | Dispatcher | Required | Sorts orders by: perishable goods flag, customer priority level, promised delivery window |
| 02 | Geospatial Clustering | Dispatcher | Required | Groups geographically close delivery points into zones; assigns each zone to nearest vehicle |
| 03 | Load Capacity Checker | Dispatcher | Required | Before confirming route: checks total weight + volume of orders does not exceed vehicle max capacity |
| 04 | Dynamic Rerouting | Dispatcher | Required | When a vehicle breaks down, redistributes remaining orders to other vehicles and recalculates ETAs |
| 05 | Stop-Time ETA Estimator | Dispatcher | Required | Calculates ETA per stop: (distance / speed) + (parcel count × unload time) |
| 06 | Multi-Drop Sequence Optimizer | Dispatcher | Required | Algorithm reorders stops for minimum total distance (Travelling Salesman Problem approach) |
| 07 | Emergency Express Insertion | Dispatcher | Required | Inserts urgent order into an active route at the best possible point; recalculates all ETAs |
| 08 | Driver-Vehicle Pairing Guard | Dispatcher | Required | Checks driver's license type matches the vehicle type before confirming assignment |
| 09 | Shift Transition Manager | Dispatcher | Required | Transfers route, vehicle, and pending orders from one driver to another mid-shift |
| 10 | Delivery Window Violation Alert | Dispatcher | Required | If ETA will exceed promised delivery window, Dispatcher receives immediate alert with options |
| 11 | Fuel-Efficient Speed Advisor | Dispatcher | Required | Suggests optimal speed per route based on load weight and distance to save fuel |
| 39 | Bulk Order Import (CSV/XML) | Dispatcher | Required | Uploads daily orders from CSV or XML file from sales system; system validates columns and imports |
| 37 | RBAC — Role-Based Access Control | Manager | Required | Defines what each role can see/do: Manager = all; Dispatcher = routing + orders; Mechanic = maintenance only |
| 38 | Historical Route Playback | Manager | Required | Reviews a completed past route step-by-step: driver, time per stop, any delays |
| 40 | Sustainability Report (CO2) | Manager | Required | Monthly report of fleet CO2 emissions with reduction suggestions; formula: distance × emission factor per vehicle type |
| 41 | System Audit Trail | Manager | Required | Permanent log of every manual system change: who changed what, when, old value vs new value |
| 42 | Database Integrity Manager | Manager | Required | Archives old routes and completed maintenance records to keep main tables fast; scheduled or manual trigger |

---

### 4.4 Maintenance App

**Platform:** Web / Mobile
**Users:** Fleet Manager + Mechanic
**Accent color:** Coral
**Purpose:** Dedicated vehicle maintenance management tool. Tracks vehicle health, assigns mechanics, manages spare parts, and decides when a vehicle should be retired.

**User flow (in order):**
1. Login → 2. Odometer Alert triggers → 3. Assign Mechanic → 4. Lock vehicle (Out-of-Service) → 5. Order spare parts → 6. Log repair + update lifecycle → 7. Cost-to-value check

**Functions in this app:**

| # | Function Name | Role | Type | Description |
|---|--------------|------|------|-------------|
| N | Mechanic Login & Auth | Both | NEW | Mechanic logs in to see assigned work orders and vehicles needing service |
| 23 | Odometer-Driven Service Alerts | Auto | Required | System tracks odometer of each vehicle; sends automatic alert when scheduled maintenance is due (e.g., every 5,000 km → oil change alert) |
| 24 | Fuel Expense Audit | Manager | Required | Compares actual fuel invoices vs GPS distance traveled; flags >X% discrepancy as possible theft or leak |
| 25 | Vehicle Out-of-Service Lockout | Mechanic | Required | When mechanic sets vehicle to "In Repair", system removes it from available vehicle list in Dashboard |
| 26 | Part Replacement Lifecycle | Mechanic | Required | Tracks lifespan of each part per vehicle (brakes, battery, air filter, etc.); mechanic logs replacements after each repair; system calculates remaining life |
| 27 | Annual Inspection & Insurance | Auto | Required | Alerts Fleet Manager 30 days before insurance or annual inspection expires for any vehicle |
| 28 | Fuel Efficiency Comparator | Manager | Required | Compares fuel consumption efficiency across vehicles; shows table/chart (Vehicle A = 12km/L, Vehicle B = 9km/L) to support upgrade decisions |
| 29 | Maintenance Cost-to-Value | Manager | Required | Formula: repair cost ÷ market value > 40% → system recommends retiring the vehicle |
| 30 | Technician Assignment Hub | Manager | Required | Fleet Manager assigns the right mechanic (internal or external) to a maintenance ticket; mechanic sees it in their app |
| 31 | Spare Parts Inventory | Mechanic | Required | Tracks garage parts stock (oils, filters, bulbs, brakes); deducts when mechanic uses a part; triggers reorder alert at minimum stock level |
| 32 | Emergency Roadside Assistance | Manager/Mechanic | Required | When driver reports breakdown, GPS location is captured automatically; Fleet Manager dispatches nearest mechanic |

---

## 5. ALL FUNCTIONAL REQUIREMENTS (42 FUNCTIONS)

This is the master flat list of all 42 named requirements, organized by source app. Requirements marked [NEW] are not in the original 42 but are identified as necessary for the system to function.

### Driver App Functions
| ID | Name | Priority |
|----|------|----------|
| N [NEW] | Driver Login & Auth | Must |
| N [NEW] | Active Route View | Must |
| 12 | Pre-Trip Safety Inspection | Must |
| 13 | Digital Signature Capture | Must |
| 14 | Photo-Evidence Attachment | Must |
| 15 | Incident Reporting Workflow | Must |
| 16 | Break & Rest Manager | Must |
| 17 | QR-Code Parcel Verification | Must |
| 18 | COD Reconciliation | Must |
| 19 | Offline Progress Queue | Must |
| 20 | Proximity-Based Auto Check-in | Must |
| 21 | Return-to-Base (RTB) Logic | Must |
| 22 | Driver Performance Score | Must |

### Customer Portal Functions
| ID | Name | Priority |
|----|------|----------|
| 33 | Live Tracking Public Link | Must |
| N [NEW] | Order Status Page | Must |
| 34 | Delivery Preference Manager | Must |
| 35 | Multi-Channel Notifications | Must |
| 13 | Signature (Customer Side) | Must |
| 36 | Post-Delivery Feedback | Must |

### Main Dashboard Functions
| ID | Name | Priority |
|----|------|----------|
| N [NEW] | Login & Authentication | Must |
| N [NEW] | Vehicle CRUD Management | Must |
| N [NEW] | User Management (CRUD) | Must |
| 01 | Route Priority Balancer | Must |
| 02 | Geospatial Clustering | Must |
| 03 | Load Capacity Checker | Must |
| 04 | Dynamic Rerouting | Must |
| 05 | Stop-Time ETA Estimator | Must |
| 06 | Multi-Drop Sequence Optimizer | Must |
| 07 | Emergency Express Insertion | Must |
| 08 | Driver-Vehicle Pairing Guard | Must |
| 09 | Shift Transition Manager | Must |
| 10 | Delivery Window Violation Alert | Must |
| 11 | Fuel-Efficient Speed Advisor | Should |
| 39 | Bulk Order Import (CSV/XML) | Must |
| 37 | RBAC — Role-Based Access Control | Must |
| 38 | Historical Route Playback | Must |
| 40 | Sustainability Report (CO2) | Should |
| 41 | System Audit Trail | Must |
| 42 | Database Integrity Manager | Should |

### Maintenance App Functions
| ID | Name | Priority |
|----|------|----------|
| N [NEW] | Mechanic Login & Auth | Must |
| 23 | Odometer-Driven Service Alerts | Must |
| 24 | Fuel Expense Audit | Must |
| 25 | Vehicle Out-of-Service Lockout | Must |
| 26 | Part Replacement Lifecycle | Must |
| 27 | Annual Inspection & Insurance | Must |
| 28 | Fuel Efficiency Comparator | Should |
| 29 | Maintenance Cost-to-Value | Should |
| 30 | Technician Assignment Hub | Must |
| 31 | Spare Parts Inventory | Must |
| 32 | Emergency Roadside Assistance | Must |

---

## 6. BACKEND MICROSERVICES (8 SERVICES)

Each service is a Laravel module. They communicate internally via service-to-service API keys. All routes go through a shared authentication middleware.

---

### 6.1 Auth & Identity Service
**Tags:** Core
**Stack:** Laravel 12, Laravel Sanctum, SQL Server, Redis, Laravel Rate Limiter

**Purpose:** Handles all authentication and authorization across the entire platform.

| Function ID | Name | Priority | Description |
|-------------|------|----------|-------------|
| AUTH-01 | Driver Login & Auth | Must | Phone/email + password login; validates active account status |
| AUTH-02 | Staff Login (Manager/Mechanic) | Must | Employee number or email login for internal staff |
| AUTH-03 | RBAC Policy Engine (fn 37) | Must | Role-based access control; each role has a defined set of permissions |
| AUTH-04 | Access/Refresh Token Flow | Must | Short-lived access tokens (15 min) + long-lived refresh tokens (7 days) |
| AUTH-05 | Logout All Sessions | Must | Invalidates all active tokens for a user across all devices |
| AUTH-06 | Forgot / Reset Password | Must | Email reset link with a temporary token valid for 1 hour |
| AUTH-07 | MFA Challenge | Should | OTP via SMS for sensitive accounts (optional) |
| AUTH-08 | Service-to-Service Auth | Should | API keys for internal service communication with usage tracking |

**Key requirements:**
- Use Laravel Sanctum as the unified auth strategy (no mixing with JWT)
- Hash passwords using `Hash::make()` with a password policy
- Store sessions and tokens in Redis for instant revocation
- Rate limit login attempts + lockout after N failures
- All failed login attempts must be logged in the audit trail
- Shared `auth` + `permissions` middleware used by all other services

---

### 6.2 Real-time Tracking & GPS Service
**Tags:** Real-time
**Stack:** Laravel Reverb, Redis Pub/Sub, SQL Server Geography, Laravel Queue, Google Maps Platform

**Purpose:** Ingests and broadcasts live GPS data from drivers. Powers the live map in the Customer Portal and Dashboard.

| Function ID | Name | Priority | Description |
|-------------|------|----------|-------------|
| RT-01 | Live Tracking Public Link (fn 33) | Must | Public link with PII hidden; shows vehicle location to customer |
| RT-02 | Vehicle Location Ingestion Stream | Must | Receives GPS updates from driver every 5 seconds via Reverb WebSocket |
| RT-03 | Proximity Alert 500m (fn 20) | Must | Triggers customer notification when vehicle is within 500m |
| RT-04 | Geofence Entry/Exit Events | Must | Logs vehicle entering/leaving predefined zones; generates events |
| RT-05 | Driver Heartbeat Timeout | Must | Marks driver as offline if no GPS pings received for 3 minutes; alerts Dashboard |
| RT-06 | GPS Spoofing Detection | Should | Flags suspicious location jumps (speed > 300 km/h) as potential fraud |
| RT-07 | Last Known Location Fallback | Should | Shows last recorded position if connection drops (instead of disappearing from map) |
| RT-08 | Out-of-Service Sync (fn 25) | Should | Syncs vehicle status (In Repair / In Maintenance) across all services automatically |

**Key requirements:**
- Use Reverb channels for near-real-time location broadcasts
- Use SQL Server `Geography` type + `STDistance()` for proximity calculations
- Tracking links must be signed with an expiry time
- Filter out inaccurate GPS points before processing
- Use Redis Pub/Sub for distributing updates across multiple server instances

---

### 6.3 Route & Dispatch Service
**Tags:** Core, Operations
**Stack:** Laravel, SQL Server, Laravel Queue (Redis), Laravel Scheduler, Google Routes API

**Purpose:** The brain of the dispatching operation. Handles all routing logic, optimization, ETA calculation, and assignment.

| Function ID | Name | Priority | Description |
|-------------|------|----------|-------------|
| RD-01 | Route Priority Balancer (fn 01) | Must | Sorts orders by urgency (Express/Normal) and geography before distribution |
| RD-02 | Geospatial Clustering (fn 02) | Must | Groups nearby orders into geographic zones to minimize travel |
| RD-03 | Load Capacity Checker (fn 03) | Must | Validates vehicle weight and count capacity before assigning new orders |
| RD-04 | Multi-Drop Sequence Optimizer (fn 06) | Must | Optimizes stop order using TSP heuristic to minimize total distance |
| RD-05 | Stop-Time ETA Estimator (fn 05) | Must | Calculates expected delivery time per order factoring in stop time |
| RD-06 | Emergency Express Insertion (fn 07) | Must | Inserts urgent order into active route; recalculates all ETAs |
| RD-07 | Breakdown Redistribution (fn 04) | Must | Automatically redistributes orders from broken-down vehicle to others |
| RD-08 | Shift Transition Manager (fn 09) | Must | Transitions routes between morning/evening shift drivers |
| RD-09 | Driver-Vehicle Pairing Guard (fn 08) | Must | Validates driver license matches vehicle type (e.g., heavy truck license required) |
| RD-10 | Delivery Window Alert (fn 10) | Must | Alerts if order will miss its promised delivery window |
| RD-11 | Fuel Speed Advisor (fn 11) | Should | Recommends economical speed based on fuel prices and vehicle efficiency |
| RD-12 | Manual Override + Route Versioning | Should | Allows manual route edits; tracks version history of changes with auth |

**Key requirements:**
- Google Routes API is the single source for all navigation calculations
- Re-distribution and ETA recalculation happen via async queues
- Every manual route override must be logged (before/after values) for audit
- A scheduled job runs every minute to compare current ETAs vs promised windows
- All sensitive endpoints must be idempotent (safe to retry without duplication)

---

### 6.4 Order Management Service
**Tags:** Core, Operations
**Stack:** Laravel REST APIs, SQL Server, Laravel Filesystem, Azure Blob Storage, Intervention Image

**Purpose:** Manages the full lifecycle of every delivery order, from CSV import to final proof of delivery.

| Function ID | Name | Priority | Description |
|-------------|------|----------|-------------|
| OM-01 | Bulk Order Import (fn 39) | Must | Imports orders from CSV/XML with schema validation before insertion |
| OM-02 | Active Route View | Must | Shows driver's current orders on an interactive map |
| OM-03 | Pre-Trip Inspection (fn 12) | Must | Logs vehicle condition (fuel, tires, lights) before departure |
| OM-04 | QR / Barcode Verification (fn 17) | Must | Scans order QR/barcode to verify match before completing delivery |
| OM-05 | POD Capture Photo + Signature (fn 13) | Must | Captures signature image and photo as proof of delivery; stores on Azure Blob |
| OM-06 | Delivery Preference Manager (fn 34) | Must | Manages customer delivery preferences (time, alternate location, special requirements) |
| OM-07 | RTB Workflow (fn 21) | Must | Return-to-base flow: return → assess → correct → redeliver |
| OM-08 | Order Status Timeline | Must | Displays order lifecycle as a visual timeline (pending, in transit, delivered, failed) |
| OM-09 | Partial Delivery Handling | Should | Allows delivering part of an order; schedules retry for the remainder |
| OM-10 | Reattempt Scheduling | Should | Auto-schedules delivery retries with exponential backoff |
| OM-11 | COD Reconciliation (fn 18) | Should | Matches cash collected by driver against the order value |
| OM-12 | Failure Reason Taxonomy | Should | Standardized failure reason codes (wrong address, customer absent, etc.) for analytics |

**Order State Machine:**
```
Pending → InTransit → Delivered
                    → Returned
                    → Failed
```

**Key requirements:**
- CSV/XML imports validated against a defined schema before any insertion
- All POD media (photos, signatures) stored on Azure Blob Storage
- Each POD record must include: signature/photo + timestamp + GPS coordinates
- Prevent changing order status twice with the same event (idempotent state updates)
- Failure reasons must use the standard taxonomy for consistency in analytics

---

### 6.5 Notification Service
**Tags:** Real-time, Operations
**Stack:** Laravel Notifications, Firebase FCM, Twilio, SMTP/SES, Laravel Queue (Redis)

**Purpose:** Sends all system notifications across multiple channels (Push, SMS, Email) based on delivery events.

| Function ID | Name | Priority | Description |
|-------------|------|----------|-------------|
| NF-01 | Notification Event Intake | Must | Receives system events and queues them for processing |
| NF-02 | User Preference Center | Must | User controls: which notification channels, preferred times |
| NF-03 | Push Notifications (fn 35) | Must | Sends mobile push alerts via Firebase FCM |
| NF-04 | SMS Notifications | Must | Sends SMS via Twilio as backup channel |
| NF-05 | Email Notifications | Must | Sends formal email alerts via SMTP/SES |
| NF-06 | Proximity Customer Alert (fn 20) | Must | Triggers multi-channel customer alert when vehicle is 500m away |
| NF-07 | Delivery Delay Alert (fn 10) | Must | Immediate alert if delivery window will be missed |
| NF-08 | Maintenance Alerts (fn 23/31/32) | Should | Oil, tire, inspection alerts to managers |
| NF-09 | Deduplication Guard | Should | Prevents sending the same notification twice in a short time window |
| NF-10 | Retry + Escalation + Quiet Hours | Should | 3 retries with exponential backoff; escalation logic; respects quiet hours |
| NF-11 | Delivery Status Tracking | Should | Tracks notification state: sent → delivered → read |

**Channel Fallback Logic:**
```
Push Notification → [if failed] → SMS → [if failed] → Email
```

**Key requirements:**
- Redis queues as the single unified queue strategy
- Deduplication key: prevents same event from reaching same user more than once
- All retries use exponential backoff with a dead-letter queue for permanent failures
- Delivery logs must record: sent/delivered/failed per message

---

### 6.6 Maintenance Service
**Tags:** Operations
**Stack:** Laravel, SQL Server, Laravel Scheduler, Laravel Events, Redis

**Purpose:** Manages the full lifecycle of vehicle maintenance, from automated alerts to work order completion and cost analysis.

| Function ID | Name | Priority | Description |
|-------------|------|----------|-------------|
| MT-01 | Odometer Service Alert (fn 23) | Must | Auto-alerts at defined mileage thresholds (e.g., every 10,000 km) |
| MT-02 | Work Order Lifecycle | Must | Full work order lifecycle: open → assigned → in_progress → resolved → closed |
| MT-03 | Technician Assignment Hub (fn 30) | Must | Assigns mechanics based on specialty, location, and availability |
| MT-04 | Out-of-Service Lockout (fn 25) | Must | Prevents vehicle from being dispatched when in maintenance status |
| MT-05 | Spare Parts Inventory (fn 31) | Must | Inventory management: minimum stock, reorder level, supplier lead time |
| MT-06 | Repair Log Lifecycle (fn 26) | Must | Documents each repair step: inspection, parts, testing |
| MT-07 | Emergency Roadside Dispatch (fn 32) | Must | Priority dispatch of nearest mechanic to driver's GPS location |
| MT-08 | Maintenance Cost-to-Value (fn 29) | Should | Flags vehicle for replacement if repair_cost / market_value > 0.40 |
| MT-09 | Fuel Efficiency Comparator (fn 28) | Should | Compares fuel efficiency before and after maintenance |
| MT-10 | Part Warranty Tracking | Should | Tracks warranty periods on installed parts |
| MT-11 | Preventive Plan Templates | Nice to have | Predefined maintenance plan templates by vehicle type |

**Work Order States:**
```
open → assigned → in_progress → resolved → closed
```

**Vehicle Status Enum:**
```
available | in_service | out_of_service
```

**Key requirements:**
- `repair_cost / market_value > 0.4` triggers a "Recommend Replacement" flag
- Always check technician availability before any assignment
- All work order changes and inventory updates must be logged in the audit trail
- Minimum stock thresholds must be configurable per part type

---

### 6.7 Reporting & Analytics Service
**Tags:** Analytics
**Stack:** Laravel, SQL Server, SQL Server Read Replica, Laravel Scheduler, Snappy (wkhtmltopdf)

**Purpose:** Aggregates data from all services into KPIs, dashboards, and scheduled reports for Fleet Managers.

| Function ID | Name | Priority | Description |
|-------------|------|----------|-------------|
| AN-01 | KPI Aggregation Jobs | Must | Calculates key performance indicators daily from raw data |
| AN-02 | Driver Performance Score (fn 22) | Must | Composite score: on-time rate + safety + fuel efficiency + customer ratings |
| AN-03 | Fleet Utilization Report (fn 40) | Must | Report on working hours, capacity used, CO2 emissions per vehicle |
| AN-04 | On-Time Delivery KPI (fn 41) | Must | Percentage of deliveries completed within promised window (daily/weekly) |
| AN-05 | Fuel Efficiency Benchmark (fn 28) | Should | Compares each vehicle's fuel consumption against fleet average |
| AN-06 | Maintenance Cost Analysis (fn 29) | Should | Breaks down maintenance costs: preventive vs reactive |
| AN-07 | Anomaly Detection | Should | Flags anomalies: missing fuel, unusual speeds, excessive stop durations |
| AN-08 | Daily/Weekly Scheduled Reports | Must | Auto-generated PDF reports sent to managers on schedule |
| AN-09 | KPI Drill-Down Explorer | Nice to have | Interactive dashboard to drill from fleet level down to individual driver |

**Driver Performance Score Formula:**
```
Score (0–100) = (delivery_speed × weight_A) + (fuel_efficiency × weight_B) + (customer_rating × weight_C)
```
Weights are configurable without code changes.

**Key requirements:**
- All analytics queries read from the SQL Server Read Replica (never the OLTP primary)
- KPI weights/configuration must be editable without code deployment
- Data validation pipeline runs before any KPI calculation
- Daily and hourly rollup tables reduce query cost
- Old data is archived to a cold storage layer based on configurable time windows

---

### 6.8 Logging & Audit Service
**Tags:** Core, Analytics
**Stack:** Laravel Monolog, SQL Server, ELK Stack, Sentry, Laravel Scheduler

**Purpose:** Captures structured logs, immutable audit trails, error tracking, and ensures compliance across the platform.

| Function ID | Name | Priority | Description |
|-------------|------|----------|-------------|
| LG-01 | Structured Application Logs | Must | JSON-formatted logs with request_id and user_id for every operation |
| LG-02 | Immutable Audit Trail (fn 42) | Must | Immutable record: who changed what, when, before value, after value |
| LG-03 | Correlation ID Propagation | Must | Unified correlation ID that flows through all requests across all services |
| LG-04 | Error & Exception Tracking | Must | Tracks errors via Sentry with full stack traces |
| LG-05 | PII Masking / Redaction | Must | Masks sensitive data (phone numbers, email) before storage in logs |
| LG-06 | Retention + Archive Jobs | Must | 30-day hot storage; older logs archived to cold storage |
| LG-07 | Compliance Export | Should | Exports audit reports for legal compliance and external review |
| LG-08 | System Event Replay Feed | Nice to have | Serialized event feed for debugging or forensic investigation |
| LG-09 | DB Integrity Checkpoints | Should | Periodic database consistency and integrity checks |

**Key requirements:**
- All logs must be JSON-structured with `request_id` and `user_id`
- Audit model: `who / what / when / before_value / after_value` — writes are immutable
- PII masking is mandatory before any log is stored
- Immediate alerts for critical errors and sudden error rate spikes
- Retention policy: 30 days hot + configurable cold archive

---

## 7. NON-FUNCTIONAL REQUIREMENTS

### 7.1 Performance
- GPS location updates: received and broadcast within 5 seconds
- API response time: < 200ms for read operations under normal load
- Route optimization calculation: < 30 seconds for up to 100 stops
- Dashboard load time: < 3 seconds

### 7.2 Availability
- System uptime target: 99.5%
- Offline mode for Driver App: full functionality with local storage sync when connectivity resumes

### 7.3 Security
- All passwords hashed using `Hash::make()` (bcrypt)
- All API endpoints protected by Sanctum tokens
- Role-based access enforced via middleware on every route
- PII (names, phone numbers, email, GPS data) masked in logs
- Signed URLs for customer tracking links with expiry
- Rate limiting on all public and auth endpoints
- Failed login attempts logged and trigger lockout after N attempts

### 7.4 Scalability
- Redis Pub/Sub used for distributing real-time updates across multiple server instances
- Analytics queries isolated to a read replica to protect the OLTP database
- Queue workers can be scaled horizontally via Redis queues

### 7.5 Data Integrity
- Order state machine prevents invalid status transitions
- All manual overrides versioned and logged before/after
- All mutation endpoints are idempotent where applicable
- Database archiving job moves old data to archive tables to maintain performance

### 7.6 Compliance
- Audit trail is immutable and exportable for legal review
- Data retention policy: 30 days hot, longer in cold archive
- All system changes traceable to a specific user and timestamp

---

## 8. DATA MODELS & KEY ENTITIES

### Core Entities

**User**
```
id, name, email, phone, password_hash, role (driver|dispatcher|fleet_manager|mechanic),
employee_id, license_type, status (active|inactive), created_at, updated_at
```

**Vehicle**
```
id, plate_number, type (light|heavy|refrigerated), max_weight_kg, max_volume_m3,
odometer_km, status (available|in_service|out_of_service), market_value, created_at
```

**Order**
```
id, customer_name, customer_phone, delivery_address, lat, lng, weight_kg, volume_m3,
payment_type (prepaid|COD), cod_amount, status (pending|in_transit|delivered|returned|failed),
failure_reason, priority (normal|express), promised_window_start, promised_window_end,
route_id, driver_id, created_at
```

**Route**
```
id, vehicle_id, driver_id, status (planned|active|completed), shift (morning|evening),
total_distance_km, total_stops, started_at, completed_at, version
```

**RouteStop**
```
id, route_id, order_id, sequence, eta, actual_arrival, departure_at, status
```

**GPSPing**
```
id, driver_id, vehicle_id, lat, lng, speed_kmh, accuracy_m, recorded_at
```

**ProofOfDelivery**
```
id, order_id, driver_id, signature_url, photo_url, lat, lng, delivered_at, customer_name
```

**MaintenanceWorkOrder**
```
id, vehicle_id, mechanic_id, type (routine|emergency|breakdown),
status (open|assigned|in_progress|resolved|closed), description,
repair_cost, opened_at, resolved_at
```

**SparePart**
```
id, name, category, unit_price, stock_quantity, minimum_stock, reorder_level
```

**AuditLog**
```
id, user_id, action, entity_type, entity_id, old_value (JSON), new_value (JSON),
ip_address, user_agent, created_at
```

**Notification**
```
id, user_id, channel (push|sms|email), event_type, payload (JSON),
status (pending|sent|delivered|failed), sent_at, delivered_at
```

---

## 9. SYSTEM FLOWS (END-TO-END)

### Flow A: Daily Dispatch Cycle
```
1. Dispatcher logs into Dashboard
2. Imports orders via CSV file (fn 39)
3. System validates and creates order records
4. System clusters orders geographically (fn 02)
5. System optimizes stop sequence (fn 06)
6. System checks load capacity for each vehicle (fn 03)
7. Dispatcher reviews and adjusts priorities (fn 01)
8. Dispatcher assigns driver + vehicle (fn 08 — license check)
9. Driver receives route notification on Driver App
10. Driver completes pre-trip inspection (fn 12)
11. Driver starts route — GPS stream begins (RT-02)
12. Dispatcher monitors ETAs in real time (fn 10)
13. Customer receives tracking link (fn 33) and SMS/Push updates (fn 35)
14. At 500m distance: auto proximity alert sent to customer (fn 20)
15. Driver scans parcel QR at customer door (fn 17)
16. Customer signs on driver's screen (fn 13)
17. Order marked as "Delivered" — COD logged if applicable (fn 18)
18. Customer receives feedback request (fn 36)
19. Performance score updated for driver (fn 22)
```

### Flow B: Failed Delivery
```
1. Driver cannot deliver (customer absent, wrong address, etc.)
2. Driver selects failure reason from taxonomy (fn 12 in OM service)
3. Order status → "Returned"
4. RTB workflow activates (fn 21)
5. Parcel returned to warehouse
6. Retry scheduled with exponential backoff (OM-10)
```

### Flow C: Vehicle Breakdown During Route
```
1. Driver reports breakdown via Incident Reporting (fn 15)
2. Driver's GPS location captured automatically
3. Dispatcher receives immediate alert
4. Dynamic Rerouting activates (fn 04) — remaining orders redistributed
5. ETAs recalculated for all affected customers (fn 05)
6. Fleet Manager dispatches nearest mechanic (fn 32)
7. Vehicle status set to "out_of_service" (fn 25)
8. Vehicle locked from new assignments across the system (RT-08)
```

### Flow D: Maintenance Cycle
```
1. Odometer alert fires at threshold (fn 23 / MT-01)
2. Fleet Manager creates maintenance work order (MT-02)
3. Mechanic assigned to work order (fn 30 / MT-03)
4. Vehicle locked from dispatch (fn 25 / MT-04)
5. Mechanic records parts used; inventory deducted (fn 31 / MT-05)
6. Repair logged with full part replacement lifecycle (fn 26 / MT-06)
7. Cost-to-value analysis runs (fn 29 / MT-08)
   → If repair_cost / market_value > 0.40 → "Recommend Replacement" flag
8. Work order closed; vehicle status → "available"
9. Vehicle reappears in dispatch system
```

---

## 10. GLOSSARY

| Term | Definition |
|------|-----------|
| ETA | Estimated Time of Arrival — calculated per delivery stop |
| POD | Proof of Delivery — includes digital signature + photo |
| COD | Cash on Delivery — customer pays driver in cash on receipt |
| RTB | Return to Base — workflow for failed/undeliverable orders |
| RBAC | Role-Based Access Control — permission system by user role |
| TSP | Travelling Salesman Problem — algorithm used for route optimization |
| Safe Drop | Leaving a parcel at a location without the customer present (photo required) |
| Geofence | A virtual geographic boundary; triggers events when entered or exited |
| Heartbeat | Periodic GPS ping from the Driver App confirming connectivity |
| KPI | Key Performance Indicator — measurable metric for system/driver performance |
| OLTP | Online Transaction Processing — the primary SQL Server database |
| Dead-Letter Queue | A queue for messages that failed all retry attempts |
| PII | Personally Identifiable Information — data that must be masked in logs |
| Reverb | Laravel's built-in WebSocket server used for real-time broadcasting |
| Sanctum | Laravel's API authentication package used for token-based auth |
| Work Order | A maintenance ticket assigned to a mechanic for a specific vehicle |
| Clustering | Grouping geographically close delivery points for efficient routing |
| Dispatch | The process of assigning routes and drivers to orders |
| Shift Transition | Handoff of a route from one driver to another mid-shift |
| Replay Feed | A log of all system events in order, usable for debugging |

---

> **END OF DOCUMENT**
> Total functions documented: 42 named + 7 new identified = 49 total
> Total apps: 4 frontend + 1 backend layer (8 microservices)
> Last updated: 2025 — Fleet Tracking Platform v1.0