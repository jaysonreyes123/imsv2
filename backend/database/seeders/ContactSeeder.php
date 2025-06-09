<?php

namespace Database\Seeders;

use App\Http\Traits\Encryption;
use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use Encryption;
    public function run(): void
    {
        //
        $contacts = [
            [
                'firstname'    => 'Ana',
                'lastname'     => 'Santos',
                'mobile'       => '09171234567',
                'landline'     => '0212345678',
                'primary_email' => 'ana.santos@email.com',
                'date_of_birth' => '1990-05-15',
                'caller_types'  => '1'
            ],
            [
                'firstname'    => 'Ben',
                'lastname'     => 'Reyes',
                'mobile'       => '09172345678',
                'landline'     => '0223456789',
                'primary_email' => 'ben.reyes@email.com',
                'date_of_birth' => '1985-08-22',
                'caller_types'  => '2'
            ],
            [
                'firstname'    => 'Clara',
                'lastname'     => 'Dela Cruz',
                'mobile'       => '09173456789',
                'landline'     => '0234567890',
                'primary_email' => 'clara.delacruz@email.com',
                'date_of_birth' => '1992-11-30',
                'caller_types'  => '3'
            ],
            [
                'firstname'    => 'David',
                'lastname'     => 'Mendoza',
                'mobile'       => '09174567890',
                'landline'     => '0245678901',
                'primary_email' => 'david.mendoza@email.com',
                'date_of_birth' => '1988-02-14',
                'caller_types'  => '4'
            ],
            [
                'firstname'    => 'Elena',
                'lastname'     => 'Garcia',
                'mobile'       => '09175678901',
                'landline'     => '0256789012',
                'primary_email' => 'elena.garcia@email.com',
                'date_of_birth' => '1995-07-09',
                'caller_types'  => '1'
            ],
            [
                'firstname'    => 'Felix',
                'lastname'     => 'Bautista',
                'mobile'       => '09176789012',
                'landline'     => '0267890123',
                'primary_email' => 'felix.bautista@email.com',
                'date_of_birth' => '1980-12-25',
                'caller_types'  => '2'
            ],
            [
                'firstname'    => 'Grace',
                'lastname'     => 'Lopez',
                'mobile'       => '09177890123',
                'landline'     => '0278901234',
                'primary_email' => 'grace.lopez@email.com',
                'date_of_birth' => '1993-03-18',
                'caller_types'  => '3'
            ],
            [
                'firstname'    => 'Hector',
                'lastname'     => 'Perez',
                'mobile'       => '09178901234',
                'landline'     => '0289012345',
                'primary_email' => 'hector.perez@email.com',
                'date_of_birth' => '1982-06-04',
                'caller_types'  => '4'
            ],
            [
                'firstname'    => 'Iris',
                'lastname'     => 'Cruz',
                'mobile'       => '09179012345',
                'landline'     => '0290123456',
                'primary_email' => 'iris.cruz@email.com',
                'date_of_birth' => '1990-09-21',
                'caller_types'  => '1'
            ],
            [
                'firstname'    => 'Jake',
                'lastname'     => 'Morales',
                'mobile'       => '09170123456',
                'landline'     => '0201234567',
                'primary_email' => 'jake.morales@email.com',
                'date_of_birth' => '1987-01-02',
                'caller_types'  => '2'
            ]
        ];

        foreach($contacts as $keys => $datas){
            $model = new Contact();
            foreach($datas as $key => $value){
                $model->$key = $this->encrypt($key,$value);
            }
            $model->save();
        }
    }
}
