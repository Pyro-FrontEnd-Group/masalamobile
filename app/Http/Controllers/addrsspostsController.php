<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class addrsspostsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index()
    {
       $data = DB::table('rss')->where('status','Active')->get();
       
        foreach($data as $rssdata)
        {
            
            $rss = simplexml_load_file($rssdata->sourceurl);
            //echo '<h2>'. $rss->channel->title . '</h2>';	
            
            foreach ($rss->channel->item as $item) 
            {
               $countdata = DB::table('post')->where('guid',$item->guid)->first();

               //if($countdata->count() == 0 && $item != '')
               if(is_null($countdata))
               {
                   $imagepath = "";
                   
                   if(isset($item->enclosure['url'][0]))
                        $imagepath = $item->enclosure['url'][0];
                    elseif(isset($item->enclosure['url']))
                        $imagepath = $item->enclosure['url'];
                   
                    $date = Carbon::now()->toDateTimeString();
                    $postlink = $item->title;
                    $postlink = str_replace(' ','-',$postlink);
                    $postlink = preg_replace('/[^A-Za-z0-9\-]/', '', $postlink);
                    DB::table('post')->insert(['categoryid' => $rssdata->categoryid,
                    'guid' => $item->guid,
                    'postlink' => $postlink,
                    'postintro' => $item->intro,
                    'Author' => $item->author,
                    'keywords' => '0',
                    'hitcount' => '0', 
                    'posttitle' => $item->title,
                    'description' => $item->description,
                    'publishedon' => $date,
                    'imagepath' =>  $imagepath,
                    'rssid' => $rssdata->rssid,
                    'status' => 'Pending',
                    ]);
                }
                else
                {
                    //$countdata2 = DB::table('post')->where('imagepath','=','')->get();
                        if($countdata->imagepath == '' || $countdata->imagepath == '1')
                        {

                             $imagepath = "";
                   
                               if(isset($item->enclosure['url'][0]))
                                    $imagepath = $item->enclosure['url'][0];
                                elseif(isset($item->enclosure['url']))
                                    $imagepath = $item->enclosure['url'];

                                //echo "Image : ".$item->enclosure."<br/>";
                                //echo "guid : ".$item->guid."<br/>";
                                    
                                    if($imagepath != "")
                                    {
                                     DB::table('post')->where('postid',$countdata->postid)->update(['imagepath' =>  $imagepath]);   
                                    }
                        }
                    }
                }
        }
        
        DB::table('test_cron')->insert(['status' => 'Pending']);
        
        //return redirect('posts');
               
        
    }
    public function schedule()
    {
        $a=[];
        $mytime = Carbon::now()->format('Y-m-d H:i');
        //$mytime = $mytime->toDateTimeString();
        $data = DB::table('post')->where('schedule','Yes')->get();
        foreach($data as $scheduledata)
        {   array_push($a,$scheduledata->postid);
            $scheduledate = $scheduledata->schedule_date;
            $scheduledate = Carbon::createFromFormat('Y-m-d H:i:s', $scheduledate)->format('Y-m-d H:i');
            
            if($mytime == $scheduledate)
            {
                DB::table('post')->where('postid',$scheduledata->postid)->update(['status' => "Publish",'schedule' => "Scheduled",'published_date' => Carbon::now()->format('Y-m-d H:i:s')]);
            }
            
        }
        if($a != '')
        {
            return view('front_end.send_schedule_noti',['a' => $a]);
        }
        else
        {
            return view('front_end.send_schedule_noti',['a' => '']);
        }
        // return view('back_end.posts',['info' => $info]);

    }
    public function send_noti()
    {
        return redirect('send_noti');
    }
}