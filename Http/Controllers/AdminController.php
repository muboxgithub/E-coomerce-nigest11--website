<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

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
use App\Models\Feedback;
use App\Models\Order;







use Carbon\Carbon;

use Charts;
//use App\Models\Message;
use Illuminate\Support\Facades\stroage;
//use Illuminate\Support\MessageBag;
use Illuminate\Database\Grammar;

class AdminController extends Controller
{
    //

        public function admin_dashbord()
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

        public function type_of_cloth()
        {
            $datanav=subcatagory::all();
        $data_type=catagory::all();


        return view('admin.typeofclothe',compact('data_type','datanav'));
        }

        public function add_type_of_clothe()
        {
            $datanav=subcatagory::all();
        return view('admin.add_type_of_clothe',compact('datanav'));
        }

        public function upload_Type(Request $request)
        {

        $validator = Validator::make($request->all(), [

        'name' =>'required|string|max:20|unique:catagories',
        'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
        'description'=>'required|string|max:200'

        ],[
        'name'=>'Type of clothe are already exist try another',
        'description'=>'Please write short and brief description',
        ]);


        if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
        }

        $data=new catagory;
        $data->name=$request->name;
        //imageof type of clote
        // $catimag=$request->image;
        $image=$request->image;
        if($image){
        $imagename=time().'.'.$image->getClientoriginalExtension();
        $request->image->move('catagoryimage',$imagename);
        $data->image=$imagename;
        }


        $data->description=$request->description;

        $data->status='Active';

        $data->save();



        return redirect()->back()->with('message','You have Successfullly Addrd a type of clothe,Good Luck!!');
        }



        //update catgory information

        public function updatecateg($id)
        {
            $datanav=subcatagory::all();

        $data_up=catagory::find($id);

        return view('admin.updatecatagory',compact('data_up','datanav'));
        }

        //upload updated catagory information

        public function upload_updated_catagory(Request $request,$id)
        {


        $validate=Validator::make($request->all(),
        [

        'name'=>'string|max:20',
        'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
        'decription'=>'string|max:255',
        ],[
        'name'=>'the name is already exist,write another',
        'description'=>'please write good and short description',

        ]);

        if($validate->fails())
        {
        return redirect()->back()->withErrors($validate)->withInput();
        }
        $update=catagory::find($id);

        $update->name=$request->name;

        $image=$request->image;
        if($image){
        $imagename=time().'.'.$image->getClientoriginalExtension();
        $request->image->move('catagoryimage',$imagename);
        $update->image=$imagename;
        }

        $update->description=$request->description;
        $update->save();

        return redirect()->back()->with('message','You have successfully updated catagory inforamtion');
        }

        //active catagory

        public function active_catagory($id)
        {
        $data_active=catagory::find($id);
        $data_active->status='Activated';
        $data_active->save();

        return redirect()->back()->with('message','You have Successfully Activate catagory information');
        }

        //deactive cataory

        public function dactive_catagory($id)
        {
        $data_de=catagory::find($id);
        $data_de->status='Deactivate';
        $data_de->save();

        return redirect()->back()->with('message','you have successfully deactivate catagory information');
        }
        //sub catagory page

        public function subcatagory()
        {
            $datanav=subcatagory::all();
        $data_subtype =DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        //->select('catagories.*,subcatagories.*')
        ->get();
        return view('admin.subcatagory',compact('data_subtype','datanav'));
        }

        //add sub type of clothe function

        public function add_subtype_of_clothe()
        {
            $datanav=subcatagory::all();
            $dat_cat=catagory::all();
            return view('admin.addsubcatagory',compact('dat_cat','datanav'));
        }
        //upload sub type of clothe
        public function upload_subtype_ofclothe(Request $request)
        {
            $val=Validator::make($request->all(),[
              'subname'=>'required|string|max:40|unique:subcatagories',
              'subdescription'=>'required|max:255|string',
              'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',

            ],[
               'subname'=>'subtype of clothe is already talken please write unique name',
            ]);
            if($val->fails())
            {
                return redirect()->back()->withErrors($val)->withInput();
            }
            $data=new subcatagory;
            $data->categ_id=$request->categ_id;
            $data->subname=$request->subname;
            $image=$request->subimage;
            if($image){
            $imagename=time().'.'.$image->getClientoriginalExtension();
            $request->subimage->move('subcatagoryimage',$imagename);
            $data->subimage=$imagename;
            }
            $data->substatus='Active';
    
            $data->subdescription=$request->subdescription;
            $data->save();
            return redirect()->back()->with('message','You have successfully upload sub type of clothe Good Luck!!');
        }
        //activate catagory page
        public function active_subcatagory($id)
        {
            $data_sub=subcatagory::find($id);
            $data_sub->substatus='Activated';
            $data_sub->save();
            return redirect()->back()->with('message','you have successfully activated subtype of clothe');    
        }
        //deactivate subtype of cloth
        public function dactive_subcatagory($id)
        {
            $data_sub=subcatagory::find($id);
            $data_sub->substatus='Dectivated';
            $data_sub->save();
           return redirect()->back();
        }        
        //update sub catagory page
        public function updatesubcateg($id)
        {
           /// $dta_sub=subcatagory::find($id);
           $datanav=subcatagory::all();
           $dta_sub=subcatagory::find($id);
             $data=DB::table('catagories')
            ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
            ->where('subcatagories.id','=',$id)
           ->select('catagories.*','subcatagories.*')
       
           ->get();

           $data_cattype =catagory::all();
            return view('admin.updatesubcategory',compact('dta_sub','data','data_cattype','datanav'));
        }
        //upload updated subcatagory
        public function upload_updated_subcatagory(Request $request,$id)
        {
            $val=Validator::make($request->all(),[
              'subname'=>'required|string|max:40',
              'subdescription'=>'required|string|max:200',
              'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            ]);
            if($val->fails())
            {
                return redirect()->back()->withErrors($val)->withInput();
            }
            $data=subcatagory::find($id);
            $data->categ_id=$request->categ_id;
            $data->subname=$request->subname;
            $image=$request->subimage;
            if($image){
            $imagename=time().'.'.$image->getClientoriginalExtension();
            $request->subimage->move('subcatagoryimage',$imagename);
            $data->subimage=$imagename;
            }
            $data->substatus='Active';
    
            $data->subdescription=$request->subdescription;
            $data->save();
            return redirect()->back()->with('message','You have successfully updated sub catagory information');
        }
        //home image
        public function home_image()
        {
            $datanav=subcatagory::all();
            $data=homeimage::all();
            return view('admin.homeimage',compact('data','datanav'));
        }
        //add home image
        public function add_home_image()
        {
            $datanav=subcatagory::all();
            return view('admin.addhomeimage',compact('datanav'));
        }
        public function upload_home_image(Request $request)
        {
            $val=Validator::make($request->all(),[
                'himage'=>'required|image|mimes:jpeg,png,jpg,gif,avif|max:4000',
                'title'=>'required|string|max:30',
                'script'=>'required|string|max:150',
            ],[
                'himage'=>'the slider image must be rrequired and must be mimes:jpeg,png,jpg,gif,avif files',
            ]);
            if($val->fails())
            {
                return redirect()->back()->withErrors($val)->withInput();
            }
            $data_h= new homeimage;
            //adding of home imae
            $image=$request->himage;
            $imagename=time().'.'.$image->getClientoriginalExtension();
            $request->himage->move('homeimage',$imagename);
            $data_h->himage=$imagename;
            
            $data_h->title=$request->title;
            $data_h->script=$request->script;
            $data_h->status='Active';
            $data_h->save();
            return redirect()->back()->with('message','You have successfully add home slider view');

        }
        //activated slider imge
        public function active_sliderimage($id)
        {
            
            $data_home=homeimage::find($id);
            $data_home->status='Activate';
            $data_home->save();
            return redirect()->back()->with('message','You have successfully activate your ome slider');
        }
        //dactivated slider imge
        public function dactive_sliderimage($id)
        {
            $data_home=homeimage::find($id);
            $data_home->status='Dactivate';
            $data_home->save();
            return redirect()->back();
        }
        //update slider image page
        public function updatesliderimage($id)
        {
            $datanav=subcatagory::all();
            $data_slider=homeimage::find($id);
            return view('admin.updatehomeimage',compact('data_slider','datanav'));
        }
        //upload updated slder information
        public function upload_updated_slider(Request $request,$id)
        {
            $val=Validator::make($request->all(),
            [
                'himage'=>'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:4000',
                'title'=>'required|string|max:30',
                'script'=>'required|string|max:150',

            ],[
                'himage'=>'the slider image must be rrequired and must be mimes:jpeg,png,jpg,gif,avif files',
            ]);
            if($val->fails())
            {
                return redirect()->back()->withErrors($val)->withInput();
            }
            $data_home=homeimage::find($id);
            $image=$request->himage;
            if($image){
            $imagename=time().'.'.$image->getClientoriginalExtension();
            $request->himage->move('homeimage',$imagename);
            $data_home->himage=$imagename;
            }
            $data_home->title=$request->title;
            $data_home->script=$request->script;
            $data_home->save();
            return redirect()->back()->with('message','You have successfully updatd slider image information');
        }
        //clothe page
        public function clothe_image()
        {
            $datanav=subcatagory::all();
            $clothe=DB::table('catagories')
            ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
            ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
            ->select('catagories.*','subcatagories.*','clothes.*')
            ->orderBy('catagories.name','ASC')
            ->get();
            return view('admin.clothe',compact('clothe','datanav'));
        }
        //add clothe lsit page //cdecription
        public function add_cloth_list()
        {
            $datanav=subcatagory::all();
            $dat_subcat=subcatagory::all();
            return view('admin.add_clothe',compact('dat_subcat','datanav'));
        }
        //upload clothe list
        public function upload_clothe_list(Request $request)
        {
            $val=Validator::make($request->all(),[
                'cimagef'=>'required|image|mimes:jpeg,png,jpg,gif,avif|max:4000',
                'video'=>'nullable|mimes:mp4,mov,avis',
                'brandname'=>'required|string|max:40',
                'cost'=>'required|string|max:255',
                'amount'=>'required|integer',
                'purchasing_price'=>'required|integer',
                'cdecription'=>'required|string|max:255',
               
            ],[
                'cimagef'=>'image of clothe is needed|image must be mimes:jpeg,png,jpg,gif file|image size must not max:4000kb',
            ]);
            if($val->fails())
            {
                return redirect()->back()->withErrors($val)->withInput();
            }
            $data=new clothe;
            $data->subcateg_id=$request->subcateg_id;
            //image upload
            $image=$request->cimagef;
            $imagename=time().'.'.$image->getClientoriginalExtension();
            $request->cimagef->move('clotheimage',$imagename);
            $data->cimagef=$imagename;
            //video uplaod
            $image=$request->video;
            if($image){
            $imagename=time().'.'.$image->getClientoriginalExtension();
            $request->video->move('clothvideo',$imagename);
            $data->video=$imagename;
            }
            //$data->cimagef=$request->cimagef;
            //$data->video=$request->video;
            $data->brandname=$request->brandname;//purchasing_price
            $data->purchasing_price=$request->purchasing_price;//
            
            $data->cost=$request->cost;
            $data->amount=$request->amount;
            $data->cdecription=$request->cdecription;
            $data->status='Active';
            $data->save();
            return redirect()->back()->with('message','You have successfuly added clothe product list GoodLuck!!');
        }
        //actie of clothe lsit
        public function active_clothe_list($id)
        {
            $data_active=clothe::find($id);
            $data_active->status='Activate';
            $data_active->save();
            return redirect()->back()->with('message','You have suuccesffuly upload clothe list');
        }
        //deactieve clothe list
        //actie of clothe lsit
        public function dactive_clothe_list($id)
        {
            $data_active=clothe::find($id);
            $data_active->status='Deactivate';
            $data_active->save();
            return redirect()->back();
        }

        //upsate clothe lsit page
        public function update_clothe_list($id)
        {
            $datanav=subcatagory::all();
            $data_cloth=DB::table('catagories')
            ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
            ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
            ->where('clothes.id','=',$id)
            ->select('catagories.*','subcatagories.*','clothes.*')
            ->get();
            $data_cat=catagory::all();
            $data_subcat=subcatagory::all();
            return view('admin.updateclotheproduct',compact('data_cloth','data_cat','data_subcat','datanav'));
        }
        //upload updated catagory lsit
        public function upload_updated_clothe_list(Request $request,$id)
        {
            $val=Validator::make($request->all(),[
                'quickamount'=>'nullable|integer',
                'cimagef'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
                'video'=>'nullable|mimes:mp4,mov,avis',
                'brandname'=>'required|string|max:40',
                'cost'=>'required|string|max:255',
                'amount'=>'required|integer',
                'cdecription'=>'required|string|max:255',
               
            ],[
                'cimagef'=>'image of clothe is needed|image must be mimes:jpeg,png,jpg,gif file|image size must not max:4000kb',
            ]);
            if($val->fails())
            {
                return redirect()->back()->withErrors($val)->withInput();
            }
            $data=clothe::find($id);
            $data->subcateg_id=$request->subcateg_id;
            // update image upload
            $image=$request->cimagef;
            if($image){
            $imagename=time().'.'.$image->getClientoriginalExtension();
            $request->cimagef->move('clotheimage',$imagename);
            $data->cimagef=$imagename;
            }
            //update video uplaod
            $image=$request->video;
            if($image){
            $imagename=time().'.'.$image->getClientoriginalExtension();
            $request->video->move('clothvideo',$imagename);
            $data->video=$imagename;
            }
            //$data->cimagef=$request->cimagef;
            //$data->video=$request->video;
            $data->amount=$request->amount;
            $data->brandname=$request->brandname;
            $data->amount=($data->amount+$request->quickamount);
            $data->cost=$request->cost;
           
            $data->purchasing_price=$request->purchasing_price;//
            $data->cdecription=$request->cdecription;
            //$data->status='Active';
            $data->save();
            return redirect()->back()->with('message','You have successfuly Updated type of clothe product list GoodLuck!!');
        }
        //tottla user
        public function tot_user()
        {
            $datanav=subcatagory::all();
            $data_user=DB::table('users')
            ->where('users.usertype','=',0)
            ->orderBy('users.name','ASC')
            ->get();
            
            return view('admin.totaluser',compact('data_user','datanav'));
        }
        //update user
        public function update_user($id)
        {
            $datanav=subcatagory::all();
            $data_u=user::find($id);
            return view('admin.user_update',compact('data_u','datanav'));
        }
        //upload updated user data
        public function upload_updated_user(Request $request,$id)
        {
            $val=Validator::make($request->all(),[
              'name'=>'required|string|max:200',
              'email'=>'required|email',
              'phone'=>'required|integer',
              'address'=>'required|string|max:255',
              'password'=>'nullable|string|min:8',
            ]);
            if($val->fails())
            {
                return redirect()->back()->withErrors($val)->withInput();
            }

            $data_up=user::find($id);
            $data_up->name=$request->name;
            $data_up->email=$request->email;
            $data_up->phone=$request->address;
            $data_up->password=Hash::make($request->password);
            $data_up->save();
            return redirect()->back()->with('message','You have successfully updated usr infomation');
        }
            //delete user 
        public function delete_user($id)
        {
            $data_d=user::find($id);
            $data_d->delete();         
            return redirect()->back()->with('message','you have successfully delete user information');
        }
        //total solt house
        public function total_solt($id)
        {
            $datanav=subcatagory::all();
            $solt=DB::table('subcatagories')
            ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
            ->join('solts','clothes.id','=','solts.clothe_id')
            ->select('subcatagories.*','clothes.*','solts.*')
            ->where('subcatagories.id','=',$id)
            ->get();
            $dat=subcatagory::find($id);
            //$leftamount= ($solt->amount-$solt->amount_solt);
            return view('admin.totalsolt',compact('solt','datanav','dat'));
        }
        //adding solt clothe amount
        public function solt_clothes($id)
        {
            $datanav=subcatagory::all();
            $dat_subcateg=subcatagory::all();
            $dat_clothe=clothe::all();
            return view('admin.addsoltclothe',compact('dat_clothe','dat_subcateg','datanav'));
        }
        //upload solt clothe amount
        public function upload_solt_clothe(Request $request)
        {
            $val=Validator::make($request->all(),[
                'amount_solt'=>'required|integer',
                'selling_price'=>'required|integer',
            ],[
                'amount_solt'=>'please inter correct amount solt price',
                'selling_price'=>'please enter correct selling price',
            ]);
            if($val->fails())
            {
                return redirect()->back()->withErrors('$val')->withInput();
            }
            $data_solt=new solt;
            $data_solt->clothe_id=$request->clothe_id;
            $data_solt->amount_solt=$request->amount_solt;
            $data_solt->selling_price=$request->selling_price;
            $data_solt->save();
            return redirect()->back()->with('message','You have successfully upload amount you solt,Good Luck');

        }
        //adding solt clothes
        public function addingsoltclothe($id)
        
        {
            $datanav=subcatagory::all();
            $add_solt=DB::table('subcatagories')
            ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
            ->leftjoin('solts','clothes.id','=','solts.clothe_id')
            ->select('subcatagories.*','clothes.*','solts.*')
            ->where('subcatagories.id','=',$id)
            ->get();
        }

        //a;l solt 

        public function all_solt()
        {
            $startdate=Carbon::now()->startOfDay();
            $endDate=Carbon::now()->endOfDay();

            $datanav=subcatagory::all();
            $tsolt=DB::table('subcatagories')
            ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
            ->join('solts','clothes.id','=','solts.clothe_id')
            ->select('subcatagories.*','clothes.*','solts.*')
            ->whereBetween('solts.created_at',[$startdate,$endDate])
            ->orderBy('solts.created_at','DESC')
            ->get();
            return view('admin.allsolt',compact('datanav','tsolt'));
        }
        //all solt clothes
        public function allsoltclothes()
        {
            $datanav=subcatagory::all();
            $dat_subcateg=subcatagory::all();
            $dat_clothe=clothe::all();
            return view('admin.addallsoltclothe',compact('dat_subcateg','dat_clothe','datanav'));
        }
        //upload ll solt clothes
        public function uploadallsolt_clothes(Request $request)
        {
            $val=Validator::make($request->all(),[
                'amount_solt'=>'required|integer',
                'selling_price'=>'required|integer',

            ]);
            if($val->fails())
            {
                return redirect()->back()->withErrors($val)->withInput();
            }
            $data_solt=new solt;
            $data=clothe::find($request->clothe_id);
            if($data->amount-$request->amount_solt < 0)
            {
                return redirect()->back()->withErrors('amount in your stock is less than amount you have solt please recharge your stock amount')->withinput();
            }

            $data->amount=($data->amount-$request->amount_solt);
            $data->save();
            $data_solt->clothe_id=$request->clothe_id;
            $data_solt->selling_price=$request->selling_price;
            $data_solt->amount_solt=$request->amount_solt;
            $data_solt->left_amount=$data->amount;
            $data_solt->profit=$request->amount_solt*($request->selling_price-$data->purchasing_price);
            $data_solt->save();
            return redirect()->back()->with('message','you have successfully added solt amount');

        }
        //to fetch weekly solt in all solt
        public function week_solt()
        {
            $startdate=Carbon::now()->startOfWeek();
            $endDate=Carbon::now()->endOfWeek();

            $datanav=subcatagory::all();
            $tsolt=DB::table('subcatagories')
            ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
            ->join('solts','clothes.id','=','solts.clothe_id')
            ->select('subcatagories.*','clothes.*','solts.*')
            ->whereBetween('solts.created_at',[$startdate,$endDate])
            ->orderBy('solts.created_at','DESC')
            ->get();
            return view('admin.weeklysolt',compact('datanav','tsolt'));
        }
        //monthly se;l report
        public function monthly_sellreport()
        {
            $startdate=Carbon::now()->startOfMonth();
            $endDate=Carbon::now()->endOfMonth();

            $datanav=subcatagory::all();
            $tsolt=DB::table('subcatagories')
            ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
            ->join('solts','clothes.id','=','solts.clothe_id')
            ->select('subcatagories.*','clothes.*','solts.*')
            ->whereBetween('solts.created_at',[$startdate,$endDate])
            ->orderBy('solts.created_at','DESC')
            ->get();
            return view('admin.monthlysolt',compact('datanav','tsolt'));
        }
        public function yearly_sell_report()
        {
            $startdate=Carbon::now()->startOfYear();
            $endDate=Carbon::now()->endOfYear();

            $datanav=subcatagory::all();
            $tsolt=DB::table('subcatagories')
            ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
            ->join('solts','clothes.id','=','solts.clothe_id')
            ->select('subcatagories.*','clothes.*','solts.*')
            ->whereBetween('solts.created_at',[$startdate,$endDate])
            ->orderBy('solts.created_at','DESC')
            ->get();
            return view('admin.yearlysolt',compact('datanav','tsolt'));
        }
        //admin controllre
        public function add_admin()
        {
            $datanav=subcatagory::all();
            return view('admin.addadmin',compact('datanav'));
        }
        //admin page
        public function admin_page()
        {
            $data_admin=user::where('users.usertype','=',1)->get();
            $datanav=subcatagory::all();
            return view('admin.adminpage',compact('datanav','data_admin'));
        }
        //upload admin
        public function upload_admin(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'address' => 'required|string|max:255',
                'profile_photo_path' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
         
                //'password' => 'required|string|min:8|confirmed',
             ]);
             
             if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
             }
         
         
         //$dat_vi=village::find($id);
          $dat=new user;
          $dat->name=$request->name;
          $dat->email=$request->email;
          $dat->phone=$request->phone;
          ///$dat_man=Auth::user()->id;
         
          $dat->usertype='1';
          $dat->password=bcrypt($request->password);
          $dat->address=$request->address;
         //$dat->Staff_id=$dat_man;
         //to upload profile image
         $profile_photo_path=$request->profile_photo_path;
         if($profile_photo_path){
         $imagename=time().'.'.$profile_photo_path->getClientoriginalExtension();
         $request->profile_photo_path->move('adminprofile',$imagename);
         $dat->profile_photo_path=$imagename;
         }
         //$dat_vi->ass_agent=$dat->id;
         //$dat_vi->save();
         $dat->save();
         return redirect()->back()->with('message','you have successfully give Admin access to $request->name');

        }
        //delte admin formaacess
        public function delete_admin($id)
        {
            $data=user::find($id);
            $data->delete();
            return redirect()->back()->with('message','you have successfully delted but not delete more');
        }
        //updte controler
        public function update_admin($id)
        {
            $datanav=subcatagory::all();
            $data=user::find($id);
            return view('admin.updateadmin',compact('data','datanav'));
        }
        //upload updted amdmin controler
        public function upload_update_admin(Request $request,$id)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'phone' => 'required|integer',
                'password' => 'nullable|string|min:8|confirmed',
                'address' => 'required|string|max:255',
                'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
         
                //'password' => 'required|string|min:8|confirmed',
             ]);
             
             if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
             }
         
         
         //$dat_vi=village::find($id);
          $dat=user::find($id);
          $dat->name=$request->name;
          $dat->email=$request->email;
          $dat->phone=$request->phone;
          ///$dat_man=Auth::user()->id;
         
          //$dat->usertype='1';
          $dat->password=bcrypt($request->password);
          $dat->address=$request->address;
         //$dat->Staff_id=$dat_man;
         //to upload profile image
         $profile_photo_path=$request->profile_photo_path;
         if($profile_photo_path){
         $imagename=time().'.'.$profile_photo_path->getClientoriginalExtension();
         $request->profile_photo_path->move('adminprofile',$imagename);
         $dat->profile_photo_path=$imagename;
         }
         //$dat_vi->ass_agent=$dat->id;
         //$dat_vi->save();
         $dat->save();
         return redirect()->back()->with('message','you have successfully give Updated access to $request->name');
        }
        //customer usertype=3 page
        public function customer_page()
        {
            $datanav=subcatagory::all();
            $data_cust=user::where('users.usertype','=',2)->get();
            return view('admin.customerpage',compact('datanav','data_cust'));
        }
        public function add_customer()
        {
            $datanav=subcatagory::all();
            return view('admin.addcustomer',compact('datanav'));
        }
        //upload custoemr
        public function upload_customer(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'address' => 'required|string|max:255',
                'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
         
                //'password' => 'required|string|min:8|confirmed',
             ]);
             
             if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
             }
         
         
         //$dat_vi=village::find($id);
          $dat=new user;
          $dat->name=$request->name;
          $dat->email=$request->email;
          $dat->phone=$request->phone;
          ///$dat_man=Auth::user()->id;
         
          $dat->usertype='2';
          $dat->password=bcrypt($request->password);
          $dat->address=$request->address;
         //$dat->Staff_id=$dat_man;
         //to upload profile image
         $profile_photo_path=$request->profile_photo_path;
         if($profile_photo_path){
         $imagename=time().'.'.$profile_photo_path->getClientoriginalExtension();
         $request->profile_photo_path->move('customerprofile',$imagename);
         $dat->profile_photo_path=$imagename;
         }
         //$dat_vi->ass_agent=$dat->id;
         //$dat_vi->save();
         $dat->save();
         return redirect()->back()->with('message','you have successfully add  Customer Good Luck!!');

        }
        //update ciustoemr
        public function update_customer($id)
        {
            $datanav=subcatagory::all();
            $data=user::find($id);
            return view('admin.updatecustomer',compact('datanav','data'));
        }
        //upload updated custoemr
        public function upload_update_customer(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required',
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'required|string|max:255',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
     
            //'password' => 'required|string|min:8|confirmed',
         ]);
         
         if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
         }
     
     
     //$dat_vi=village::find($id);
      $dat=user::find($id);
      $dat->name=$request->name;
      $dat->email=$request->email;
      $dat->phone=$request->phone;
      ///$dat_man=Auth::user()->id;
     
      //$dat->usertype='1';
      $dat->password=bcrypt($request->password);
      $dat->address=$request->address;
     //$dat->Staff_id=$dat_man;
     //to upload profile image
     $profile_photo_path=$request->profile_photo_path;
     if($profile_photo_path){
     $imagename=time().'.'.$profile_photo_path->getClientoriginalExtension();
     $request->profile_photo_path->move('customerprofile',$imagename);
     $dat->profile_photo_path=$imagename;
     }
     //$dat_vi->ass_agent=$dat->id;
     //$dat_vi->save();
     $dat->save();
     return redirect()->back()->with('message','you have successfully give Updated access to $request->name');
    }

    //dube controller
    public function dube()
    {
        $datanav=subcatagory::all();
        $data_dube=dube::orderBy('created_at','DESC')->orderBy('updated_at','ASC')->get();
        return view('admin.dube',compact('datanav','data_dube'));
    }

    ///addd dube
    public function add_dube()
    {
        $datanav=subcatagory::all();
        return view('admin.adddube',compact('datanav'));
    }
    //upload dube
    public function upload_dube(Request $request)
    {
        $val=Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'phone'=>'nullable|string',
            'address'=>'nullable|string',
            'description'=>'required|string|max:255',
            'amount_dube'=>'required|integer',


        ]);
        if($val->fails())
        {
            return redirect()->back()->withErrors($val)->withInput();
        }
        $data_dube=new dube;
        $data_dube->name=$request->name;
        $data_dube->phone=$request->phone;
        $data_dube->amount_dube=$request->amount_dube;

        $data_dube->address=$request->address;
        $data_dube->description=$request->description;
        $data_dube->save();
        return redirect()->back()->with('message','you have successfully  added you dube');

    }
    //update credit
    public function update_credit($id)
    {
        $datanav=subcatagory::all();
        $data_credit=dube::find($id);
        return view('admin.updatecredit',compact('data_credit','datanav'));
    }
    //upload updated admin
    public function upload_updated_credit(Request $request,$id)
    {
        $data_dube=dube::find($id);
        $data_dube->name=$request->name;
        $data_dube->phone=$request->phone;
        $data_dube->amount_dube=$request->amount_dube;
        $data_dube->address=$request->address;
        $data_dube->amount_dube=($data_dube->amount_dube-$request->amount_pay);
        $data_dube->address=$request->address;
        $data_dube->description=$request->description;
        $data_dube->save();
        return redirect()->back()->with('message','you have successfully update amount dubes');

    }
    //admin controller for rdering
    
    //order page
    public function order_admin()
    {
        $datanav = subcatagory::all();

        $startdate=Carbon::now()->startOfDay();
        $endDate=Carbon::now()->endOfDay();


            $hou=DB::table('orders')->get();
            $dat=DB::table('orders')
                   ->join('users','users.id','=','orders.user_id')
                   ->select(DB::raw('DISTINCT(orders.user_id)'),'users.*')
                   ->where(function ($query) {
                    $query->where('orders.status', '=', 'ordered')
                        ->orWhere('orders.status', '=', 'delivered');
                })
                ->where('orders.status', '!=', 'requested')
                   ->whereBetween('orders.created_at',[$startdate,$endDate])
                   ->orderBy('orders.created_at','DESC')
                   ->get();

        return view('admin.orderadmin',compact('datanav','dat'));
    }

    //order detail
    public function order_detail($id)
    {

        $startdate=Carbon::now()->startOfDay();
        $endDate=Carbon::now()->endOfDay();

        $data_cu=user::find($id);
        $datanav = subcatagory::all();
        $data_order=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->join('orders','clothes.id','=','orders.clothe_id')
        ->select('catagories.*','subcatagories.*','clothes.*','orders.*')
        ->whereBetween('orders.created_at',[$startdate,$endDate])
        ->where('orders.user_id','=',$id)
        ->where(function ($query) {
            $query->where('orders.status', '=', 'ordered')
                ->orWhere('orders.status', '=', 'delivered');
        })
        ->where('orders.status', '!=', 'requested')
       ->orderBy('orders.created_at','DESC')
        ->get();

        return view('admin.orderdetail',compact('datanav','data_order','data_cu'));
       


    }

    //weekly order page
    public function weekly_order()
    {
        $datanav = subcatagory::all();

        $startdate=Carbon::now()->startOfWeek();
        $endDate=Carbon::now()->endOfWeek();


            $hou=DB::table('orders')->get();
            $dat=DB::table('orders')
                   ->join('users','users.id','=','orders.user_id')
                   ->select(DB::raw('DISTINCT(orders.user_id)'),'users.*')
                   ->where('orders.status','=','ordered')
                   ->whereBetween('orders.created_at',[$startdate,$endDate])
                   ->orderBy('orders.created_at','ASC')
                   ->get();

        return view('admin.orderadminweek',compact('datanav','dat'));
    }
    

    //montthly ordder
    public function monthly_order()
    {
        $datanav = subcatagory::all();

        $startdate=Carbon::now()->startOfMonth();
        $endDate=Carbon::now()->endOfMonth();


            $hou=DB::table('orders')->get();
            $dat=DB::table('orders')
                   ->join('users','users.id','=','orders.user_id')
                   ->select(DB::raw('DISTINCT(orders.user_id)'),'users.*')
                   ->where('orders.status','=','ordered')
                   ->whereBetween('orders.created_at',[$startdate,$endDate])
                   ->orderBy('orders.created_at','ASC')
                   ->get();

        return view('admin.orderadminMonth',compact('datanav','dat'));
    }

    //weekly order dertial 
    

    public function weekly_orderdetail($id)
    {

        $startdate=Carbon::now()->startOfWeek();
        $endDate=Carbon::now()->endOfWeek();


        $data_cu=user::find($id);
        $datanav = subcatagory::all();
        $data_order=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->join('orders','clothes.id','=','orders.clothe_id')
        ->select('catagories.*','subcatagories.*','clothes.*','orders.*')
        ->where('orders.user_id','=',$id)
        ->where(function ($query) {
            $query->where('orders.status', '=', 'ordered')
                ->orWhere('orders.status', '=', 'delivered');
        })
        ->where('orders.status', '!=', 'requested')
        ->whereBetween('orders.created_at',[$startdate,$endDate])
       ->orderBy('orders.created_at','DESC')
        ->get();

        return view('admin.orderdetailweekly',compact('datanav','data_order','data_cu'));
       


    }
    


    //order detail of monthly

    public function monthly_orderdetail($id)
    {
        $startdate=Carbon::now()->startOfMonth();
        $endDate=Carbon::now()->endOfMonth();


        $data_cu=user::find($id);
        $datanav = subcatagory::all();
        $data_order=DB::table('catagories')
        ->join('subcatagories','catagories.id','=','subcatagories.categ_id')
        ->join('clothes','subcatagories.id','=','clothes.subcateg_id')
        ->join('orders','clothes.id','=','orders.clothe_id')
        ->select('catagories.*','subcatagories.*','clothes.*','orders.*')
        ->where('orders.user_id','=',$id)
        ->where(function($query){
        
           $query->where('orders.status','=','ordered')
            ->orwhere('orders.status','=','delivered');
        })
        ->where('orders.status', '!=', 'requested')
        ->whereBetween('orders.created_at',[$startdate,$endDate])
       ->orderBy('orders.created_at','DESC')
        ->get();

        return view('admin.orderdetailmonthly',compact('datanav','data_order','data_cu'));   
    }
    public function updateStatus(Request $request, $id)
    {
        // Find the order in the database
        $order = Order::find($id);
    
        if ($order) {
            $status = $request->input('status');
    
            // Update the status of the order
            if ($status === 'delivered') {
                $order->status = 'delivered';
            } else {
                $order->status = 'ordered';
            }
    
            $order->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Order not found.']);
    }

}
 