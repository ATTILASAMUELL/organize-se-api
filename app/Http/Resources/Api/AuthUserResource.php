<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Mensagem de sucesso personalizada.
     *
     * @var string
     */
    protected $successMessage;

    /**
     * Mensagem de sucesso personalizada.
     *
     * @var string
     */
    protected $token;

    /**
     * Construtor da classe.
     *
     * @param  mixed  $resource
     * @param  string  $successMessage
     * @return void
     */
    public function __construct($resource, $successMessage = 'User created successfully',$token = null)
    {
        parent::__construct($resource);
        $this->successMessage = $successMessage;
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {

        $token = !empty($this->token) ? $this->token : auth('api')->login($this->resource);

        return [
            'status' => 'success',
            'message' => $this->successMessage,
            'user' => parent::toArray($request),
            'authorization' => [
                'token' => $token,
                'type' => 'Bearer',
            ],
        ];
    }
}
