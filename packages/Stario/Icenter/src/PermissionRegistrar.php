<?php

namespace Stario\Icenter;

use Exception;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Collection;
use Stario\Icenter\Contracts\Permission;

class PermissionRegistrar
{
    /** @var \Illuminate\Contracts\Auth\Access\Gate */
    protected $gate;

    /** @var \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    /** @var \Illuminate\Contracts\Logging\Log */
    protected $logger;

    /** @var string */
    protected $cacheKey = 'wemesh.permission.cache';

    public function __construct(Gate $gate, Repository $cache, Log $logger)
    {
        $this->gate = $gate;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function registerPermissions(): bool
    {
        try {
            $this->getPermissions()->map(function ($permission) {
                $this->gate->define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });

            return true;
        } catch (Exception $exception) {
            if ($this->shouldLogException()) {
                $this->logger->alert(
                    "Could not register permissions because {$exception->getMessage()}" . PHP_EOL .
                    $exception->getTraceAsString()
                );
            }

            return false;
        }
    }

    public function forgetCachedPermissions()
    {
        $this->cache->forget($this->cacheKey);
    }

    public function getPermissions(): Collection
    {
        return $this->cache->remember($this->cacheKey, 1440, function () {
            return app(Permission::class)->with('roles')->get();
        });
    }

    protected function shouldLogException(): bool
    {
        return true;
    }
}
