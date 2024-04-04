<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class RequestEstado extends FormRequest
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
            'estado' => 'required|numeric|in:0,1',
        ];
    }
    public function messages()
    {
        return [
            'estado.required' => 'El campo estado es obligatorio.',
            'estado.numeric' => 'El campo estado debe ser numérico.',
            'estado.in' => 'El campo estado debe ser 0 o 1.',
        ];
    }

    protected function failedValidation(Validator $validator){

        $error = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(
                $error,
                404
            )
        );

    }
}
