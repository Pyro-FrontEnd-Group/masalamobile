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
    $postlink = str_replace(' ','-',$title);
    $postlink = preg_replace('/[^A-Za-z0-9\-]/', '', $postlink);
    $scheduleinput = $request->input('schedule_input');
    $schedule = $request->input('schedule');
    $noti_input = $request->input('notification');
    if($status == "Publish")
    {
        DB::table('post')->insert(['categoryid' => $category,'postlink' => $postlink, 'posttitle' => $title,'description' => $description,'publishedon' => $date ,'imagepath' => "https://mobilemasala.com/".$images,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'created_at' => $date,'published_date' => $date,'schedule' => $schedule, 'schedule_date' => $scheduleinput,'noti_input' => $noti_input]);
        if($noti_input == '1')
        {
            return redirect('send_noti'); 
        }
        else
        {
            return redirect('posts'); 
        }
    }
    else
    {
        DB::table('post')->insert(['categoryid' => $category,'postlink' => $postlink, 'posttitle' => $title,'description' => $description,'publishedon' => $date ,'imagepath' => "https://mobilemasala.com/".$images,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'created_at' => $date,'updated_at' => $date,'schedule' => $schedule, 'schedule_date' => $scheduleinput,'noti_input' => $noti_input]);
    }
    // if($noti_input == '1')
    // {
    //     DB::table('post')->insert(['categoryid' => $category,'postlink' => $postlink, 'posttitle' => $title,'description' => $description,'publishedon' => $date ,'imagepath' => "https://mobilemasala.com/".$images,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'created_at' => $date,'updated_at' => $date,'schedule' => $schedule, 'schedule_date' => $scheduleinput,'noti_input' => $noti_input]);
    // }
    // else{
    //     DB::table('post')->insert(['categoryid' => $category,'postlink' => $postlink, 'posttitle' => $title,'description' => $description,'publishedon' => $date ,'imagepath' => "https://mobilemasala.com/".$images,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'created_at' => $date,'updated_at' => $date,'schedule' => $schedule, 'schedule_date' => $scheduleinput,'noti_input' => $noti_input]);
    // }
    return redirect('posts')->with('success', 'Posts Added Successfully'); 
}
public function add_new_paparazzi_post(Request $request)
    {

    $date = Carbon::now()->toDateTimeString();
    $title = $request->input('title');
    $video_link = $request->input('video_link');
    $description = $request->input('description');
    $status = $request->input('status');
    $postlink = str_replace(' ','-',$title);
    $postlink = preg_replace('/[^A-Za-z0-9\-]/', '', $postlink);
    if($status == 'Publish')
    {
        DB::table('paparazzi_post')->insert(['postlink' => $postlink, 'posttitle' => $title,'description' => $description,'videopath' =>$video_link ,'status' => $status,'created_at' => $date,'published_date' => $date]);
    }
    else{
        DB::table('paparazzi_post')->insert(['postlink' => $postlink, 'posttitle' => $title,'description' => $description,'videopath' =>$video_link ,'status' => $status,'created_at' => $date]);
    }
    return redirect('paparazzi')->with('success', 'Paparazzi Posts Added Successfully'); 
}
public function edit_paparazzi_post(Request $request)
    {
    $post_id = $request->input('postid');
    $date = Carbon::now()->toDateTimeString();
    $title = $request->input('posttitle');
    $video_link = $request->input('video_link');
    $description = $request->input('description');
    $status = $request->input('status');
    $check_status = $request->input('checkstatus');
    $postlink = str_replace(' ','-',$title);
    $postlink = preg_replace('/[^A-Za-z0-9\-]/', '', $postlink);
    if($check_status == "Publish")
    {
        DB::table('paparazzi_post')->where('postid',$post_id)->update(['postlink' => $postlink, 'posttitle' => $title,'description' => $description,'videopath' =>$video_link ,'status' => $status,'updated_at' => $date]);
    }
    else if($status == 'Publish'){
        DB::table('paparazzi_post')->where('postid',$post_id)->update(['postlink' => $postlink, 'posttitle' => $title,'description' => $description,'videopath' =>$video_link ,'status' => $status,'published_date' => $date,'updated_at' => $date]);
    }
    else{
        DB::table('paparazzi_post')->where('postid',$post_id)->update(['postlink' => $postlink, 'posttitle' => $title,'description' => $description,'videopath' =>$video_link ,'status' => $status,'updated_at' => $date]);
    }
    return redirect('paparazzi')->with('success', 'Paparazzi Posts Updated Successfully'); 
}

public function delete_paparazzi_post($id)
    {
        $del = DB::table('paparazzi_post')->where('postid', $id)->get();

        DB::table('paparazzi_post')->where('postid',$id)->delete();

        return redirect('paparazzi')->with('success2', 'Paparazzi Deleted Successfully'); 
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
    $postlink = str_replace(' ','-',$title);
    $postlink = preg_replace('/[^A-Za-z0-9\-]/', '', $postlink);
    $scheduleinput = $request->input('schedule_input');
    $schedule = $request->input('schedule');
    $check_status = $request->input('checkstatus');
    $images=$request->file('images');
    $noti_input = $request->input('notification');


    if (empty($images)) {

        if($check_status == "Pending")
        {
            if($status == "Publish")
            {
                DB::table('post')->where('postid', $post_id)->update(['categoryid' => $category,'postlink' => $postlink,'posttitle' => $title,'description' => $description,'publishedon' => $date ,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'published_date' => $date,'schedule' => $schedule, 'schedule_date'=> $scheduleinput,'noti_input' => $noti_input]);
                if($noti_input == '1')
                {
                    return redirect('send_noti'); 
                }
                else
                {
                    return redirect('posts')->with('success', 'Post Updated Successfully'); 
                }
            }
            else
            {
                DB::table('post')->where('postid', $post_id)->update(['categoryid' => $category,'postlink' => $postlink,'posttitle' => $title,'description' => $description,'publishedon' => $date , 'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'updated_at' => $date, 'schedule' => $schedule, 'schedule_date' => $scheduleinput,'noti_input' => $noti_input]);
            }
        }
        else 
        {
            DB::table('post')->where('postid', $post_id)->update(['categoryid' => $category,'postlink' => $postlink,'posttitle' => $title,'description' => $description,'publishedon' => $date , 'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'updated_at' => $date, 'schedule' => $schedule, 'schedule_date' => $scheduleinput,'noti_input' => $noti_input]);
        }
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
          if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/post-img/" .$_FILES["images"]["name"]))
          {
            $images= "image/post-img/" . $_FILES["images"]["name"];
          }
        if($check_status == "Pending")
        {
            if($status == "Publish")
            {
                DB::table('post')->where('postid', $post_id)->update(['categoryid' => $category,'postlink' => $postlink,'posttitle' => $title,'description' => $description,'publishedon' => $date ,'imagepath' => $images,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'published_date' => $date, 'schedule' => $schedule, 'schedule_date' => $scheduleinput,'noti_input' => $noti_input]);
                if($noti_input == '1')
                {
                    return redirect('send_noti'); 
                }
                else
                {
                    return redirect('posts')->with('success', 'Post Updated Successfully');
                }
            }
            else
            {
                DB::table('post')->where('postid', $post_id)->update(['categoryid' => $category,'postlink' => $postlink,'posttitle' => $title,'description' => $description,'publishedon' => $date ,'imagepath' => $images,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'updated_at' => $date, 'schedule' => $schedule, 'schedule_date' => $scheduleinput,'noti_input' => $noti_input]);
            }
        }
        else 
        {
            DB::table('post')->where('postid', $post_id)->update(['categoryid' => $category,'postlink' => $postlink,'posttitle' => $title,'description' => $description,'publishedon' => $date ,'imagepath' => $images,'rssid' => $rss,'status' => $status,'trending_now' => $trending,'hot_content' => $hotcontent,'updated_at' => $date, 'schedule' => $schedule, 'schedule_date' => $scheduleinput,'noti_input' => $noti_input]);
        }

        }     
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

return redirect('rss-feed')->with('success', 'RSS Updated Successfully'); 
}
public function delete_rss($id)
    {
        

        DB::table('rss')->where('rssid',$id)->delete();
        DB::table('post')->where('rssid', $id)->delete();
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
          if(move_uploaded_file($_FILES["image"]["tmp_name"], "image/visual-story/" .$_FILES["image"]["name"]))
          {
            $image= "image/visual-story/" . $_FILES["image"]["name"];
          }        


     $date = Carbon::now()->toDateTimeString();
    $category = $request->input('category');
    $stype = $request->input('stype');
    $sdescription = $request->input('sdescription');
    // $status = $request->input('status');
    

    
    DB::table('v_stories')->insert(['categoryid' => $category,'storytitle' => $stype,'description' => $sdescription,'imagelink' => $image,'status' => 'Active','created_at' => $date,'updated_at' => $date]);
    
    //$addressid =DB::getPdo()->lastInsertId();

return redirect('visualstories')->with('success', 'Visual Stories Added Successfully'); 
}
public function add_newstories(Request $request)
{
    // echo print_r($request->all());
    // die;
function generate_uuid() {
            return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0C2f ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
            );
          
          }
    
//           $idd = generate_uuid();
//           $image="";
//           if(move_uploaded_file($_FILES["image"]["tmp_name"], "image/stories/" .$_FILES["image"]["name"]))
//           {
//             $image= "image/stories/" . $_FILES["image"]["name"];
//           }        


     $date = Carbon::now()->toDateTimeString();
    // $stitle = $request->input('stitle');
    // $ttitle = $request->input('ttitle');
    // $storydesc = $request->input('storydesc');
    //$status = $request->input('status');
    $status = "Active";
    
    $stitle = $request->input('stitle');
    $ttitle = $request->input('ttitle');
    $image = $request->file('image');
    $storydesc = $request->input('storydesc');
    
    
    
    foreach($ttitle as $key => $n) 
        {
            $ttitles = $n;	
            $storydescs = $storydesc[$key];		
            $images = $image[$key];
            $name= date('mdYHis') . uniqid() . $images->getClientOriginalName();
            $images->move('image',$name);
            // $idd = generate_uuid();
           
            // if(move_uploaded_file($images["tmp_name"], "image/stories/" .$images))
            // {
            //     $images= "image/stories/" . $images;
            // }     
            
            DB::table('v_storiestrans')->insert(['storyid' => $stitle,'t_title' => $ttitles,'t_description' => $storydescs,'t_imagelink' => 'image/'.$name,'t_status' => $status]);
            
        }
    
    
    
    //$addressid =DB::getPdo()->lastInsertId();

return redirect('add_story&id='.$stitle)->with('success', 'Stories Added Successfully'); 
}

public function edit_vstory(Request $request)
{


    $date = Carbon::now()->toDateTimeString();
    $category = $request->input('category');
    $story_title = $request->input('story_title');
    $storydescription = $request->input('storydescription');
    $story_id = $request->input('story_id');
    $status = "Active";

    $images=$request->file('images');

    if (empty($images)) {
        DB::table('v_stories')->where('storyid', $story_id)->update(['categoryid' => $category, 'storytitle' => $story_title,'description' => $storydescription, 'status' => $status,'updated_at' => $date]);

        //UPDATE `v_stories` SET `storyid`=[value-1],`categoryid`=[value-2],`storytitle`=[value-3],`description`=[value-4],`imagelink`=[value-5],`status`=[value-6],`created_at`=[value-7],`updated_at`=[value-8] WHERE 1
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
          if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/visual-story/" .$_FILES["images"]["name"]))
          {
            $images= "image/visual-story/" . $_FILES["images"]["name"];
          }
  
    DB::table('v_stories')->where('storyid', $story_id)->update(['categoryid' => $category, 'storytitle' => $story_title,'imagelink' => $images,'description' => $storydescription, 'status' => $status,'updated_at' => $date]);

}
   
return redirect('visualstories')->with('success', 'Visual Stories Updated Successfully'); 
}

public function delete_vstory($id)
    {
        $del = DB::table('v_stories')->where('storyid', $id)->get();

        DB::table('v_stories')->where('storyid',$id)->delete();

        DB::table('v_storiestrans')->where('storyid',$id)->delete();

        return redirect('visualstories')->with('success2', 'Visual Stories Deleted Successfully'); 
    }

public function edit_storynew(Request $request)
{


    $date = Carbon::now()->toDateTimeString();
    $stitle = $request->input('stitle');
    $ttitle = $request->input('ttitle');
    $tdescription = $request->input('tdescription');
    $transid = $request->input('transid');
    $status = "Active";

    $images=$request->file('images');

    if (empty($images)) {
        DB::table('v_storiestrans')->where('transid', $transid)->update(['storyid' => $stitle, 't_title' => $ttitle,'t_description' => $tdescription, 't_status' => $status]);

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
          if(move_uploaded_file($_FILES["images"]["tmp_name"], "image/stories/" .$_FILES["images"]["name"]))
          {
            $images= "image/stories/" . $_FILES["images"]["name"];
          }

  
    DB::table('v_storiestrans')->where('transid', $transid)->update(['storyid' => $stitle, 't_title' => $ttitle,'t_description' => $tdescription,'t_imagelink' => $images, 't_status' => $status]);

}
return redirect('view_stories')->with('success', 'Stories Updated Successfully'); 
}


public function delete_stories($transid,$storyid)
    {
        $del = DB::table('v_storiestrans')->where('transid', $transid)->get();

        DB::table('v_storiestrans')->where('transid',$transid)->delete();

        return redirect('add_story&id='.$storyid)->with('success2', 'Stories Deleted Successfully'); 
    }


// public function publish($id){
//   DB::table('tbl_storelocator')->where('store_id',$id)->update(['status' => 'Active']);
//   return redirect('store_location')->with('success', 'Activated in Store Location');
// }

// public function un_publish($id){
//   DB::table('tbl_storelocator')->where('store_id',$id)->update(['status' => 'Inactive']);
//   return redirect('store_location')->with('success2', 'Inactivated Store Location'); 
// }
public function remove_trending($id)
    {
        DB::table('post')->where('postid', $id)->update(['trending_now' => ' ']);
        return redirect('trending')->with('success', 'News Updated only as Post'); 
    }

}

    