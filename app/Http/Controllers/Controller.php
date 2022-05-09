<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function feed($id){
        $details =  DB::table('post')->where('postlink',$id)->where('status','Publish')->take(1)->get();

        // get previous 
        $previous = DB::table('post')->where('categoryid',$details[0]->categoryid)->where('status','Publish')->where('postid', '<', $details[0]->postid)->max('postid');

        // get next 
        $next = DB::table('post')->where('categoryid',$details[0]->categoryid)->where('status','Publish')->where('postid', '>', $details[0]->postid)->min('postid');

        $next_feed = DB::table('post')->where('postid',$next)->take(1)->get();
        $previous_feed = DB::table('post')->where('postid',$previous)->take(1)->get();
          
          return view('front_end.post-single',['details' => $details,'next_feed' => $next_feed , 'previous_feed' => $previous_feed]);
        }
        public function paparazzi_feed($id){
          $details =  DB::table('paparazzi_post')->where('postlink',$id)->where('status','Publish')->take(1)->get();
            
            return view('front_end.paparazzi-post',['details' => $details]);
          }
        
        public function feed_meta(Request $request){
        $details =  DB::table('post')->where('postlink',$request->rsstitle)->where('status','Publish')->take(1)->get();
          
          return view('front_end.post-single',['details' => $details]);
        }
        
        public function adminfeed($id){
          $details =  DB::table('post')->where('postlink',$id)->take(1)->get();
            
            return view('front_end.admin-post-single',['details' => $details]);
          }
     public function category($id){
            $categoryname =  DB::table('category')->where('categoryid',$id)->get();
            
            $details =  DB::table('post')->where('categoryid',$id)->where('status','Publish')->Orderby('published_date', 'desc')->get();
            return view('front_end.post-content',['details' => $details],['categoryname'=>$categoryname]);
            }
              public function vs($id){
            $details =  DB::table('v_storiestrans')->where('storyid',$id)->get();
             $detailsmain =  DB::table('v_stories')->where('storyid',$id)->get();
                       return view('front_end.stories',['details' => $details, 'detailsmain'=>$detailsmain]);
            }
            public function postcategoryadmin(Request $request){
              $cat_name = $request->input('categoryname');
              $rss_name = $request->input('rssname');
              $status = $request->input('status');
              
              $info = DB::table('post');
              if ($cat_name != '')
              $info = $info->where('categoryid',$cat_name);
              if ($rss_name != '')
              $info = $info->where('rssid',$rss_name);
              if ($status != '')
              $info = $info->where('status',$status);
                                                                                      
              $info = $info->Orderby('postid', 'desc')->take(2000)->get();

                 return view('back_end.posts',['info' => $info]);
                 //return response()->json(["success" => true, "data" => $info]);
              }
              public function add_story($id){
                // $info =  DB::table('post')->where('categoryid',$request->input('filtercategoryname'))->get();
                $info = DB::table('v_stories')->where('storyid',$id)->get();
                   return view('back_end.add_story',['info' => $info]);
                  
                }
                 public function getjson(){
                  //$info =  DB::table('post')->select('posttitle','imagepath','postlink')->where('status','Publish')->where('created_at', '>=', Carbon::now()->subDays(2))->inRandomOrder()->get();
                  $info =  DB::table('post')->select('posttitle','imagepath','postlink')->where('status','Publish')->orderBy('created_at', 'DESC')->take(20)->inRandomOrder()->get();
                // $info = DB::table('v_stories')->where('storyid',$id)->get();
                   return view('front_end.get-json',['info' => $info]);
                  
                }
                public function add_subscribe(Request $request){

                  $email =  $request->input('email');
                  $email_exits = DB::table('subscription')->where('subscriber_email' ,$email);
                  $email_count = $email_exits->count();

                  if($email_count >= 1)
                  {
                    ?>
                    <script type="text/javascript">
                      alert("Email Already Exits");
                      window.location.href="index";
                    </script>
                    <?php
                  } 
                  else{
                    DB::table('subscription')->insert(['subscriber_email' => $email]);
                    ?>
                    <script type="text/javascript">
                      alert("Thanks for Subscribing Masala Mobile");
                      window.location.href="index";
                    </script>
                    <?php
                  } 
                }             
                public function search(Request $request){
                  $search_value1 = $request->input('search');
                  $search_value = str_replace(" ","%",$search_value1);
                  //echo $search_value;
                  //die;
                  if($search_value == "")
                  {
                  $info =  DB::table('post')->where('status','Publish')->inRandomOrder()->Orderby('published_date', 'desc')->take(15)->get();
                  }
                  else
                  {
                    $info =  DB::table('post')->where('posttitle', 'like', '%' . $search_value . '%')
                                              ->where('postlink', 'like', '%' . $search_value . '%')->where('status','Publish')->Orderby('published_date', 'desc')->get();
                    if($info->count() == '0')
                    {
                      $info =  DB::table('post')->where('postlink', 'like', '%' . $search_value . '%')->where('status','Publish')->Orderby('published_date', 'desc')->get();
                      if($info->count() > '0')
                      {
                        return view('front_end.search',['info' => $info],['search_value1' => $search_value1]);
                      }
                    }
                    if($info->count() == '0')
                    {
                      $get_cat_post = DB::table('category')->where('categoryname', 'like', '%' . $search_value . '%')->take(1)->get();
                      
                      if($get_cat_post->count() == '0')
                      {
                        $info =  DB::table('post')->where('status','Publish')->inRandomOrder()->Orderby('published_date', 'desc')->take(15)->get();
                         return view('front_end.search',['info' => $info],['search_value1' => ' ']);
                      }
                      else{
                        foreach($get_cat_post as $cate_id => $value)
                        {
                          $cate_id = $value->categoryid;
                        }
                        $info = DB::table('post')->where('categoryid',$cate_id)->where('status','Publish')->Orderby('published_date', 'desc')->get();
                      }
                      
                    }
                  
                  }
                  // $info = DB::table('v_stories')->where('storyid',$id)->get();
                     return view('front_end.search',['info' => $info],['search_value1' => $search_value1]);
                    
                  }
                  public function enquiry(Request $request){
                    $name = $request->input('name');
                    $email = $request->input('email');
                    $msg = $request->input('mesg');
                    $mobile = $request->input('mobile');

                    DB::table('enquiry')->insert(['name' => $name,'email' => $email, 'message' => $msg, 'mobile' => $mobile]);
                    ?>
                    <script type="text/javascript">
                      alert("Form Submitted");
                      window.location.href="index";
                    </script>
                    <?php
                  }
                  public function filtersearch(Request $request)
                  {
                    $searchname = $request->input('filtercategoryname');
                    $info = DB::table('post')->where('status','Publish')->get();
                    return view('back_end.posts',['info' => $info]);
                    //return response()->json(array('success' => true, 'value'=>$info));
                  }
                  public function  post_pending()
                  {
                    $request = $_POST['list'];
                    
                    foreach ($request as $postid=>$value) 
                    {
                      DB::table('post')->where('postid', $value)->update(["status" => 'Publish', "published_date" => Carbon::now()]);
                    }
                    return response()->json(array('success' => true, 'value'=> 'Published'));
                  }
                  public function getnotitoken($id){
                    // $info =  DB::table('post')->where('categoryid',$request->input('filtercategoryname'))->get();
                    $info = DB::table('noti_token')->where('token_id',$id)->get();
                    if($info->count() == 1)
                    {
                      $value = "Already Exits";
                    }
                    else
                    {
                      $value = "Inserted";
                      DB::table('noti_token')->insert(['token_id' => $id]);
                    }
                    return response()->json(array('success' => true, 'value'=> $value));
                      
                    }
                    public function getshareinfo($id){
                      $info =  DB::table('post')->where('postid',$id)->get();
                      
                      foreach($info as $postdet)
                      {
                        $psttitle = $postdet->postlink;
                      }
                      // if($info->count() == 1)
                      // {
                      //   $value = "Already Exits";
                      // }
                      // else
                      // {
                      //   $value = "Inserted";
                      //   DB::table('noti_token')->insert(['token_id' => $id]);
                      // }
                      return response()->json(array('success' => true, 'posttitle'=> $psttitle));
                        
                      }
                      public function get_posts_admin(){
                       $info = DB::table('post')
                                ->Orderby('postid', 'desc')->take(2000)                                             
                                ->get();
                      
                       return view('back_end.posts',['info' => $info]);
                        
                      }
                      public function add_subcription(Request $request){
                        $get_number = $request->mobile; 
                        $number= "+".$get_number;
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                          CURLOPT_URL => 'http://13.234.96.218:3000/sendMessage',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS =>'{
                            "phone_number": "'.$number.'"
                        }',
                          CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                          ),
                        ));
                        
                        $response = curl_exec($curl);
                        
                        curl_close($curl);
                        //echo $response;
                        return redirect('/')->with('success', 'Subscription Added Successfully');
                      }
                      
                      
                      public function send_noti_contro(){
                        $get_pubished_article = DB::table('post')->where('status','Publish')->orderBy('published_date','DESC')->limit(1)->get();
                        foreach($get_pubished_article as $post)
                        {
                            $get_tokens = DB::table('noti_token')->get();
                            foreach($get_tokens as $token)
                            {
                                $url ="https://fcm.googleapis.com/fcm/send";
                                $fields=array(
                                    "to"=>$token->token_id,
                                    "notification"=>array(
                                        "body"=> strip_tags($post->description),
                                        "title"=>$post->posttitle,
                                        "icon"=>"https://mobilemasala.com/assets/noti_icon.png",
                                        "image"=>$post->imagepath,
                                        "click_action"=>"https://mobilemasala.com/post-single&id=".$post->postlink
                                    )
                                );

                                $headers=array(
                                    'Authorization: key=AAAAwiHg65s:APA91bHAeQZBGeMvPqAMVP85Y8lW3wW-Wg0kTXq_rC4UFvCxMQeRHTZeJfjjPYNrmaFr7x0mcdYRZfIayAdHwL9uhtPwrZKFz-39LZ9HuAA0r7bJqn3Z5LLOzj2FNd5BtadzkhTYDu6H',
                                    'Content-Type:application/json'
                                );

                                $ch=curl_init();
                                curl_setopt($ch,CURLOPT_URL,$url);
                                curl_setopt($ch,CURLOPT_POST,true);
                                curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
                                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                                curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
                                $result=curl_exec($ch);
                                //print_r($result);
                                curl_close($ch);
                            }
                        }

                        return redirect('posts');
                      }
                      public function meta_share($id,$value){
                        $details =  DB::table('post')->where('postlink',$id)->where('status','Publish')->take(1)->get();
                          return view('front_end.meta_share',['details' => $details,'value' => $value]);
                        }
                    

                 
                  
}
