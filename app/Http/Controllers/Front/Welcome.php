<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class Welcome extends Controller
{
    //
    public function welcomePage() {
        $visits = Redis::incr('visits');
        return view('welcome')->withVisits($visits);
    }
}
