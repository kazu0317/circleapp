<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DateTime;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'user1',
            'email'=>'user1@gmail.com',
            'created_at'=>new DateTime(),
            'updated_at'=>new DateTime(),
            'password' => Hash::make('password'),
            'univ' => 'テスト大学',
            'grade' => 2,
            'hobby' => 'テニス',
            'hard_experience' =>0,
            'soft_experience' =>3,

     ]);
    }
}
