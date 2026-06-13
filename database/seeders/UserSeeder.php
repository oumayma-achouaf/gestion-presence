<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['pin_code' => '1234'],
            [
                'name' => 'ADMIN',
                'role' => 'admin',
            ],
        );

        $employees = [
            ['name' => 'ABIRE', 'pin_code' => '1001'],
            ['name' => 'RAHMA', 'pin_code' => '1002'],
            ['name' => 'MONCEF', 'pin_code' => '1003'],
            ['name' => 'SABER', 'pin_code' => '1004'],
            ['name' => 'TAHRA', 'pin_code' => '1005'],
            ['name' => 'FATIHA', 'pin_code' => '1006'],
            ['name' => 'HAYAT', 'pin_code' => '1007'],
            ['name' => 'SAID', 'pin_code' => '1008'],
            ['name' => 'AYOUB', 'pin_code' => '1009'],
            ['name' => 'YOUNESS', 'pin_code' => '1010'],
        ];

        foreach ($employees as $index => $data) {
            $user = User::updateOrCreate(
                ['pin_code' => $data['pin_code']],
                [
                    'name' => $data['name'],
                    'role' => 'employee',
                ],
            );

            Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $data['name'],
                    'email' => strtolower($data['name']).'@company.com',
                    'phone' => '060000000'.$index,
                    'position' => 'Employee',
                    'status' => 'active',
                    'hire_date' => now()->subMonths($index + 1)->toDateString(),
                ],
            );
        }
    }
}
