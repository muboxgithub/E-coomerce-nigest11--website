<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Subcatagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FeedbackController extends Controller
{
    //
    public function send_feedback(Request $request){
        $feedback=new Feedback();


        $feedback->name=$request->name;
        $feedback->email=$request->email;
        $feedback->feedback=$request->feedback;

        if (auth()->check()){
            $user_id=Auth::user()->id;
            $feedback->user_id=$user_id;
        }

        $feedback->save();

       

        return redirect()->back();
   
    }

    public function view_feedback(){
        $feedback=Feedback::all();
        $datanav = Subcatagory::all();
        return view('admin.feedback',compact('feedback','datanav'));
    }
}
