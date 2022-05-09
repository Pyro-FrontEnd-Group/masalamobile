<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Validator;
use Redirect;
use View;
use Carbon\Carbon;

class postController extends Controller
{

public function new_post(Request $request)
    {

     function generate_uuid() {
            return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0C2f ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
            );
          
          }
    
          $idd = generate_uuid();
          $images="";
          // if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/post-img/" . $idd.".".pathinfo($_FILES["images"]["name"], PATHINFO_EXTENSION))){
          //       $images = "image/post-img/" . $idd.".".pathinfo($_FILES["images"]["name"], PATHINFO_EXTENSION);
          
          // }
          if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/post-img/" .$_FILES["images"]["name"]))
          {
            $images= "image/post-img/" . $_FILES["images"]["name"];
          }

    $date = Carbon::now()->toDateTimeString();
    $category = $request->input('category');
    $title = $request->input('title');
    $description = $request->input('description');
    $rss = $request->input('rss');
    $status = $request->input('status');
    $trending = $request->input('trending');
    $hotcontent = $request->input('hotcontent');
    // $mobile = $request->input('mobile');
    // $lan = $request->input('lan');
    // $lat = $request->input('lat');
    // $map = $request->input('map');
    // $mrp = $request->input('mrp');
    // $status = $request->input('status');


    // $images=array();
    // if($files=$request->file('images')){
    // foreach($files as $file){
    // $image_path= date('mdYHis') . uniqid() . $file->getClientOriginalName();
    // $file->move('image/product_img', $image_path);
    // $images[]=$image_path;
    
    DB::table('post')->insert(['categoryid' => $category,'guid' => '0','postlink' => '0','postintro' => '0','Author' => '0','keywords' => '0','hitcount' => '0', 'posttitle' => $title,'description' => $description,'publishedon' => $date ,'imagepath' => $images,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'created_at' => $date,'updated_at' => $date]);
    //echo  $test;

    //INSERT INTO `post`(`postid`, `categoryid`, `guid`, `posttitle`, `description`, `postlink`, `postintro`, `Author`, `publishedon`, `keywords`, `imagepath`, `rssid`, `hitcount`, `status`, `created_at`, `updated_at`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13],[value-14],[value-15],[value-16])

return redirect('posts')->with('success', 'Posts Added Successfully'); 
}

public function edit_post(Request $request)
{


    $date = Carbon::now()->toDateTimeString();
    $post_id = $request->input('post_id');
    $category = $request->input('category');
    $title = $request->input('posttitle');
    $description = $request->input('description');
    $rss = $request->input('rss_name');
    $status = $request->input('status');
    $trending = $request->input('trending');
    $hotcontent = $request->input('hotcontent');

    $images=$request->file('images');

    if (empty($images)) {
        DB::table('post')->where('postid', $post_id)->update(['categoryid' => $category,'guid' => '0','postlink' => '0','postintro' => '0','Author' => '0','keywords' => '0','hitcount' => '0', 'posttitle' => $title,'description' => $description,'publishedon' => $date ,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'updated_at' => $date]);
    } else {
    function generate_uuid() {
            return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0C2f ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
            );
          
          }
    
          $idd = generate_uuid();
          // $images="";
          // if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/post-img/" . $idd.".".pathinfo($_FILES["images"]["name"], PATHINFO_EXTENSION))){
          //       $images = "image/post-img/" . $idd.".".pathinfo($_FILES["images"]["name"], PATHINFO_EXTENSION);
          
          // }
          if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/post-img/" .$_FILES["images"]["name"]))
          {
            $images= "image/post-img/" . $_FILES["images"]["name"];
          }
    // $images=array();
    // if($files=$request->file('images')){
    // foreach($files as $file){
    // $image_path= date('mdYHis') . uniqid() . $file->getClientOriginalName();
    // $file->move('image/post-img',$image_path);
    // $images[]=$image_path;
  
    DB::table('post')->where('postid', $post_id)->update(['categoryid' => $category,'guid' => '0','postlink' => '0','postintro' => '0','Author' => '0','keywords' => '0','hitcount' => '0', 'posttitle' => $title,'description' => $description,'publishedon' => $date ,'imagepath' => $images,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'updated_at' => $date]);

    // }
}
    // }

    
    
return redirect('posts')->with('success', 'Post Updated Successfully'); 
}


public function delete_post($id)
    {
        $del = DB::table('post')->where('postid', $id)->get();

        DB::table('post')->where('postid',$id)->delete();

        return redirect('posts')->with('success2', 'Posts Deleted Successfully'); 
    }

public function add_rss_feed(Request $request)
    {

    $date = Carbon::now()->toDateTimeString();
    $category = $request->input('category');
    $rssname = $request->input('rssname');
    $url = $request->input('url');
    $duration = $request->input('duration');
    $status = $request->input('status');
    
    DB::table('rss')->insert(['categoryid' => $category,'rssname' => $rssname,'sourceurl' => $url,'status' => $status,'lastrun' => $date,'created_at' => $date,'updated_at' => $date]);
    //echo  $test;

    //INSERT INTO `rss`(`rssid`, `rssname`, `sourceurl`, `status`, `lastrun`, `created_at`, `updated_at`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])

return redirect('rss-feed')->with('success', 'RSS Added Successfully'); 
}

public function edit_rss_feed(Request $request)
    {

    $date = Carbon::now()->toDateTimeString();
    $rssname = $request->input('rssname');
    $category = $request->input('category');
    $url = $request->input('url');
    $rss_id = $request->input('rss_id');
    $status = $request->input('status');
    
    DB::table('rss')->where('rssid', $rss_id)->update(['categoryid' => $category,'rssname' => $rssname,'sourceurl' => $url,'status' => $status,'lastrun' => $date,'updated_at' => $date]);
    //echo  $test;

    //INSERT INTO `rss`(`rssid`, `rssname`, `sourceurl`, `status`, `lastrun`, `created_at`, `updated_at`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])

return redirect('rss-feed')->with('success', 'RSS Updated Successfully'); 
}
public function delete_rss($id)
    {
        $del = DB::table('rss')->where('rssid', $id)->get();

        DB::table('rss')->where('rssid',$id)->delete();

        return redirect('rss-feed')->with('success2', 'RSS Deleted Successfully'); 
    }

public function add_visualstory(Request $request)
    {

function generate_uuid() {
            return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0C2f ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
            );
          
          }
    
          $idd = generate_uuid();
          $image="";
          // if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/post-img/" . $idd.".".pathinfo($_FILES["images"]["name"], PATHINFO_EXTENSION))){
          //       $images = "image/post-img/" . $idd.".".pathinfo($_FILES["images"]["name"], PATHINFO_EXTENSION);
          
          // }
          if(move_uploaded_file($_FILES["image"]["tmp_name"], "image/visual-story/" .$_FILES["image"]["name"]))
          {
            $image= "image/visual-story/" . $_FILES["image"]["name"];
          }

          $idd = generate_uuid();
          $images="";
          // if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/post-img/" . $idd.".".pathinfo($_FILES["images"]["name"], PATHINFO_EXTENSION))){
          //       $images = "image/post-img/" . $idd.".".pathinfo($_FILES["images"]["name"], PATHINFO_EXTENSION);
          
          // }
          // if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/stories/" .$_FILES["images"]["name"]))
          // {
          //   $images= "image/stories/" . $_FILES["images"]["name"];
          // }
            // $images=array();
            // if($files=$request->file('images')){
            // foreach($files as $file){
            // $name= date('mdYHis') . uniqid() . $file->getClientOriginalName();
            // $file->move('image',$name);
            // $images[]=$name;


     $date = Carbon::now()->toDateTimeString();
    $category = $request->input('category');
    $stype = $request->input('stype');
    $sdescription = $request->input('sdescription');
    $status = $request->input('status');
    $title = $request->input('title');
    $description = $request->input('description');


    // $images=array();
    // if($files=$request->file('images')){
    // foreach($files as $file){
    // $image_path= date('mdYHis') . uniqid() . $file->getClientOriginalName();
    // $file->move('image/product_img', $image_path);
    // $images[]=$image_path;
    
    DB::table('v_stories')->insert(['categoryid' => $category,'storytitle' => $stype,'description' => $sdescription,'imagelink' => $image,'status' => $status,'created_at' => $date,'updated_at' => $date]);

     $addressid =DB::getPdo()->lastInsertId();
 foreach($title as $key => $n) 
    {
    $titles = $title[$key];
    $descriptions = $description[$key];

    request()->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $images=array();
        if($files=$request->file('images'))
        {
            foreach($files as $file)
            {
                $name= date('mdYHis') . uniqid() . $file->getClientOriginalName();
                $file->move('image',$name);
                $images[]=$name;       

                 $description = $request->input('description');
                    DB::table('v_storiestrans')->insert(['storyid' => $addressid,'t_title' => $stype,'t_description' => $sdescription,'t_imagelink' => $name,'t_status' => $status]);
            }
            //return redirect()->back()->with('success', 'Image Uploaded Successfully');
        }
   
}

    //echo  $test;

    //INSERT INTO `post`(`postid`, `categoryid`, `guid`, `posttitle`, `description`, `postlink`, `postintro`, `Author`, `publishedon`, `keywords`, `imagepath`, `rssid`, `hitcount`, `status`, `created_at`, `updated_at`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13],[value-14],[value-15],[value-16])

// return redirect('visualstories')->with('success', 'Added Visual Stories Successfully'); 

return redirect('visualstories')->with('success', 'visualstories Added Successfully'); 
}

public function publish($id){
  DB::table('tbl_storelocator')->where('store_id',$id)->update(['status' => 'Active']);
  return redirect('store_location')->with('success', 'Activated in Store Location');
}

public function un_publish($id){
  DB::table('tbl_storelocator')->where('store_id',$id)->update(['status' => 'Inactive']);
  return redirect('store_location')->with('success2', 'Inactivated Store Location'); 
}

}