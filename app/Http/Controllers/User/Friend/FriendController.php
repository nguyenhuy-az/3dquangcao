<?php

namespace App\Http\Controllers\User\Friend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendController extends Controller
{
    public function index($userId = null)
    {
        $userAccess = 'friend';
        return view('user.friend.index', compact('userAccess'));
    }
}
