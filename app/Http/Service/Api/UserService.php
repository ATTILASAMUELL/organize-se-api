<?php

namespace App\Http\Service\Api;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Http\DTO\Api\UserDTO;


class UserService 
{
    public function register(UserDTO $userDTO)
    {
        try {
            $userModel = $this->userModel();
            $user = $userModel->create($userDTO->array());
            $user->sendEmailVerificationNotification();
    
            return $user;
        } catch (\Exception $e) {
           return false;      
        }
    }

    private function userModel()
    {
        return new User();
    }
    
}