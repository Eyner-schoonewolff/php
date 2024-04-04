<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class Usuario extends FormRequest
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


    public function messages(): array
    {
        return
            [
                'required' => ' el campo :attribute es requerido.',
                'correo.unique' => 'el correo que usted quiere registrar ya no se encuentra disponible.',
                'date' => 'la fecha debe ser tipo 2003-03-11 Anio-mes-dia'
            ];
    }
    public function rules(): array
    {

        $regla =
            $this->isMethod('put') ?
            [
                'required',
                'email',
                Rule::unique('usuario', 'correo')->ignore($this->user()->id),
            ]
            :
            'required|email|unique:usuario,correo';


        return [
            'nombre' => 'required|string|min:1|max:50',
            'correo' => $regla,
            'contrasenia' => 'required|string|min:6',
            'direccion' => 'required|string|max:50',
            'telefono' => 'required|string|max:11',
            'fecha_nacimiento' => 'required|date'
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
