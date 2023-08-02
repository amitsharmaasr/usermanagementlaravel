<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Validation\Factory as Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    private $validationErrors = [];

    private $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function model(array $row)
    {
        $validation = $this->validateRow($row);

        if ($validation->fails()) {
            $this->validationErrors[] = [
                'row_num' => $this->getRowCount(),
                'errors' => $validation->errors()->all(),
            ];
            return null;
        }

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

        return new User([
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

    private function validateRow($row)
    {
        // You can define your custom validation rules here
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'dob' => 'date_format:Y-m-d',
            'doj' => 'date_format:Y-m-d',
            'gender' => 'nullable|string|in:male,female,other',
            'designation' => 'required|string',
        ];

        return $this->validator->make($row, $rules);
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private $rowCount = 0;

    public function getRowCount(): int
    {
        return ++$this->rowCount;
    }
}
