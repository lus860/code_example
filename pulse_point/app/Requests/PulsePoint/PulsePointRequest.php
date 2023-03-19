<?php

namespace App\Http\Requests\PulsePoint;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PulsePointRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'productivity' => 'nullable|integer|between:0,5',
            'feeling' => 'nullable|integer|between:0,5',
            'description' => 'max:500',
        ];
    }
}
