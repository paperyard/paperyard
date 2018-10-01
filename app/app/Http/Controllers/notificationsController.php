<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;

class notificationsController extends Controller
{
    // list of notification
    public function index(){

        $my_notifications = DB::table('notifications')->where('notif_user_id', Auth::user()->id)->orderBy('notif_id', 'DESC')->get();
        return view('pages/notifications')->with(compact('my_notifications'));
    }

    // return create notification page
    public function newNotification(){
        return view('pages/notifications_create');
    }

    // create new notification
    public function createUpdateNotification(Request $req){

        // set user time zone.
         date_default_timezone_set(Auth::user()->user_timezone);

        //'keywords'  $req->keywords
        //'notification_title' $req->notification_title

        // check if tax relevant is true
        if($req->has('tax_relevant')){
           $tax_relevant = 1;
        }else{
           $tax_relevant = 0;
        }
        // check if notification has tags
        if($req->has('tags')){
           $tags = $req->tags;
        }else{
           $tags = null;
        }
        // check if notifications has send email action
        if($req->has('send_email')){
            $send_email = 1;
            $subject =  $req->subject;
            $receiver_name =  $req->receiver_name;
            $receiver_email =  $req->receiver_email;
            $message =  str_replace('↵','<br>', $req->message);
        }else{
            $send_email = 0;
            $subject =  null;
            $receiver_name =  null;
            $receiver_email =  null;
            $message =  null;
        }

        // save notification
        if($req->has('save_notification')){
                $new_notif = DB::table('notifications')
                ->insert([
                    'notif_user_id'     =>  Auth::user()->id,
                    'notif_title'       =>  $req->notification_title,
                    'notif_keywords'    =>  $req->keywords,
                    'tax_relevant'      =>  $tax_relevant,
                    'tags'              =>  $tags,
                    'send_email'        =>  $send_email,
                    'se_subject'        =>  $subject,
                    'se_receiver_name'  =>  $receiver_name,
                    'se_receiver_email' =>  $receiver_email,
                    'se_message'        =>  $message,
                    "created_at" =>  \Carbon\Carbon::now(), # \Datetime()
                    "updated_at" =>  \Carbon\Carbon::now(),  # \Datetime()
                ]);

                if(count($new_notif)>=1){
                    session()->flash('notif_save_success', 'You created new notification.');
                    return "success";
                }else{
                    return "failed";
                }
          }

        // update notification
        if($req->has('update_notification')){


                $update_notif = DB::table('notifications')
                ->where([
                    ['notif_user_id','=',Auth::user()->id],
                    ['notif_id','=',$req->notification_id]
                ])
                ->update([
                    'notif_title'       =>  $req->notification_title,
                    'notif_keywords'    =>  $req->keywords,
                    'tax_relevant'      =>  $tax_relevant,
                    'tags'              =>  $tags,
                    'send_email'        =>  $send_email,
                    'se_subject'        =>  $subject,
                    'se_receiver_name'  =>  $receiver_name,
                    'se_receiver_email' =>  $receiver_email,
                    'se_message'        =>  $message,
                    "updated_at" =>     \Carbon\Carbon::now(),  # \Datetime()
                ]);

                if(count($update_notif)>=1){
                    session()->flash('notif_update_success', 'Notification successfully updated.');
                    return "success";
                }else{
                    return "failed";
                }
          }

    }

    // edit notification
    public function editNotification($notify_id){
        $notification = DB::table('notifications')->where([
            ['notif_user_id','=',Auth::user()->id],
            ['notif_id','=',$notify_id]
        ])
        ->first();

        if(count($notification)>=1){
             return view('pages/notifications_edit')->with(compact('notification'));
         }else{
            return redirect('error_404');
         }
    }

    public function deleteNotification(Request $req){

           $deleted = DB::table('notifications')->where([
                ['notif_user_id','=',Auth::user()->id],
                ['notif_id','=',$req->doc_id]
           ])->delete();

           if(count($deleted)>=1){
              session()->flash('notif_deleted', 'Notification deleted.');
              return "deleted";
           }else{
              return "Something went wrong";
           }
    }
    
    //notification linechart datas
    public function getNotificationLineChartDatas(){
            
       //get users notification id in notification history
        $notify_ids = DB::table('notifications_history')
        ->where('notification_user_id', Auth::user()->id)
        ->select('notification_id')
        ->groupBy('notification_id')
        ->get();
    
        if(count($notify_ids)>0){
            foreach($notify_ids as $n){
                $datas = [0.1];
                //count total notification , group by day and year
                $notify_datas = DB::table('notifications_history')
                ->where('notification_id', $n->notification_id)
                ->select(DB::raw('count(`notification_id`) as total_notif')) 
                ->groupBy(DB::raw('DAY(created_at)'))
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();

                if(count($notify_datas)>0){
                     foreach($notify_datas as $d){
                        array_push($datas, $d->total_notif);
                     }
                     $n->notif_counts = $datas;
                }else{
                     $n->notif_counts = $datas;
                }      
            }
        }
        return json_encode($notify_ids);
    }


}


