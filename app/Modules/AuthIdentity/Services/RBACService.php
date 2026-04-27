<?php

/**
 * @file RBACService.php
 * @description محرك التحكم في الوصول بناءً على الأدوار — Role & Permission management
 * @module AuthIdentity
 * @author Team Leader (Khalid)
 */

namespace App\Modules\AuthIdentity\Services;

use App\Modules\AuthIdentity\Repositories\RoleRepository;
use App\Modules\AuthIdentity\Repositories\UserRepository;
use App\Modules\LoggingAudit\Services\AuditService;
use Exception;

class RBACService
{
    protected RoleRepository $roleRepository;
    protected UserRepository $userRepository;
    protected AuditService   $auditService;

    public function __construct(
        RoleRepository $roleRepository,
        UserRepository $userRepository,
        AuditService   $auditService
    ) {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
        $this->auditService   = $auditService;
    }

    /**
     * جلب جميع الأدوار مع Pagination
     */
    public function getAllRoles(int $perPage = 15)
    {
        return $this->roleRepository->paginate($perPage);
    }

    /**
     * جلب دور واحد مع صلاحياته
     */
    public function getRoleById(int $id)
    {
        $role = $this->roleRepository->findByIdOrFail($id);
        $role->load('permissions');
        return $role;
    }

    /**
     * إنشاء دور جديد مع تعيين صلاحياته
     */
    public function createRole(array $data)
    {
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $role = $this->roleRepository->create($data);

        if (!empty($permissions)) {
            $role->permissions()->sync($permissions);
        }

        $this->auditService->log('created', 'role', $role->role_id, afterState: $data, module: 'AuthIdentity');

        return $role->load('permissions');
    }

    /**
     * تحديث دور وصلاحياته
     */
    public function updateRole(int $id, array $data)
    {
        $before = $this->roleRepository->findByIdOrFail($id)->toArray();

        $permissions = $data['permissions'] ?? null;
        unset($data['permissions']);

        $this->roleRepository->update($id, $data);

        $role = $this->roleRepository->findByIdOrFail($id);

        if ($permissions !== null) {
            $role->permissions()->sync($permissions);
        }

        $this->auditService->log('updated', 'role', $id, beforeState: $before, afterState: $data, module: 'AuthIdentity');

        return $role->load('permissions');
    }

    /**
     * حذف دور (يمنع حذف System Roles)
     */
    public function deleteRole(int $id): bool
    {
        $role = $this->roleRepository->findByIdOrFail($id);

        if ($role->is_system_role) {
            throw new Exception('لا يمكن حذف الأدوار النظامية');
        }

        $before = $role->toArray();

        // Detach all users and permissions before deleting
        $role->users()->detach();
        $role->permissions()->detach();

        $result = $this->roleRepository->delete($id);

        $this->auditService->log('deleted', 'role', $id, beforeState: $before, module: 'AuthIdentity');

        return $result;
    }

    /**
     * جلب الأدوار غير النظامية
     */
    public function getNonSystemRoles()
    {
        return $this->roleRepository->findAllBy(['is_system_role' => false]);
    }

    /**
     * التحقق من صلاحية معينة للمستخدم (role-based check)
     */
    public function hasPermission(int $userId, string $permissionSlug): bool
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return false;
        }

        // Check direct user permissions
        if ($user->permissions()->where('slug', $permissionSlug)->exists()) {
            return true;
        }

        // Check role-based permissions
        return $user->roles()
            ->whereHas('permissions', fn ($q) => $q->where('slug', $permissionSlug))
            ->exists();
    }

    /**
     * تعيين دور لمستخدم (لا يزيل الأدوار الحالية)
     */
    public function assignRoleToUser(int $userId, int $roleId): bool
    {
        $user = $this->userRepository->findByIdOrFail($userId);
        $role = $this->roleRepository->findByIdOrFail($roleId);

        $user->roles()->syncWithoutDetaching([$roleId]);

        $this->auditService->log(
            'role_assigned', 'user', $userId,
            afterState: ['role_id' => $roleId, 'role_name' => $role->name],
            module: 'AuthIdentity'
        );

        return true;
    }
}
