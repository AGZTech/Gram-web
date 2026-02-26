<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
            MemberSeeder::class,
            NoticeSeeder::class,
            NewsSeeder::class,
            SchemeSeeder::class,
            DevelopmentWorkSeeder::class,
            PublicServiceSeeder::class,
        ]);
    }
}
