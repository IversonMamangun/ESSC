<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'recipient_name' => $this->recipient_name,
            'recipient_number' => $this->recipient_number,
            'region' => $this->region,
            'province' => $this->province,
            'city' => $this->city,
            'barangay' => $this->barangay,
            'street' => $this->street,
            'postal_code' => $this->postal_code,
            'landmark' => $this->landmark,
            'is_default' => $this->is_default,
        ];
    }
}
