<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{AuthLoginRequest,AuthRegisterRequest};
use App\Http\DTO\Api\UserDTO;
use App\Http\Service\Api\UserService;
use App\Http\Resources\Api\AuthUserResource;
use App\Http\Traits\Api\ApiResponseTrait;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(AuthRegisterRequest $request){

        $userDto = $this->convertRequestInDTO($request);
        $userService = $this->userService();
        $userRegister = $userService->register($userDto);

        if($userRegister instanceof User) {
            return new AuthUserResource($userRegister);
        }

        return $this->errorResponse();
    }

    public function login(AuthLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return $this->errorResponse('Unauthorized!', 401);
        }

        $user = Auth::guard('api')->user();

        return new AuthUserResource($user, 'User logged in successfully');
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return $this->successResponse();
    }


    public function refresh()
    {
        $user = Auth::guard('api')->user();

        return new AuthUserResource($user, 'User refreshed successfully', Auth::guard('api')->refresh());
    }

    /**
     * Register User
     *
     * @param AuthRegisterRequest $request
     * @return UserDTO
     */
    private function convertRequestInDTO(AuthRegisterRequest $registrationRequest)
    {
        $data = $registrationRequest->validated();

        // Agora você pode acessar os dados do formulário
        $name = $data['name'];
        $email = $data['email'];
        $password = Hash::make($data['password']);

        $userDTO = new UserDTO($name,$email,$password);

        return $userDTO;
    }
    
    /**
     * Register User
     * @return UserService
     */
    private function userService()
    {
        return new UserService(); 
    } 
}