<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function addUser(array $data)
    {
        return $this->user->create($data);
    }
    public function updateUser($id, array $data)
    {
        return $this->user->whereId($id)->update($data);
    }
    public function deleteUser($id)
    {
        return $this->user->destroy($id);
    }
}
