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
            "start" => "required|date",
            "end" => "required|date",
        ];
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();

        $date = Carbon::createFromFormat("d/m/Y", $this->date);

        $data['start'] = $date->copy()->hour(explode(":", $this->start_time)[0])->minute(explode(":", $this->start_time)[1]);

        $data['end'] = $date->copy()->hour(explode(":", $this->end_time)[0])->minute(explode(":", $this->end_time)[1]);

        $this->getInputSource()->replace($data);
        return parent::getValidatorInstance();
    }
}
