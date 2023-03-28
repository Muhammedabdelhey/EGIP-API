<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class PatientRequest extends FormRequest
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
            case 'POST': {
                    return [
                        'name' => 'required|string|between:2,100',
                        'email' => 'required|string|email|max:100|unique:users',
                        'password' => 'required|string|confirmed|min:6',
                        'Stage' => 'required|integer',
                        'address' => 'required|string',
                        'birth_date' => 'required',
                        'phone' => 'required',
                        'gender' => 'required',
                        'caregiver_id' => 'required|exists:App\Models\Caregiver,id',
                    ];
                }
            case 'PUT': {
                    $id = $this->route('patient_id');
                    $patient = Patient::find($id);
                    if (!$patient) {
                        throw new HttpResponseException(responseJson(401, '', 'this Pateint_id not found'));
                    }
                    return [
                        'name' => 'required|string|between:2,100',
                        'email' => 'required|string|email|max:100|unique:users,email,' . $patient->user->id,
                        'Stage' => 'required|integer',
                        'address' => 'required|string',
                        'birth_date' => 'required',
                        'gender' => 'required',
                        'phone' => 'required',
                    ];
                }
            default:
                break;
        }
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(responseJson(401, "", $validator->errors()));
    }
}
