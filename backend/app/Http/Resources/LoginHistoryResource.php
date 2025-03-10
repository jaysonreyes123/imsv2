<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user" => $this->users_->firstname,
            "ipaddress" => $this->ipaddress,
            "signin" => $this->signin == null ?  "---" : $this->signin,
            "signout" => $this->signout == null ?  "---" : $this->signout,
            "status" => $this->status == 1 ? "Signed in" : "Signed out"
        ];
    }
}
