<?php
namespace Stario\Icenter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Stario\Icenter\Contracts\Role as RoleContract;
use Stario\Icenter\Exceptions\GuardDoesNotMatch;
use Stario\Icenter\Exceptions\RoleAlreadyExists;
use Stario\Icenter\Exceptions\RoleDoesNotExist;
use Stario\Icenter\Models\Permission;
use Stario\Icenter\Traits\HasPermissions;
use Stario\Icenter\Traits\RefreshesPermissionCache;

class Role extends Model implements RoleContract
{
    use HasPermissions;
    use RefreshesPermissionCache;

    public $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        // $this->setTable('roles');
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        if (static::where('name', $attributes['name'])->where('guard_name', $attributes['guard_name'])->first()) {
            throw RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions'
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            'model_has_roles',
            'role_id',
            'model_id'
        );
    }

    /**
     * Find a role by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role|\Spatie\Permission\Models\Role
     *
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     */
    public static function findByName(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? config('auth.defaults.guard');

        $role = static::where('name', $name)->where('guard_name', $guardName)->first();

        if (!$role) {
            throw RoleDoesNotExist::create($name);
        }

        return $role;
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Permission $permission
     *
     * @return bool
     *
     * @throws \Spatie\Permission\Exceptions\GuardMismatch
     */
    public function hasPermissionTo($permission): bool
    {
        if (is_string($permission)) {
            $permission = app(Permission::class)->findByName($permission, $this->getDefaultGuardName());
        }

        if (!$this->getGuardNames()->contains($permission->guard_name)) {
            throw GuardDoesNotMatch::create($permission->guard_name, $this->getGuardNames());
        }

        return $this->permissions->contains('id', $permission->id);
    }
}
