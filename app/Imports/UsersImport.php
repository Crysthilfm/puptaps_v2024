<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        Alumni::insert (
            array (
                'stud_number'    => $row['studentnumber'],
                'last_name'      => $row['lastname'],
                'first_name'     => $row['firstname'],
                'middle_name'    => $row['middlename'],
                'suffix'         => $row['suffix'],
                'course_id'      => $row['course'],
                'batch'          => $row['batch'],

                // Sample grad date
                // Change template
                // Add
                'graduate_year'  => $row['graduateyear'],

                'email'          => '',
                'number'         => '',
                'profile_status' => 'Incomplete',
            )
        );

        $alumni = Alumni::where('stud_number', '=', $row['studentnumber'])->first();
        User::insert(
            array (
                'alumni_id'         => $alumni['alumni_id'],
                'email'             => '',
                'username'          => $alumni['stud_number'],
                'password'          => 'Not Set',
                'user_role'         => 'Alumni',
                'account_status'    => 'Inactive',
            )
        );
    }
}
