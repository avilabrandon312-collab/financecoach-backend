<?php

namespace Database\Seeders;

use App\Models\Leccion;
use Illuminate\Database\Seeder;

class LeccionSeeder extends Seeder
{
    public function run(): void
    {
        $lecciones = [
            [
                'titulo' => 'Presupuesto 50/30/20',
                'descripcion' => 'Aprende a dividir tus ingresos en 50% necesidades, 30% deseos y 20% ahorro/deudas para tener control total de tu dinero cada mes.',
                'categoria' => 'presupuesto',
                'video_url' => 'https://www.youtube.com/watch?v=HQzoZfc3GwQ',
                'parametro_formacional' => ['nivel' => 'basico', 'duracion_min' => 8],
            ],
            [
                'titulo' => 'Cómo construir un fondo de emergencia',
                'descripcion' => 'Descubre cuánto dinero deberías tener ahorrado para imprevistos y cómo empezar a construir ese colchón financiero sin sacrificar tu día a día.',
                'categoria' => 'ahorro',
                'video_url' => 'https://www.youtube.com/watch?v=vJTjEd-P0aQ',
                'parametro_formacional' => ['nivel' => 'basico', 'duracion_min' => 10],
            ],
            [
                'titulo' => 'Tipos de deuda: buena vs mala',
                'descripcion' => 'No toda deuda es negativa. Aprende a diferenciar entre deuda que construye patrimonio y deuda que solo genera intereses sin retorno.',
                'categoria' => 'deuda',
                'video_url' => 'https://www.youtube.com/watch?v=PHe0bXAIuk0',
                'parametro_formacional' => ['nivel' => 'intermedio', 'duracion_min' => 12],
            ],
            [
                'titulo' => 'Interés compuesto explicado',
                'descripcion' => 'El interés compuesto es la base de toda inversión a largo plazo. Entiende cómo tu dinero puede crecer exponencialmente con el tiempo.',
                'categoria' => 'inversion',
                'video_url' => 'https://www.youtube.com/watch?v=x_1M2gGuNGA',
                'parametro_formacional' => ['nivel' => 'intermedio', 'duracion_min' => 9],
            ],
            [
                'titulo' => 'Primeros pasos para invertir',
                'descripcion' => 'Una guía práctica para quienes quieren empezar a invertir sin experiencia previa: CDTs, fondos de inversión y otras opciones de bajo riesgo.',
                'categoria' => 'inversion',
                'video_url' => 'https://www.youtube.com/watch?v=gFQNPmLKj1k',
                'parametro_formacional' => ['nivel' => 'avanzado', 'duracion_min' => 14],
            ],
            [
                'titulo' => 'Separar las finanzas personales del negocio',
                'descripcion' => 'Uno de los errores más comunes de los emprendedores es mezclar sus finanzas personales con las de su negocio. Aprende por qué separarlas es clave.',
                'categoria' => 'emprendimiento',
                'video_url' => 'https://www.youtube.com/watch?v=7wUAlL4nAd4',
                'parametro_formacional' => ['nivel' => 'basico', 'duracion_min' => 7],
            ],
            [
                'titulo' => 'Flujo de caja para pequeños negocios',
                'descripcion' => 'Entiende la diferencia entre utilidad y flujo de caja, y por qué un negocio rentable puede quedarse sin efectivo si no se gestiona bien.',
                'categoria' => 'emprendimiento',
                'video_url' => 'https://www.youtube.com/watch?v=NB0Y5xJ8xLQ',
                'parametro_formacional' => ['nivel' => 'intermedio', 'duracion_min' => 11],
            ],
            [
                'titulo' => '¿Qué es la inflación y cómo te afecta?',
                'descripcion' => 'La inflación reduce el poder adquisitivo de tu dinero con el tiempo. Aprende cómo protegerte de ella con decisiones financieras inteligentes.',
                'categoria' => 'educacion_general',
                'video_url' => 'https://www.youtube.com/watch?v=6Yg1atmxBz4',
                'parametro_formacional' => ['nivel' => 'basico', 'duracion_min' => 6],
            ],
        ];

        foreach ($lecciones as $leccion) {
            Leccion::firstOrCreate(
                ['titulo' => $leccion['titulo']],
                $leccion
            );
        }
    }
}
