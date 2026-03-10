<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Makanan
            ['name' => 'Nasi Goreng',        'category' => 'Makanan',  'price' => 10000, 'status' => 'available', 'description' => 'Nasi goreng spesial dengan telur dan ayam'],
            ['name' => 'Mie Goreng',          'category' => 'Makanan',  'price' => 9000,  'status' => 'available', 'description' => 'Mie goreng pedas gurih'],
            ['name' => 'Ayam Geprek',         'category' => 'Makanan',  'price' => 12000, 'status' => 'available', 'description' => 'Ayam geprek sambal bawang'],
            ['name' => 'Nasi Uduk',           'category' => 'Makanan',  'price' => 8000,  'status' => 'available', 'description' => 'Nasi uduk lengkap dengan lauk pauk'],
            ['name' => 'Bakso',               'category' => 'Makanan',  'price' => 10000, 'status' => 'available', 'description' => 'Bakso sapi kuah kaldu'],
            ['name' => 'Soto Ayam',           'category' => 'Makanan',  'price' => 10000, 'status' => 'available', 'description' => 'Soto ayam bening dengan suwiran ayam'],
            ['name' => 'Gado-gado',           'category' => 'Makanan',  'price' => 9000,  'status' => 'available', 'description' => 'Gado-gado sayur dengan bumbu kacang'],
            ['name' => 'Lontong Sayur',       'category' => 'Makanan',  'price' => 8000,  'status' => 'out',       'description' => 'Lontong dengan sayur lodeh'],
            // Snack
            ['name' => 'Pisang Goreng',       'category' => 'Snack',    'price' => 3000,  'status' => 'available', 'description' => 'Pisang goreng crispy'],
            ['name' => 'Tahu Goreng',         'category' => 'Snack',    'price' => 2000,  'status' => 'available', 'description' => 'Tahu goreng bumbu'],
            ['name' => 'Tempe Goreng',        'category' => 'Snack',    'price' => 2000,  'status' => 'available', 'description' => 'Tempe goreng tepung'],
            ['name' => 'Risoles',             'category' => 'Snack',    'price' => 4000,  'status' => 'available', 'description' => 'Risoles isi sayuran'],
            // Minuman
            ['name' => 'Es Teh Manis',        'category' => 'Minuman',  'price' => 3000,  'status' => 'available', 'description' => 'Teh manis dingin segar'],
            ['name' => 'Es Jeruk',            'category' => 'Minuman',  'price' => 4000,  'status' => 'available', 'description' => 'Jeruk peras segar'],
            ['name' => 'Air Mineral',         'category' => 'Minuman',  'price' => 2500,  'status' => 'available', 'description' => 'Air mineral botol 600ml'],
            ['name' => 'Es Coklat',           'category' => 'Minuman',  'price' => 5000,  'status' => 'available', 'description' => 'Coklat dingin bergurih'],
            ['name' => 'Jus Alpukat',         'category' => 'Minuman',  'price' => 6000,  'status' => 'out',       'description' => 'Jus alpukat susu segar'],
            ['name' => 'Teh Hangat',          'category' => 'Minuman',  'price' => 2000,  'status' => 'available', 'description' => 'Teh hangat manis'],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
