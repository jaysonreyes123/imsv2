<?php

namespace App\Http\Controllers\Call;

use App\Http\Controllers\Controller;
use App\Http\Traits\Encryption;
use App\Http\Traits\HttpResponse;
use App\Models\CallLog;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use stdClass;

class CallController extends Controller
{
    //
    use HttpResponse,Encryption;
    public function index(Request $request){
        $Auth3CX = $this->Get3CXCookie();
        if(strlen($Auth3CX) != 0 ){
            $calllogs = $this->GetAPIData("CallLog", $Auth3CX, "TimeZoneName=Asia%2FManila&callState=All&dateRangeType=Today&fromFilter=&fromFilterType=Any&numberOfRows=50&searchFilter=&startRow=0&toFilter=&toFilterType=Any");
	        $results = (json_decode($calllogs));
            foreach($results->CallLogRows as $calls) {
                $calllogs_tks_dateandtime   = $calls->CallTime;
                $calllogs_tks_fromno        = $calls->CallerId;
                $calllogs_tks_tono          = $calls->Destination;
                $calllogs_tks_duration      = $calls->Duration;
                $call_logs_model = CallLog::where('date_and_time',$calllogs_tks_dateandtime)
                ->where('from_no',$calllogs_tks_fromno)
                ->where('to_no',$calllogs_tks_tono)
                ->where('duration',$calllogs_tks_duration)->get();
                if($call_logs_model->count() == 0){
                    $insert_model = new CallLog();
                    $insert_model->date_and_time = Carbon::parse($calllogs_tks_dateandtime)->format('m/d/Y h:i:s A');
                    $insert_model->from_no = $calllogs_tks_fromno;
                    $insert_model->to_no = $calllogs_tks_tono;
                    $insert_model->duration = $calllogs_tks_duration;
                    $insert_model->source = '3CX';
                    $insert_model->save();
                }
            }
        }
        else{
            echo "Authentication failed";
        }
    }
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

    function Get3CXCookie()
    {
        $ServerURL = env('CALL_SERVER');
        $LoginCreds = new stdClass();
        $LoginCreds->username = env('CALL_USERNAME');
        $LoginCreds->password = env('CALL_PASSWORD');

        $UserDetails = json_encode($LoginCreds);

        $PostData        =    file_get_contents("https://" . $ServerURL . "/api/login", false, stream_context_create(array(
            'http' => array(
                'protocol_version'    =>    '1.1',
                'user_agent'          =>    'PHP',
                'method'              =>    'POST',
                'header'              =>    'Content-type: application/json\r\n' .
                    'User-Agent: PHP\r\n' .
                    'Connection: close\r\n' .
                    'Content-length: ' . strlen($UserDetails) . '',
                'content'            =>    $UserDetails,
            ),
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        )));
        $TempCookie  = explode("; ", $http_response_header[9]);
        $FinalCookie = substr($TempCookie[0], 12);

        if ($PostData == "AuthSuccess")
            return $FinalCookie;

        return null;
    }

    function GetAPIData($API, $AuthCookie, $Parameter)
    {
        $ServerURL = env('CALL_SERVER');
        $GetData = file_get_contents("https://" . $ServerURL . "/api/" . $API. "/?" . $Parameter, false, stream_context_create(array(
            'http' => array(
                'protocol_version'    =>    '1.1',
                'user-agent'          =>    'PHP',
                'method'              =>    'GET',
                'header'              =>    'Cookie: ' . $AuthCookie . ''
            ),
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        )));

        return $GetData;
    }
}
