<?php

namespace App\Http\Requests\Goals\Api;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MetricKeyResultSettingAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    private $company;

    public function authorize(Request $request)
    {
        $this->company = Company::where('api_token', $request->api_token)->first();
        return $this->company;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        return [
            'start_value' => 'numeric',
            'max_value' => 'numeric',
            'decimal' => Rule::in([0, 1, 2]),
            'id_metric' => 'required|integer|exists:metrics,id',
        ];
    }
}
