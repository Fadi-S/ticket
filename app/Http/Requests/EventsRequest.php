<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class EventsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("events.create") || auth()->user()->can("events.edit");
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
            "deacons_number" => "nullable|numeric|min:0",
            "description" => "required",
            "start" => "required|date",
            "end" => "required|date|after:start",
            "overload" => "required|numeric|min:0|max:100",
            "published_at" => "required|date|before:start",
            'type_id' => 'nullable',
        ];
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();

        $data['published_at'] = Carbon::createFromFormat("Y-m-d h:i A", $this->published_at);

        $date = Carbon::createFromFormat("Y-m-d", $this->date);

        $data['start'] = $date->copy()
            ->hour(explode(":", $this->start_time)[0])
            ->minute(explode(":", $this->start_time)[1]);

        $data['end'] = $date->copy()
            ->hour(explode(":", $this->end_time)[0])
            ->minute(explode(":", $this->end_time)[1]);

        if($data['end']->lessThan($data['start']))
            $data['end']->addDay();

        $data['overload'] = $data['overload'] / 100;

        $this->getInputSource()->replace($data);
        return parent::getValidatorInstance();
    }
}
