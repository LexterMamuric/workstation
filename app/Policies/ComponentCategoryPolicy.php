<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ComponentCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComponentCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the componentCategory can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list componentcategories');
    }

    /**
     * Determine whether the componentCategory can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentCategory  $model
     * @return mixed
     */
    public function view(User $user, ComponentCategory $model)
    {
        return $user->hasPermissionTo('view componentcategories');
    }

    /**
     * Determine whether the componentCategory can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create componentcategories');
    }

    /**
     * Determine whether the componentCategory can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentCategory  $model
     * @return mixed
     */
    public function update(User $user, ComponentCategory $model)
    {
        return $user->hasPermissionTo('update componentcategories');
    }

    /**
     * Determine whether the componentCategory can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentCategory  $model
     * @return mixed
     */
    public function delete(User $user, ComponentCategory $model)
    {
        return $user->hasPermissionTo('delete componentcategories');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentCategory  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete componentcategories');
    }

    /**
     * Determine whether the componentCategory can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentCategory  $model
     * @return mixed
     */
    public function restore(User $user, ComponentCategory $model)
    {
        return false;
    }

    /**
     * Determine whether the componentCategory can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ComponentCategory  $model
     * @return mixed
     */
    public function forceDelete(User $user, ComponentCategory $model)
    {
        return false;
    }
}
