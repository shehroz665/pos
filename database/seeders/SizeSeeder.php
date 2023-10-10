<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['size_name' => 'Small'],
            ['size_name' => 'Medium'],
            ['size_name' => 'Large'],
            ['size_name' => 'xl'],
            ['size_name' => 'None'],
        ];
        foreach ($sizes as $size) {
            Size::create($size);
        }
    }
}
