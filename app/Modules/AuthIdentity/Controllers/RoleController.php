<?php

/**
 * @file: RoleController.php
 * @description: متحكم الأدوار والصلاحيات - RBAC (AUTH-03 / fn37)
 * @module: AuthIdentity
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AuthIdentity\Services\RBACService;
use App\Modules\AuthIdentity\Requests\RoleRequest;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    protected RBACService $rbacService;

    public function __construct(RBACService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    /**
     * جلب جميع الأدوار
     * GET /api/v1/roles
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // TODO: Implement get all roles
        // 1. $roles = $this->rbacService->getAllRoles(request('per_page', 15))
        // 2. Return roles as JSON
        // 3. Catch Exception
    }

    /**
     * جلب دور واحد مع صلاحياته
     * GET /api/v1/roles/{id}
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        // TODO: Implement get role by ID
        // 1. $role = $this->rbacService->getRoleById($id)
        // 2. Return role with permissions
        // 3. Catch ModelNotFoundException → 404
    }

    /**
     * إنشاء دور جديد
     * POST /api/v1/roles
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        // TODO: Implement create role
        // 1. $role = $this->rbacService->createRole($request->validated())
        // 2. Return: response()->json(['success' => true, 'message' => 'تم إنشاء الدور', 'data' => $role], 201)
        // 3. Catch Exception
    }

    /**
     * تحديث دور
     * PUT /api/v1/roles/{id}
     * @param int $id
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function update(int $id, RoleRequest $request): JsonResponse
    {
        // TODO: Implement update role
        // 1. $role = $this->rbacService->updateRole($id, $request->validated())
        // 2. Return updated role
        // 3. Catch Exception
    }

    /**
     * حذف دور
     * DELETE /api/v1/roles/{id}
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        // TODO: Implement delete role
        // 1. $this->rbacService->deleteRole($id)
        // 2. Return success response
        // 3. Catch Exception (منع حذف System Roles)
    }

    /**
     * جلب الأدوار غير النظامية
     * GET /api/v1/roles/type/non-system
     * @return JsonResponse
     */
    public function nonSystemRoles(): JsonResponse
    {
        // TODO: return response()->json(['success' => true, 'data' => $this->rbacService->getNonSystemRoles()], 200);
    }
}
