<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\OuterJoin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Catagory;
use App\Models\Subcatagory;
use App\Models\Homeimage;
use App\Models\Clothe;
use App\Models\Solt;
use App\Models\Dube;






use Carbon\Carbon;

use Charts;
//use App\Models\Message;
use Illuminate\Support\Facades\stroage;
//use Illuminate\Support\MessageBag;
use Illuminate\Database\Grammar;

class SMSController extends Controller
{
    //let create function for sms notification

    public function send($id)
        {
            $datanav=subcatagory::all();

            $data_sms=dube::find($id);

            return view('admin.sendsms',compact('data_sms','datanav'));


        }

        public function sendmessages(Request $request)
        {



        $to = request("phone");
        $from = getenv("TWILIO_FROM");
        $message = request("amount_dube");
        //open connection

        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, getenv("TWILIO_SID").':'.getenv("TWILIO_TOKEN"));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_URL, sprintf('https://api.twilio.com/2010-04-01/Accounts/'.getenv("TWILIO_SID").'/Messages.json', getenv("TWILIO_SID")));
        curl_setopt($ch, CURLOPT_POST, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'To='.$to.'&From='.$from.'&Body='.$message);

        // execute post
        $result = curl_exec($ch);
        $result = json_decode($result);

        // close connection
        curl_close($ch);
        //Sending message ends here
        return [$result];
     
    

    }

        
    
}



