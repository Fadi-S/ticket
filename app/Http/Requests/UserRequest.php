<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard()->user()->can("users.create") || auth()->guard()->user()->can("users.edit");
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
            ];
        else if($this->method() == "PATCH") {

            $rules = [
                'name' => "required|regex:/[a-zA-Z ]+/",
                'username' => [
                    Rule::unique('users')->ignore($this->route("user")->id),
                    "regex:/^(?=[a-zA-Z0-9.]{2,32}$)(?!.*[_.]{2})[^_.].*[^_.]$/",
                ],
                'password' => "nullable|min:6|max:32",
                'email' => [
                    "required",
                    "email",
                    Rule::unique('users')->ignore($this->route("user")->id)
                ]
            ];
        }

        return $rules;

    }
}
