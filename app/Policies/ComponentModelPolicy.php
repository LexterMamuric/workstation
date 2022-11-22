<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ComponentModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComponentModelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the componentModel can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list componentmodels');
    }

    /**
     * Determine whether the componentModel can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentModel  $model
     * @return mixed
     */
    public function view(User $user, ComponentModel $model)
    {
        return $user->hasPermissionTo('view componentmodels');
    }

    /**
     * Determine whether the componentModel can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create componentmodels');
    }

    /**
     * Determine whether the componentModel can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentModel  $model
     * @return mixed
     */
    public function update(User $user, ComponentModel $model)
    {
        return $user->hasPermissionTo('update componentmodels');
    }

    /**
     * Determine whether the componentModel can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentModel  $model
     * @return mixed
     */
    public function delete(User $user, ComponentModel $model)
    {
        return $user->hasPermissionTo('delete componentmodels');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentModel  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete componentmodels');
    }

    /**
     * Determine whether the componentModel can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentModel  $model
     * @return mixed
     */
    public function restore(User $user, ComponentModel $model)
    {
        return false;
    }

    /**
     * Determine whether the componentModel can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentModel  $model
     * @return mixed
     */
    public function forceDelete(User $user, ComponentModel $model)
    {
        return false;
    }
}
