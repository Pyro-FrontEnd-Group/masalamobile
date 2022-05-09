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
       $data = DB::table('rss')->get();
       
        foreach($data as $rssdata)
        {
            
            $rss = simplexml_load_file($rssdata->sourceurl);
            //echo '<h2>'. $rss->channel->title . '</h2>';	
            
            foreach ($rss->channel->item as $item) 
            {
               $countdata = DB::table('post')->where('guid',$item->guid);

               if($countdata->count() == 0 && $item != '')
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
                    
                    /*foreach($countdata as $rowdata)
                    {
                        if($rowdata->imagepath == '')
                        {
                             $imagepath = "";
                   
                               if(isset($item->enclosure['url'][0]))
                                    $imagepath = $item->enclosure['url'][0];
                                elseif(isset($item->enclosure['url']))
                                    $imagepath = $item->enclosure['url'];
                                    
                                    if($imagepath != "")
                                    {
                                     DB::table('post')->update(['imagepath' =>  $imagepath])->where('postid',$rowdata->postid);   
                                    }
                        }
                    }*/
                }
        
           
            }
        }
        
        DB::table('test_cron')->insert(['status' => 'Pending']);
        
        //return redirect('posts');
               
        
    }
}