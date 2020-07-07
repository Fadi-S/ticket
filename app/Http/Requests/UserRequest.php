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
                "name" => "required",
                "email" => "required|email|unique:users",
                "username" => "required|unique:users",
                "password" => "min:6|max:32",
            ];
        else if($this->method() == "PATCH") {

            $rules = [
                'name' => "required",
                'username' => Rule::unique('users')->ignore($this->route("user")->id),
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
