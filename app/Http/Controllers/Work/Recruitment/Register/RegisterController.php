<?php

namespace App\Http\Controllers\Work\Recruitment\Register;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        $dataAccess = [
            'object' => null
        ];
        return view('work.recruitment.register.index', compact('dataAccess'));
    }

    # lay form dang ky
    public function getAdd()
    {
        $dataAccess = [
            'object' => null
        ];
        return view('work.recruitment.register.add', compact('dataAccess'));
    }

    public function postAdd()
    {
        $dataAccess = [
            'object' => null
        ];
        return view('work.recruitment.info.index', compact('dataAccess'));
    }
}
