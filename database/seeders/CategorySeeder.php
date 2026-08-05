<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['name' => 'Salario',          'type' => 'income',  'naturaleza' => 'otro'],
            ['name' => 'Ventas negocio',   'type' => 'income',  'naturaleza' => 'otro'],
            ['name' => 'Vivienda/Arriendo','type' => 'expense', 'naturaleza' => 'esencial'],
            ['name' => 'Servicios públicos','type' => 'expense','naturaleza' => 'esencial'],
            ['name' => 'Alimentación',     'type' => 'expense', 'naturaleza' => 'esencial'],
            ['name' => 'Transporte',       'type' => 'expense', 'naturaleza' => 'esencial'],
            ['name' => 'Salud',            'type' => 'expense', 'naturaleza' => 'esencial'],
            ['name' => 'Educación',        'type' => 'expense', 'naturaleza' => 'esencial'],
            ['name' => 'Entretenimiento/Ocio','type' => 'expense','naturaleza' => 'discrecional'],
            ['name' => 'Restaurantes/Domicilios','type' => 'expense','naturaleza' => 'discrecional'],
            ['name' => 'Compras/Ropa',     'type' => 'expense', 'naturaleza' => 'discrecional'],
            ['name' => 'Viajes',           'type' => 'expense', 'naturaleza' => 'discrecional'],
        ];

        foreach ($categorias as $categoria) {
            Category::firstOrCreate(['name' => $categoria['name']], $categoria);
        }
    }
}
