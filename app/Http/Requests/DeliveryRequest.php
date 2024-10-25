<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:delivery_boys,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|max:20',
            'altNumber' => 'nullable|max:20',
            'gender' => 'required|in:male,female,other',
            'adhar' => 'required',
            // 'image' => 'required|image|mimes:jpg,jpeg,png',
            'drivingLicence' => 'required',
            'deliveryBoyType' => 'required',
            // 'deliveryBoyType' => 'required|string|in:restaurantDeliveryBoy, speedieDeliveryBoy',
            'locationId' => 'nullable',
            'vehicle_type' => 'nullable',
            'vehicle_number' => 'nullable',
        ];
    }
}
