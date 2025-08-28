<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\AuthorizationException;

class ProductPolicy
{
    use HandlesAuthorization;

    // Other policy methods...

    public function view(User $user)
    {
        if ($user->tokenCan('user') || $user->tokenCan('seller') || $user->tokenCan('admin')) {
            return true;
        }

        throw new AuthorizationException('You do not have the required scope to view products.');
    }

    public function create(User $user)
    {
        if ($user->tokenCan('seller') || $user->tokenCan('admin')) {
            return true;
        }

        throw new AuthorizationException('You do not have the required scope to create a product.');
    }

    public function update(User $user)
    {
        if ($user->tokenCan('seller') || $user->tokenCan('admin')) {
            return true;
        }

        throw new AuthorizationException('You do not have the required scope to update this product.');
    }

    public function delete(User $user)
    {
        if ($user->tokenCan('admin')) {
            return true;
        }

        throw new AuthorizationException('You do not have the required scope to delete this product.');
    }
}
