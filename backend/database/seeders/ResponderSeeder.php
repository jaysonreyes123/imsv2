<?php

namespace Database\Seeders;

use App\Http\Traits\Encryption;
use App\Models\Responder;
use App\Models\ResponderType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResponderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use Encryption;
    public function run(): void
    {
        //
        $responder_data = [
            [
                "responder_types" => "Fire Department",
                "firstname" => "Jose",
                "lastname" => "Dela Cruz",
                "contact_no" => "09171234567",
                "email_address" => "jose.delacruz@example.com",
                "password" => "Pass1234!"
            ],
            [
                "responder_types" => "Law Enforcement Department",
                "firstname" => "Maria",
                "lastname" => "Santos",
                "contact_no" => "09182345678",
                "email_address" => "maria.santos@example.com",
                "password" => "LawEnf567"
            ],
            [
                "responder_types" => "Public Health and Safety Department",
                "firstname" => "Juan",
                "lastname" => "Reyes",
                "contact_no" => "09223456789",
                "email_address" => "juan.reyes@example.com",
                "password" => "Health@2025"
            ],
            [
                "responder_types" => "Fire Department",
                "firstname" => "Ana",
                "lastname" => "Garcia",
                "contact_no" => "09184561234",
                "email_address" => "ana.garcia@example.com",
                "password" => "FireDept01"
            ],
            [
                "responder_types" => "Law Enforcement Department",
                "firstname" => "Mark",
                "lastname" => "Mendoza",
                "contact_no" => "09331234567",
                "email_address" => "mark.mendoza@example.com",
                "password" => "SafeMark99"
            ],
            [
                "responder_types" => "Public Health and Safety Department",
                "firstname" => "Liza",
                "lastname" => "Ramos",
                "contact_no" => "09224567890",
                "email_address" => "liza.ramos@example.com",
                "password" => "LizaPHS!23"
            ],
            [
                "responder_types" => "Fire Department",
                "firstname" => "Pedro",
                "lastname" => "Bautista",
                "contact_no" => "09173456789",
                "email_address" => "pedro.bautista@example.com",
                "password" => "PedroFire4"
            ],
            [
                "responder_types" => "Law Enforcement Department",
                "firstname" => "Carla",
                "lastname" => "Flores",
                "contact_no" => "09339876543",
                "email_address" => "carla.flores@example.com",
                "password" => "LawFlores8"
            ],
            [
                "responder_types" => "Public Health and Safety Department",
                "firstname" => "Miguel",
                "lastname" => "Navarro",
                "contact_no" => "09185678901",
                "email_address" => "miguel.navarro@example.com",
                "password" => "HealthMig9"
            ],
            [
                "responder_types" => "Fire Department",
                "firstname" => "Jessa",
                "lastname" => "Cruz",
                "contact_no" => "09234567890",
                "email_address" => "jessa.cruz@example.com",
                "password" => "JessaFDept"
            ],
        ];
        foreach($responder_data as $keys => $datas){
            $responder = new Responder();
            foreach($datas as $key => $value){
                if($key == "responder_types"){
                    $d = ResponderType::where("label",$value)->first();
                    $responder->$key = $d->id ?? 1;
                }
                else{
                    $responder->$key = $this->encrypt($key,$value);
                }
                $responder->statuses = 1;
                $responder->responder_statuses = 1;

            }
            $responder->save();
        }

    }
}
