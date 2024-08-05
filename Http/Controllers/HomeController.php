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
use App\Models\Like;
use App\Models\Solt;

use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Order;



use Carbon\Carbon;

use Charts;
//use App\Models\Message;
use Illuminate\Support\Facades\stroage;
//use Illuminate\Support\MessageBag;
use Illuminate\Database\Grammar;

class HomeController extends Controller
{


    //first ao
    public function redirect()
    {
        if(Auth::id())
        {

            if(Auth::user()->usertype == '0' || Auth::user()->usertype == '2')
            {
                $data_himage=homeimage::where('status','Activate')->get();
                $data_cata=catagory::all();
                //$data_catsub=
                $dat_au=Auth::user()->usertype=='5';
                $data_subcata =DB::table('subcatagories')
                
                ->distinct()
                //->orderBy('catagories.name','DESC')
                ->get();
                $data_flora=DB::table('catagories')
                ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
                ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
                ->select('catagories.*','subcatagories.*','clothes.*')
                ->orderBy('clothes.created_at','DESC')
                ->where('catagories.id','=',1)
                ->get();
                $data_taka=DB::table('catagories')
                ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
                ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
                ->select('catagories.*','subcatagories.*','clothes.*')
                ->orderBy('clothes.created_at','DESC')
                ->where('catagories.id','=',6)
                ->get();
                $other=DB::table('catagories')
                ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
                ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
                ->select('catagories.*','subcatagories.*','clothes.*')
                ->orderBy('clothes.created_at','DESC')
                ->where('catagories.id','=',7)
                ->get();

                return view('user.home',compact('data_himage','other','data_taka','data_cata','data_subcata','dat_au','data_flora'));
            }
            else
            {
                $datanav=subcatagory::all();
            $data_solt=solt::sum('amount_solt');
            $data_user=user::count();
            $data_home=homeimage::count();

            $data_clothe=clothe::count();
            $data_taka=clothe::sum('amount');

            $data_categ=catagory::count();
            $data_scateg=subcatagory::count();
            $data_profit=solt::sum('profit');
        return view('admin.home',compact('datanav','data_profit','data_solt','data_scateg','data_categ','data_user','data_home','data_clothe','data_taka'));
            }
        }


        else{
               return redirect()->back();
        }
    }


    //iindex function when user enter to code he get theid folede


    public function index()
    {
        $data_himage=homeimage::where('status','Activate')->get();
        $data_cata=catagory::all();

        $data_subcata =DB::table('subcatagories')
                    ->distinct()
        //->orderBy('catagories.name','DESC')
        ->get();
        //$da=subcatagory::where('subcatagory.categ_id','=',$id)->get();
        $data_flora=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->select('catagories.*','subcatagories.*','clothes.*')
        ->orderBy('clothes.created_at','DESC')
        ->where('catagories.id','=',3)
        ->get();
        $data_taka=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->select('catagories.*','subcatagories.*','clothes.*')
        ->orderBy('clothes.created_at','DESC')
        ->where('catagories.id','=',6)
        ->get();
        $other=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->select('catagories.*','subcatagories.*','clothes.*')
        ->orderBy('clothes.created_at','DESC')
        ->where('catagories.id','=',7)
        ->get();
        return view('user.home',compact('data_himage','other','data_taka','data_cata','data_subcata','data_flora'));
    }
    //home subcatagory
    public function home_subcatagory($id)
    {
        $data_cata=catagory::all();

        $data_subcata =DB::table('subcatagories')
                    ->distinct()
        //->orderBy('catagories.name','DESC')
        ->get();

        $data_catagory=catagory::find($id);
        $data_sub=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->where('subcatagories.categ_id',$id)
        ->get();
        return view('user.subcatagory_home',compact('data_sub','data_cata','data_subcata','data_catagory'));
    }
    public function home_page()
    {
        $data_himage=homeimage::where('status','Activate')->get();
      $data_a =DB::table('catagories')->select('catagories.*')->get();

      $data_cata=catagory::all();
        $data_subcata =DB::table('subcatagories')
                    ->distinct()
        //->orderBy('catagories.name','DESC')
        ->get();
        $data_flora=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->select('catagories.*','subcatagories.*','clothes.*')
        ->orderBy('clothes.created_at','DESC')
        ->where('catagories.id','=',3)
        ->get();
        $data_taka=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->select('catagories.*','subcatagories.*','clothes.*')
        ->orderBy('clothes.created_at','DESC')
        ->where('catagories.id','=',6)
        ->get();
        $other=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->select('catagories.*','subcatagories.*','clothes.*')
        ->orderBy('clothes.created_at','DESC')
        ->where('catagories.id','=',7)
        ->get();

        

        //$da=subcatagory::where('subcatagory.categ_id','=',$id)->get();
        return view('user.home',compact('data_himage','other','data_taka','data_cata','data_subcata','data_flora','data_a'));
    }




    public function GetSubCatAgainstMainCatEdit($id){
        echo json_encode(DB::table('subcatagories')->where('subcatagories.categ_id', $id)->get());
    }
    //porrtfolia pagee
    public function portfolia($id)
    {
        $data_portfolia=DB::table('clothes')
        ->where('clothes.subcateg_id',$id)
        ->orderBy('clothes.created_at','DESC')
        //->orderBy('cost','ASC')
        ->get();

        //commao for all varialble
        
        $data_subcata =DB::table('subcatagories')
                    ->distinct()
        //->orderBy('catagories.name','DESC')
        ->get();
        $data_port=subcatagory::find($id);
        $data_cata=catagory::all();
        return view('user.portfolio',compact('data_portfolia','data_subcata','data_cata','data_port'));
    }

    //portfolio detai

    public function portfolio_detail($id)
    {
           
        $data_subcata =DB::table('subcatagories')
                    ->distinct()
        //->orderBy('catagories.name','DESC')
        ->get();
        $data_cata=catagory::all();
        $data_clothe=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->orderBy('clothes.created_at','DESC')
        ->where('clothes.id','=',$id)
        ->get();
       return view('user.portfoliodetail',compact('data_subcata','data_cata','data_clothe'));
    }
    //


public function getSubcategoriesids($subcategoryId)
{
     
    $data_portfolia=DB::table('clothes')
        ->where('clothes.subcateg_id',$subcategoryId)
        ->orderBy('clothes.created_at','DESC')
        //->orderBy('cost','ASC')
        ->get();

        //commao for all varialble
        
        $data_port=subcatagory::find($subcategoryId);
        $data_cata=catagory::all();
        return view('user.portfolio',compact('data_portfolia','data_cata','data_port'));


}

public function order_clothe (Request $request,$id)
    {

        $data_order=new order;
$da=Auth::user()->id;
        $data_order->user_id=$da;
        $data_order->amount_order=$request->amount_order;
        $data_order->text=$request->text;
        $data_order->status='requested';
        $data_order->clothe_id=$id;

        $data_order->save();

       Alert::success('Product added or Requested in to order successfully','Pleae go to order page and confirm in order to get your purchase');

        return redirect()->back();
   
    }

    //order page
    public function ordercustomer($id)
    {
        $data_cata=catagory::all();

        $data_cr=DB::table('catagories')
        ->where('catagories.id','=',$id)
        ->get();


        $data_user=Auth::user()->id;



        $data_or=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->join('orders','clothes.id','=','orders.clothe_id')
        ->select('catagories.*','subcatagories.*','clothes.*','orders.*')
        ->where('catagories.id','=',$id)
        ->where('orders.user_id','=',$data_user)
        ->get();
        return view('user.order',compact('data_cata','data_or','data_cr'));
    }

    public function confirm_order($id)
    {
        $data=order::find($id);
        $data->status='ordered';
        $data->save();
        Alert::success('Product Ordered Succesfully','please pay and your purchase delivered soon Thank You');
        return redirect()->back();
    }

    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'confirmed';
        $order->save();

        return response()->json([
            'buttonText' => 'Confirmed',
        ]);
    }
    //deleting order
    public function delete_order($id)
    {
        $order = Order::findOrFail($id);

        // Delete the order
        $order->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Order deleted successfully.');

    }


    // check if the user likes it or not
    public function checkLikeStatus($cloth_id)
    {
        $user_id = auth()->id();

        // Check if the user has liked the cloth
        $like = Like::where('user_id', $user_id)
            ->where('cloth_id', $cloth_id)
            ->first();

        $userHasLiked = $like !== null;

        return response()->json(['user_has_liked' => $userHasLiked]);
    }

    // like
    public function like(Request $request){
        $like=new Like();
        
        if (auth()->check()){
            $user_id=Auth::user()->id;

            $user_liked = Like::where('user_id', $user_id)
            ->where('cloth_id', $request->cloth_id)
            ->first();

            if(!$user_liked){
                $like->user_id=$user_id;
                $like->cloth_id=$request->cloth_id;
                $like->save();
                return response()->json(['success' => true]);
            }

            

        }

     
    }

    // unlike
    public function unlike(Request $request){
        $like=new Like();
        
        if (auth()->check()){
            $user_id=Auth::user()->id;

            $user_liked = Like::where('user_id', $user_id)
            ->where('cloth_id', $request->cloth_id)
            ->first();

            if($user_liked){
                $user_liked->delete();
                return response()->json(['success' => true]);
            
            }

            

        }

     
    }

    public function getLikesCount($cloth_id)
    {
        // Get the count of likes for the cloth
        $likesCount = Like::where('cloth_id', $cloth_id)->count();

        return response()->json(['likes_count' => $likesCount]);
    }



    public function getSubscriptionStatus($user_id)
    {
        // Retrieve the subscription status for the given user
        $user = User::find($user_id);

        if ($user) {
            return response()->json(['success' => true, 'subscription_status' => $user->subscribed]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    // subscribe
    public function subscribe(Request $request){
        
        if (auth()->check()){
            $user_id=Auth::user()->id;

            $user = User::find($user_id);

            if ($user) {
                $user->subscribed=true;
                $user->save();
                return response()->json(['success' => true]);
           
            } else {
                return response()->json(['sucess' => false]);
                 }

            }

        }

        public function unsubscribe(Request $request)
    {
        $user_id=Auth::user()->id;

        $user = User::find($user_id);

        if ($user) {
            // Update the user's subscription status to false (unsubscribed)
            $user->subscribed = false;
            $user->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }


}
