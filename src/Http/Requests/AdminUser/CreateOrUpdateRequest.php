<?php

namespace Moell\Mojito\Http\Requests\AdminUser;


use Illuminate\Foundation\Http\FormRequest;
use Moell\Mojito\AdminUserFactory;

class CreateOrUpdateRequest extends FormRequest
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
     * @author moell<moel91@foxmail.com>
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:255'
        ];

        switch ($this->method()) {
            case 'POST':
                $rules['password'] = 'required|min:6|max:32';
                $rules['email'] = 'required|email|unique:' . AdminUserFactory::adminUser()->getTable();
                break;
            case 'PATCH':
                $rules['password'] = 'min:8|max:32';
                break;
        }

        return $rules;
    }
}