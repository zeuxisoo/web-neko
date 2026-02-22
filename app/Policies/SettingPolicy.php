<?php

namespace App\Policies;

use App\Models\User;

class SettingPolicy
{
    /**
     * Determine if user can view any settings
     * All authenticated users can view settings
     */
    public function viewAny(User $user): bool {
        return true;
    }

    /**
     * Determine if user can view the settings
     * All authenticated users can view settings
     */
    public function view(User $user): bool {
        return true;
    }

    /**
     * Determine if user can update settings
     * Only admins can update settings
     */
    public function update(User $user): bool {
        return (bool) $user->is_admin === true;
    }

    /**
     * Determine if user can clear settings cache
     * Only admins can clear cache settings
     */
    public function clear(User $user): bool {
        return (bool) $user->is_admin === true;
    }
}
