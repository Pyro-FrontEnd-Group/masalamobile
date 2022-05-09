<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use alert;
use Validator;
use Redirect;
use View;
use Session;
use Carbon\Carbon;

class LoginController extends Controller
{
public function login_process(Request $request)
{
  if ($request->method() == "POST") 
  {
      $email = $request->input('email');
      $password = $request->input('password');

      $detail = DB::table('users')->where('email',$email)->where('password',$password)->get();
      $count = DB::table('users')->where('email',$email)->where('password',$password)->get()->count();

    // $detail = DB::table('users')->where('email',$email)->where('password',md5($password))->where('type','user')->get();
    // $count = DB::table('users')->where('email',$email)->where('password',md5($password))->where('type','user')->get()->count();
    // echo $count."####";
    // if($count = 1){
      if($count > 0){

        foreach($detail as $value){
            $id = $value->userid;
            $roleid = $value->roleid;
            $rss_access = $value->rss_access;
          }

        Session::put('userid',$id);
        Session::put('roleid',$roleid);
        Session::put('rss_access',$rss_access);
        return redirect('dashboard');
    }
    else{
      return redirect('login')->with('success2', 'Incorrect Username or Password Please try Again');
    }
  }
  else{
    return redirect('login')->with('success2', 'Something went wrong, Please try again!!');

  }


}
 public function change_password(Request $request){
  
  $old_password = $request->input('old_password');
  $new_password = $request->input('new_password');
  $confirm_password = $request->input('confirm_password');


   $old_count = DB::table('users')->where('password',$old_password)->get()->count();

    if($old_count <= 0){
        return redirect()->back()->with('success2', 'Old Password Missmatch');
       // return redirect()->back()->withErrors(['old_password' => 'Old Password Missmatch']);
    }

    $this->validate($request, [
     
    'new_password' => 'required_with:confirm_password|same:confirm_password',

    ]);

    DB::table('users')->where('password',$old_password)->update(['password' => $new_password]);

    return redirect()->back()->with('success', 'Password changed Successfully');
 

}
}