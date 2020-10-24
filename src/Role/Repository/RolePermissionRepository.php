<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\RolePermission;

/**
 * Class RolePermissionRepository
 *
 * @package Ares\Role\Repository
 */
class RolePermissionRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_ROLE_PERMISSION_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_ROLE_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = RolePermission::class;

    /**
     * @param int $permissionId
     * @param array $roleIds
     *
     * @return bool
     */
    public function isPermissionAssigned(int $permissionId, array $roleIds): bool
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select('id')
            ->where('permission_id', $permissionId)
            ->whereIn('role_id', $roleIds)
            ->limit(1);

        $result = $this->getList($searchCriteria)->toArray();

        return count($result) > 0;
    }
}
