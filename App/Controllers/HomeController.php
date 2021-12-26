<?php

namespace App\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;

class HomeController
{
    public function index(){
        include "App/Views/home.php";
    }

    public function get(){
        header('Content-Type: application/json; charset=utf-8');
        if (!isset($_POST['user'])) {
            echo json_encode([
                'status'=>'error',
                'message'=>'Missing parameters.'
            ]);
            return;
        }

        $user = $_POST['user'];
        $timeDuration = (isset($_ENV['TWITTER_CONSUMER_KEY'])) ? $_ENV['TWITTER_CONSUMER_KEY'] : 60*15;
        $limitRate = (isset($_ENV['LIMIT_RATES'])) ? $_ENV['LIMIT_RATES'] : 400;

        if (!cache()->has("time_first_use") || !cache()->has("requests")){
            cache()->set("time_first_use", Carbon::now()->toDateTimeString(), Carbon::now()->addSeconds($timeDuration)->timestamp);
            cache()->set("requests", $limitRate, Carbon::now()->addSeconds($timeDuration)->timestamp);
        }

        $timeResetUse = Carbon::createFromDate(cache()->get("time_first_use"))->addSeconds($timeDuration);

        if (Carbon::now()->diffInSeconds($timeResetUse,false) < 0){
            cache()->set("time_first_use", Carbon::now()->toDateTimeString(), Carbon::now()->addSeconds($timeDuration)->timestamp);
            cache()->set("requests", $limitRate, Carbon::now()->addSeconds($timeDuration)->timestamp);
        }

        if (cache()->get("requests")>0){
            cache()->set("requests",cache()->get("requests")-1);
            $TwitterOAuth = new TwitterOAuth($_ENV['TWITTER_CONSUMER_KEY'], $_ENV['TWITTER_CONSUMER_SECRET']);
            $data = $TwitterOAuth->get("users/show",array('screen_name' => $user));
            if (!isset($data->id)){
                echo json_encode([
                    'status'=>'error',
                    'message'=>"User not found."
                ]);
                return;
            }
            echo json_encode([
                "status"=>'ok',
                "response"=>$data->id,
                "limite_rate"=>cache()->get("requests")
            ]);
        }else{
            if (Carbon::now()->diffInSeconds($timeResetUse,false) <=60){
                $message = "You have reached the request limit within 15 minutes, try again in ".Carbon::now()->diffInSeconds($timeResetUse,false)." seconds.";
            }else{
                $message = "You have reached the request limit within 15 minutes, try again in ".Carbon::now()->diffInMinutes($timeResetUse,false)." minutes.";
            }
            echo json_encode([
                'status'=>'error',
                'message'=>$message,
                "limite_rate"=>cache()->get("requests")
            ]);
        }
    }

}