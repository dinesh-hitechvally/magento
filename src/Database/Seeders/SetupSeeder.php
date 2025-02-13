<?php

namespace Dinesh\Magento\Database\Seeders;

use Illuminate\Database\Seeder;
use Dinesh\Magento\App\Models\Setup;

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'url' => 'http://localhost/magento2/pub/',
                'user' => 'admin',
                'password' => 'admin123',
            ],
        ];

        // Seed the setup table
        foreach ($data as $websiteData) {
            Setup::create($websiteData);
        }
    }
}
