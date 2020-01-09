<?php

namespace App\Http\Controllers\User\Image;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function index($userId = null)
    {
        $userAccess = 'image';
        return view('user.image.index', compact('userAccess'));
    }
}
