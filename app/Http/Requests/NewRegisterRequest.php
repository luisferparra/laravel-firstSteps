<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewRegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>array('required','regex:/[a-zA-Z ]+/'),
            'surname'=>['required'],
            'age'=>['required','integer','max:99','min:18'],
            'gender'=>array('required'),
            'policy_id'=>['required','exists:policies,id']
        ];
    }
}
