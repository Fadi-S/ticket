<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard()->user()->can("admins.create") || auth()->guard()->user()->can("admins.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if($this->method() == "POST")
            $rules = [
                "name" => "required|regex:/[a-zA-Z ]+/",
                "email" => "required|email|unique:users",
                "username" => [
                    "required",
                    "unique:users",
                    "regex:/^(?=[a-zA-Z0-9.]{2,32}$)(?!.*[_.]{2})[^_.].*[^_.]$/"
                ],
                "password" => "min:6|max:32",
                "role" => "required",
            ];
        else if($this->method() == "PATCH") {

            $rules = [
                'name' => "required|regex:/[a-zA-Z ]+/",
                'username' => [
                    Rule::unique('users')->ignore($this->route("admin")->id),
                    "regex:/^(?=[a-zA-Z0-9.]{2,32}$)(?!.*[_.]{2})[^_.].*[^_.]$/",
                ],
                'password' => "nullable|min:6|max:32",
                "role" => "required",
                'email' => [
                    "required",
                    "email",
                    Rule::unique('users')->ignore($this->route("admin")->id)
                ]
            ];
        }

        return $rules;
    }
}
