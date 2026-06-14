<?php

namespace App\Policies;

use App\Models\SampahLiar;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SampahLiarPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SampahLiar $sampahLiar): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'masyarakat';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SampahLiar $sampahLiar): bool
    {
        return $user->id === $sampahLiar->pengguna_id && $sampahLiar->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SampahLiar $sampahLiar): bool
    {
        return $user->id === $sampahLiar->pengguna_id && $sampahLiar->status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SampahLiar $sampahLiar): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SampahLiar $sampahLiar): bool
    {
        return false;
    }
}
