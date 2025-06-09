<?php

namespace Database\Seeders;

use App\Http\Traits\Encryption;
use App\Models\Agency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use Encryption;
    public function run(): void
    {
        //
        $agencies = [
            [
                'agency_name' => 'Power Horizon International Resources Inc.',
                'contact_no_one' => '8123377',
                'contact_no_two' => '09338150574',
                'primary_email' => 'info@powerhorizonmanila.com.ph',
                'contact_person' => 'Michael Rey-Sal Celis Cuevas',
            ],
            [
                'agency_name' => 'Principalia Management & Personnel Consultants, Inc.',
                'contact_no_one' => '815-1077',
                'contact_no_two' => 'N/A',
                'primary_email' => 'info@principalia.com',
                'contact_person' => 'Lucas Felix M. Britanico',
            ],
            [
                'agency_name' => 'Profile Overseas Manpower Services Inc.',
                'contact_no_one' => '8133234',
                'contact_no_two' => '0999-8810527',
                'primary_email' => 'info@profilemanpower.com',
                'contact_person' => 'Jesus Noel Litan',
            ],
            [
                'agency_name' => 'QRD International Placement Inc.',
                'contact_no_one' => '02375-1144',
                'contact_no_two' => '09988417182',
                'primary_email' => 'info@qrdinternational.com',
                'contact_person' => 'Gerard B. Sanvictores',
            ],
            [
                'agency_name' => 'EDI Staffbuilders International, Inc.',
                'contact_no_one' => '812-6703',
                'contact_no_two' => '812-6704',
                'primary_email' => 'tag@edistaffbuilders.com',
                'contact_person' => 'N/A',
            ],
            [
                'agency_name' => 'Cierto Agencia Profesionale, Inc.',
                'contact_no_one' => '8628-1473',
                'contact_no_two' => 'N/A',
                'primary_email' => 'agenciaprofesionale@gmail.com',
                'contact_person' => 'N/A',
            ],
            [
                'agency_name' => 'Chartreuse Prime Recruitment Specialists, Inc.',
                'contact_no_one' => '8643-5400',
                'contact_no_two' => 'N/A',
                'primary_email' => 'resume@cprsi.com.ph',
                'contact_person' => 'N/A',
            ],
            [
                'agency_name' => 'Bison Management Corp.',
                'contact_no_one' => '8896-4667',
                'contact_no_two' => '7957-3286',
                'primary_email' => 'info@bisonph.com',
                'contact_person' => 'N/A',
            ],
            [
                'agency_name' => 'Aureus Manpower & Consultancy Corp.',
                'contact_no_one' => '0917-8052939',
                'contact_no_two' => '0918-9381982',
                'primary_email' => 'info@aureuscorp.com',
                'contact_person' => 'N/A',
            ],
            [
                'agency_name' => 'Anifel Management and General Services Corp.',
                'contact_no_one' => '8524-9491',
                'contact_no_two' => '920-901-3525',
                'primary_email' => 'anifelmanila@yahoo.com',
                'contact_person' => 'N/A',
            ],
        ];
       foreach($agencies as $keys => $datas){
            $model = new Agency();
            foreach($datas as $key => $value){
                $model->$key = $this->encrypt($key,$value);
            }
            $model->save();
        } 
    }
}
