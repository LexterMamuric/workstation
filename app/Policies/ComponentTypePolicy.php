<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ComponentType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComponentTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the componentType can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list componenttypes');
    }

    /**
     * Determine whether the componentType can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentType  $model
     * @return mixed
     */
    public function view(User $user, ComponentType $model)
    {
        return $user->hasPermissionTo('view componenttypes');
    }

    /**
     * Determine whether the componentType can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create componenttypes');
    }

    /**
     * Determine whether the componentType can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentType  $model
     * @return mixed
     */
    public function update(User $user, ComponentType $model)
    {
        return $user->hasPermissionTo('update componenttypes');
    }

    /**
     * Determine whether the componentType can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentType  $model
     * @return mixed
     */
    public function delete(User $user, ComponentType $model)
    {
        return $user->hasPermissionTo('delete componenttypes');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentType  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete componenttypes');
    }

    /**
     * Determine whether the componentType can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentType  $model
     * @return mixed
     */
    public function restore(User $user, ComponentType $model)
    {
        return false;
    }

    /**
     * Determine whether the componentType can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentType  $model
     * @return mixed
     */
    public function forceDelete(User $user, ComponentType $model)
    {
        return false;
    }
}
