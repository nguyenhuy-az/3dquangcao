<?php

namespace App\Http\Controllers\User\Activity;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function index($userId=null)
    {
        $userAccess = 'activity';
        return view('user.activity.index', compact('userAccess'));
    }
}
