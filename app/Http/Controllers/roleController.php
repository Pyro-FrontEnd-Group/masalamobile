<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Validator;
use Redirect;
use View;
use Carbon\Carbon;
class roleController extends Controller
{

    public function add_role(Request $request)
    {
        $rolename = $request->rolename;
        $status = $request->status;
        if($status == "")
        {
            $status = "inactive";
        }
        $add_role = DB::table('role')->insert(['rolename' => $rolename, 'status' => $status]);
        $get_moduleid = DB::table('module')->get();
        foreach($get_moduleid as $module)
        {

        }
        return redirect('roles')->with('success', 'Role Added Successfully'); 
    }
    public function delete_role($id)
    {
        $delete_role = DB::table('role')->where('roleid',$id)->delete();
        return redirect('roles')->with('success2', 'Role Deleted Successfully'); 
    }
    public function manage_permission($id)
    {
       
        $role_name = DB::table('role')->where('roleid',$id)->get();
        // $role_name = $role_name->rolename;
        // $role_permission = DB::table('permissions')->where('role_id',$id)->get();
        // $role_modules = DB::table('module')->get();
        $module_permission =  DB::table('module')->leftjoin('permissions',function($join) use($id)
        {
            $join->on('module.module_id', '=', 'permissions.module_id');
            $join->where('permissions.role_id','=',$id);
        })->get(['module.module_id AS moduleid', 'permissions.*','module.*']);

        return view('back_end.managepermission',['role_name' => $role_name],['module_permission'=>$module_permission]);
    
    }
    public function edit_rolepermission(Request $request)
    {
       
       $module_id = $request->module_id;
       $module_id_count = count($module_id);
    //    $add_perm = $request->add;
    //    $delete_perm = $request->delete;
    //    $edit_perm = $request->edit;
    //    $access_perm = $request->access;
       $role_id = $request->roleid;
       for($i=0;$i<$module_id_count;$i++)
       {
        $id = $module_id[$i];
        $add = $request->input('add_'.$id);
        $perm_count = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->get();
        if($perm_count->count() == '0')
        {
            $insert_query = DB::table('permissions')->insert([ 
                'role_id' => $role_id,
                'module_id' => $id ]);
                if($add == '0')
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['add' => '1']);
                }
                else
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['add' => '0']);
                }
                if($request->input('edit_'.$id) == '0')
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['edit' => '1']);
                }
                else
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['edit' => '0']);
                }
                if($request->input('access_'.$id) == '0')
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['access' => '1']);
                }
                else
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['access' => '0']);
                }
                if($request->input('delete_'.$id) == '0')
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['delete' => '1']);
                }
                else
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['delete' => '0']);
                }
        }
        else
        {
            if($add == '0')
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['add' => '1']);
                }
                else
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['add' => '0']);
                }
                if($request->input('edit_'.$id) == '0')
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['edit' => '1']);
                }
                else
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['edit' => '0']);
                }
                if($request->input('access_'.$id) == '0')
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['access' => '1']);
                }
                else
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['access' => '0']);
                }
                if($request->input('delete_'.$id) == '0')
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['delete' => '1']);
                }
                else
                {
                    $insert_query = DB::table('permissions')->where('role_id',$role_id)->where('module_id',$id)->update(['delete' => '0']);
                }
        }
        
       }
    return redirect('roles')->with('success', 'Permissions Updated Successfully'); 
    
    }
    public function add_user(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $status = $request->status;
        $roleid = $request->roleid;
        $email = $request->email;
        $rss_id = $request->rssid;
        $rss_ids = '';
        $count = 1;
        foreach($rss_id as $rssid=>$value)
        {
            if($count != '1')
            $rss_ids.=",";
            $rss_ids .= $value;
            $count++;
        }
        if($status == "")
        {
            $status = "inactive";
        }
        $check_user = DB::table('users')->where('username',$username)->get();
        $check_email = DB::table('users')->where('email',$email)->get(); 

        if($check_user->count() != '0')
        {
            return redirect('create-user')->with('success2', 'Username Already Exits'); 
        }
        if($check_email->count() != '0')
        {
            return redirect('create-user')->with('success2', 'Email Already Exits'); 
        }

        
        $add_role = DB::table('users')->insert(['username' => $username, 'password' => $password, 'status' => $status, 'roleid' => $roleid,'email' => $email,'rss_access' => $rss_ids]);
    
        return redirect('create-user')->with('success', 'User Added Successfully'); 
    }
    public function delete_user($id)
    {
        $delete_role = DB::table('users')->where('userid',$id)->delete();
        return redirect('create-user')->with('success2', 'User Deleted Successfully'); 
    }
    public function get_user_rss(Request $request)
    {
        $rss_access =  $request->rss;
        $access_rss = explode (",", $rss_access); 
        $get_rss_cat = DB::table('rss')->join('category','rss.categoryid','=','category.categoryid')->whereIn('rss.rssid',$access_rss)->get();

        return response()->json(array('success' => true, 'value'=> $get_rss_cat));

    }
    
    
    

    
    

}

    