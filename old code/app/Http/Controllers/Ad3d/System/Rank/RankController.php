<?php

namespace App\Http\Controllers\Ad3d\System\Rank;

use App\Models\Ad3d\department\Qcdepartment;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class RankController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelRank = new QcRank();
        $dataAccess = [
            'accessObject' => 'rank'
        ];
        $dataRank = QcRank::orderBy('name', 'ASC')->select('*')->paginate(30);
        return view('ad3d.system.rank.list', compact('modelStaff', 'modelRank', 'dataRank', 'dataAccess'));
    }

    public function view($rankId)
    {
        $modelRank = new QcRank();
        if (!empty($rankId)) {
            $dataRank = $modelRank->getInfo($rankId);
            return view('ad3d.system.rank.view', compact('dataRank'));
        }
    }

    //add
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'rank'
        ];
        return view('ad3d.system.rank.add', compact('modelStaff','dataAccess'));
    }

    public function postAdd()
    {
        $modelRank = new QcRank();
        $name = Request::input('txtName');
        $txtDescription = Request::input('txtDescription');

        // check exist of name
        if ($modelRank->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelRank->insert($name, $txtDescription)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }
    }

    //edit
    public function getEdit($rankId)
    {
        $modelStaff = new QcStaff();
        $modelRank = new QcRank();
        $dataRank = $modelRank->getInfo($rankId);
        if (count($dataRank) > 0) {
            return view('ad3d.system.rank.edit', compact('modelStaff','dataRank'));
        }
    }

    public function postEdit($rankId)
    {
        $modelRank = new QcRank();
        $name = Request::input('txtName');
        $txtDescription = Request::input('txtDescription');

        $notifyContent = null;
        if ($modelRank->existEditName($rankId, $name)) {
            $notifyContent = "Tên <b>'$name'</b> đã tồn tại.";
        }
        if (!empty($notifyContent)) {
            return $notifyContent;
        } else {
            $modelRank->updateInfo($rankId, $name, $txtDescription);
        }
    }

    // delete
    public function deleteDepartment($rankId)
    {
        if (!empty($rankId)) {
            $modelRank = new QcRank();
            $modelRank->actionDelete($rankId);
        }
    }
}
