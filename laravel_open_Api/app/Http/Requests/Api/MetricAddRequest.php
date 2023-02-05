<?php

namespace App\Http\Requests\Goals\Api;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MetricAddRequest extends FormRequest
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
            'name' => [
                'required',
                'max:32',
                Rule::unique('metrics')->where(function ($query) use ($request) {
                    $query->where('name', $request->name);
                    $query->where(function ($q) {
                        $q->where('id_company', $this->company->id);
                        $q->orWhere('id_company', null);
                    });

                    return $query;
                }),
            ],
        ];
    }
}
