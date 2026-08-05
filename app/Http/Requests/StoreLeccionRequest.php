<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeccionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación aplicadas a la petición HTTP.
     */
    public function rules(): array
    {
        return [
            'titulo'                => 'required|string|max:255',
            'descripcion'           => 'required|string|min:10',
            'categoria'             => 'required|string|in:presupuesto,ahorro,deuda,inversion,emprendimiento,educacion_general',
            'video_url'             => 'required|url',
            'parametro_formacional' => 'nullable',
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'titulo.required'      => 'El título de la lección es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.min'      => 'La descripción debe tener al menos 10 caracteres.',
            'categoria.required'   => 'Debe seleccionar una categoría válida.',
            'categoria.in'         => 'La categoría seleccionada no es válida.',
            'video_url.required'   => 'La URL del video de la lección es obligatoria.',
            'video_url.url'        => 'Ingrese una URL de video válida.',
        ];
    }
}
