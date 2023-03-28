<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MemoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|between:2,100',
                    'description' => 'required|string',
                    'type' => 'required|integer',
                    'patient_id' => 'required|integer|exists:App\Models\Patient,id'
                ];
            case 'PUT':
                return [
                    'name' => 'required|string|between:2,100',
                    'description' => 'required|string',
                    'type' => 'required|integer',
                ];
            default:
                break;
        }
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(responseJson(401, "", $validator->errors()));
    }
}