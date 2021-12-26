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

        #Verify that the variable "user" exists
        if (!isset($_POST['user'])) return toJson(['status'=>'error','message'=>'Missing parameters.']);
        $user = $_POST['user'];

        #Time range for request restart
        $timeDuration = env('TIME_RESET',60*15);

        #Limit of requests within that time already determined.
        $limitRate = env('LIMIT_RATES',400);

        #time_first_use:   Record the first use
              #requests:   Number of requests remaining
        if (!cache()->has("time_first_use")){
            cache()->setMultiple(
                [
                    "time_first_use"=>Carbon::now()->toDateTimeString(),
                    "requests"=>$limitRate,
                ],
                Carbon::now()->addSeconds($timeDuration)->timestamp
            );
        }

        #Reset time counting from the registration of the first use
        $timeResetUse = Carbon::createFromDate(cache()->get("time_first_use"))->addSeconds($timeDuration);

        #If the period has expired, the variables are reset
        if (Carbon::now()->diffInSeconds($timeResetUse,false) <= 0){
            cache()->setMultiple(
                [
                    "time_first_use"=>Carbon::now()->toDateTimeString(),
                    "requests"=>$limitRate,
                ],
                Carbon::now()->addSeconds($timeDuration)->timestamp
            );
        }

        #If the number of requests is over
        if (cache()->get("requests")<=0){
            $timeString = (Carbon::now()->diffInSeconds($timeResetUse,false) <=60) ?
                Carbon::now()->diffInSeconds($timeResetUse,false)." seconds." :
                Carbon::now()->diffInMinutes($timeResetUse,false)." minutes.";
            return toJson([
                'status'=>'error',
                'message'=>"You have reached the request limit within 15 minutes, try again in ".$timeString,
                "limite_rate"=>cache()->get("requests")
            ]);
        }

        #Subtract the request
        cache()->set("requests",cache()->get("requests")-1);

        #Initiate communication with the twitter library
        $TwitterOAuth = new TwitterOAuth(
            env('TWITTER_CONSUMER_KEY'),
            env('TWITTER_CONSUMER_SECRET')
        );

        #Get data of user twitter
        $response = $TwitterOAuth->get("users/show",array('screen_name' => $user));

        #Validate that the request was correct
        if (!isset($response->id)){
            return toJson([
                'status'=>'error',
                'message'=>"User not found.",
                "limite_rate"=>cache()->get("requests")
            ]);
        }

        #Response to request
        return toJson([
            "status"=>'ok',
            "response"=>$response->id,
            "limite_rate"=>cache()->get("requests")
        ]);
    }

}