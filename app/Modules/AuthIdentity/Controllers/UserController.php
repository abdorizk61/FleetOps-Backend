<?php

/**
 * @file: UserController.php
 * @description: متحكم المستخدمين - CRUD وتصفية حسب الأدوار
 * @module: AuthIdentity
 * @author: Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AuthIdentity\Services\UserService;
use App\Modules\AuthIdentity\Requests\UserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * جلب جميع المستخدمين (مع Pagination)
     * GET /api/v1/users
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // TODO: Implement get all users
        // 1. $users = $this->userService->getAllUsers(request('per_page', 15))
        // 2. Return: response()->json(['success' => true, 'data' => $users], 200)
        // 3. Catch Exception
    }

    /**
     * جلب مستخدم واحد
     * GET /api/v1/users/{id}
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        // TODO: Implement get user by ID
        // 1. $user = $this->userService->getUserById($id)
        // 2. Return user data
        // 3. Catch ModelNotFoundException → 404
    }

    /**
     * إنشاء مستخدم جديد
     * POST /api/v1/users
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        // TODO: Implement create user
        // 1. $user = $this->userService->createUser($request->validated())
        // 2. Return: response()->json(['success' => true, 'message' => 'تم إنشاء المستخدم بنجاح', 'data' => $user], 201)
        // 3. Catch Exception
    }

    /**
     * تحديث مستخدم
     * PUT /api/v1/users/{id}
     * @param int $id
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UserRequest $request): JsonResponse
    {
        // TODO: Implement update user
        // 1. $user = $this->userService->updateUser($id, $request->validated())
        // 2. Return updated user
        // 3. Catch Exception
    }

    /**
     * حذف مستخدم (Soft Delete)
     * DELETE /api/v1/users/{id}
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        // TODO: Implement delete user
        // 1. $this->userService->deleteUser($id)
        // 2. Return: response()->json(['success' => true, 'message' => 'تم حذف المستخدم'], 200)
        // 3. Catch Exception
    }

    /** GET /api/v1/users/active */
    public function active(): JsonResponse
    {
        // TODO: return response()->json(['success' => true, 'data' => $this->userService->getActiveUsers()], 200);
    }

    /** GET /api/v1/users/role/drivers */
    public function drivers(): JsonResponse
    {
        // TODO: return response()->json(['success' => true, 'data' => $this->userService->getDrivers()], 200);
    }

    /** GET /api/v1/users/role/dispatchers */
    public function dispatchers(): JsonResponse
    {
        // TODO: return response()->json(['success' => true, 'data' => $this->userService->getDispatchers()], 200);
    }

    /** GET /api/v1/users/role/fleet-managers */
    public function fleetManagers(): JsonResponse
    {
        // TODO: return response()->json(['success' => true, 'data' => $this->userService->getFleetManagers()], 200);
    }

    /** GET /api/v1/users/role/mechanics */
    public function mechanics(): JsonResponse
    {
        // TODO: return response()->json(['success' => true, 'data' => $this->userService->getMechanics()], 200);
    }
}
