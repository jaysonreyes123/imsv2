<?php

namespace App\Http\Controllers\Call;

use App\Http\Controllers\Controller;
use App\Http\Traits\Encryption;
use App\Http\Traits\HttpResponse;
use App\Http\Traits\PbxTrait;
use App\Models\CallLog;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use stdClass;

class CallController extends Controller
{
    //
    use HttpResponse,Encryption,PbxTrait;
    public function call(Request $request){
        $mobile = $request->number;
        $mobile_encrypt = $this->encrypt('mobile',$mobile);
        $contact = Contact::where('mobile',$mobile_encrypt)->first();
        if($contact){
            $params = "caller_contact=$contact->mobile&caller_firstname=$contact->firstname&caller_lastname=$contact->lastname";
        }
        else{
            $params = "caller_contact=$mobile";
        }
        return Redirect()->to(env('IMS_URL')."/save/incidents?$params");
    }

    public function index(Request $request){
        return  $this->check_token_expiration();
    }
}
