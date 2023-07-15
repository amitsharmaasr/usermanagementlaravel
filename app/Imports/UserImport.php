<?php
namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
         foreach ($rows as $row) {
        switch (strtolower($row['designation'])) {
                    case 'manager': 
                        $designation = 2;
                        break;
                    case 'admin': 
                        $designation = 1;
                        break;
                    default: 
                        $designation = 3;
                        break;
        }

        User::insert([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'mail_password' => $row['password'],
            'dob' => trim($row['dob']),
            'doj' => trim($row['doj']),
            'gender' => strtolower(trim($row['gender'])),
            'user_type' => $designation
        ]);
    }
    
    } 
}