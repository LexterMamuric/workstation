<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ComponentMake;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComponentMakePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the componentMake can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list componentmakes');
    }

    /**
     * Determine whether the componentMake can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentMake  $model
     * @return mixed
     */
    public function view(User $user, ComponentMake $model)
    {
        return $user->hasPermissionTo('view componentmakes');
    }

    /**
     * Determine whether the componentMake can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create componentmakes');
    }

    /**
     * Determine whether the componentMake can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentMake  $model
     * @return mixed
     */
    public function update(User $user, ComponentMake $model)
    {
        return $user->hasPermissionTo('update componentmakes');
    }

    /**
     * Determine whether the componentMake can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentMake  $model
     * @return mixed
     */
    public function delete(User $user, ComponentMake $model)
    {
        return $user->hasPermissionTo('delete componentmakes');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentMake  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete componentmakes');
    }

    /**
     * Determine whether the componentMake can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentMake  $model
     * @return mixed
     */
    public function restore(User $user, ComponentMake $model)
    {
        return false;
    }

    /**
     * Determine whether the componentMake can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentMake  $model
     * @return mixed
     */
    public function forceDelete(User $user, ComponentMake $model)
    {
        return false;
    }
}
