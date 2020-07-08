<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class MassesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("masses.create") || auth()->user()->can("masses.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "number_of_places" => "required|numeric|min:1",
            "time" => "required|date",
        ];
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();

        $data['time'] = Carbon::createFromFormat("d/m/Y h:i A", $this->date);

        $this->getInputSource()->replace($data);
        return parent::getValidatorInstance();
    }
}
