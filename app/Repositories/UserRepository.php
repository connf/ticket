<?php

namespace App\Repositories;

use App\Controller\UserController;
use App\Models\User;
use Carbon\Carbon;

class UserRepository
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function find(int $id): ?User
    // public function find(int|string $id): ?User --- Could use this is we had UUIDs set up 
    {
        // We could override find with a findOrFail if this were a business case requirement
        // $user = User::findOrFail($id);
        $user = User::find($id);

        // Do anything else we may need to do business logic wise
        // if it were to be necessary - this is why a Repository can be a powerful tool
        // as an inbetween between the Task Code and Controller

        return $user;
    }

    /**
     * Maybe we want a find by field type search which we can override
     * This would let us pull records by email (as unique field) by default
     * or override this to pull where all last names are "this last name"
     */
    public function findBy(string $search, string $by = "email")
    {
        return User::where($by, $search)->get();
    }
}
