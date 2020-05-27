<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FootPrintRequest extends FormRequest
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
        $baseRules = [
            'activity'      => 'required | int | notIn:0',
            'activityType'      => 'required | in:'.implode(',', config('carbon.activityType')),
            'country_code'  => 'required'
        ];
        if (request()->get("activityType") === 'fuel') {
            return $baseRules + [
                'fuelType' => 'required| in:'.implode(',', config('carbon.fuelType'))
            ];
        } elseif (request()->get("activityType") === 'miles') {
            return $baseRules + [
            'mode'  => 'required| in:'.implode(',', config('carbon.mode'))
            ];
        } else {
            return $baseRules;
        }
    }
}
