<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'fulfillment' => ['required', 'in:delivery,takeaway'],
            'address' => ['required_if:fulfillment,delivery', 'nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cod,vietqr'],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name' => 'họ tên',
            'phone' => 'số điện thoại',
            'fulfillment' => 'phương thức nhận hàng',
            'address' => 'địa chỉ giao hàng',
            'payment_method' => 'phương thức thanh toán',
        ];
    }
}
