<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class AutenticacionUsuario extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'correo' => 'required|email',
            'contrasenia' => 'required|min:6',
        ];
    }


    public function messages(): array
    {
        return
            [
                'correo.required' => 'El correo es obligatorio',
                'correo.email' => 'Debes proporcionar un correo (example@gmail.com)',
                'contrasenia.required' => 'la contrasenña es obligatorio',
                'contrasenia.min' => 'la contrasenña debe tener minimo 6 caracteres.',

            ];
    }

    protected function failedValidation(Validator $validator)
    {

        $error = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json($error, 400)
        );
    }


}
