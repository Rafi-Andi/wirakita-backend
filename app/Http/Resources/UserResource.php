<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            "id" => $this->id,
            "username" => $this->username,
            "fullname" => $this->fullname,
            "class" => $this->class,
        ];

        if ($request->user() && $request->user()->id === $this->id) {
            $data['email'] = $this->email;
        }

        return $data;
    }
}
