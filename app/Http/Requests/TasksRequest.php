<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TasksRequest extends FormRequest
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
        if($this->repeat_typeID==3){
           return[
                'days'=>'required|array|min:1',
                'name'=>'required',
                'time'=>'required|date_format:H:i:s',
                'repeats_per_day'=>'required',
                'start_date'=>'required|date_format:Y-m-d',
                'end_date'=>'required|date_format:Y-m-d',
                'repeat_typeID' => 'required|integer|exists:App\Models\RepeatType,id',
                'patient_id' => 'required|integer|exists:App\Models\Patient,id'
           ];
        }
        return [
            'name'=>'required',
            'time'=>'required|date_format:H:i:s',
            'repeats_per_day'=>'required',
            'start_date'=>'required|date_format:Y-m-d',
            'end_date'=>'required|date_format:Y-m-d',
            'repeat_typeID' => 'required|integer|exists:App\Models\RepeatType,id',
            'patient_id' => 'required|integer|exists:App\Models\Patient,id'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(responseJson(401, "", $validator->errors()));
    }
}
