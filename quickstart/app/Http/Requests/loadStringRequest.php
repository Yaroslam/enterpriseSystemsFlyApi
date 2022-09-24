<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class loadStringRequest extends FormRequest
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
        return [
            "action" => 'required|string',
            "date" => 'required|string|min:10',
            "time" => 'required|string|min:4',
            'flight' => 'required|integer',
            "from" => "required|string|size:3",
            "to" => "required|string|size:3",
            'aircraft' => 'required|integer', //добавить проверку на наличие в таблице
            'price' => 'required|integer',
            'status' => 'required|string|in:OK,CANCELED'
        ];
    }
}
