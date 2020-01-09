<?php

namespace App\Http\Controllers\Ad3d\Store\Tool\Type;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeController extends Controller
{
    public function index()
    {
        $dataAccess = [
            'accessObject'=>'type'
        ];
        return view('ad3d.tool.type.list', compact('dataAccess'));

    }

    public function view()
    {
        return view('ad3d.tool.type.view');
    }

    //add
    public function getAdd()
    {
        $dataAccess = [
            'accessObject'=>'type'
        ];
        return view('ad3d.tool.type.add', compact('dataAccess'));
    }

    public function postAdd()
    {

    }

    //edit
    public function getEdit()
    {
        return view('ad3d.tool.type.edit');
    }

    public function postEdit()
    {

    }
}
