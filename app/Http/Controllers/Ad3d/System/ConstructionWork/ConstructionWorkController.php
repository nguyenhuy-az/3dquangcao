<?php

namespace App\Http\Controllers\Ad3d\System\ConstructionWork;

use App\Models\Ad3d\ConstructionWork\QcConstructionWork;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;

//use Request;

class ConstructionWorkController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelConstructionWork = new QcConstructionWork();
        $dataAccess = [
            'accessObject' => 'activity',
            'accessSubObject' => 'constructionWork'
        ];
        $dataConstructionWork = $modelConstructionWork->selectActivityInfo()->paginate(30);
        return view('ad3d.system.construction-work.list', compact('modelStaff', 'dataConstructionWork', 'dataAccess'));

    }

    public function view($constructId)
    {
        $modelConstructionWork = new QcConstructionWork();
        if (!empty($constructId)) {
            $dataConstructionWork = $modelConstructionWork->getInfo($constructId);
            return view('ad3d.system.construction-work.view', compact('dataConstructionWork'));
        }
    }

    //them Kpi
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'activity',
            'accessSubObject' => 'constructionWork'
        ];
        return view('ad3d.system.construction-work.add', compact('modelStaff', 'dataAccess'));
    }

    public function postAdd(Request $request)
    {
        $modelConstructionWork = new QcConstructionWork();
        $txtName = $request->input('txtName');
        $txtDescription = $request->input('txtDescription');
        if ($modelConstructionWork->existName($txtName)) {
            Session::put('notifyAdd', "Danh mục này đã tồn tại.");
        } else {
            if ($modelConstructionWork->insert($txtName, $txtDescription)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Hệ tính năng đang cập nhật');
            }
        }


    }

    //edit
    public function getEdit($constructId)
    {
        $hFunction = new \Hfunction();
        $modelConstructionWork = new QcConstructionWork();
        $dataConstructionWork = $modelConstructionWork->getInfo($constructId);
        if ($hFunction->checkCount($dataConstructionWork)) {
            return view('ad3d.system.construction-work.edit', compact('dataConstructionWork'));
        }
    }

    public function postEdit(Request $request, $constructId)
    {
        $modelConstructionWork = new QcConstructionWork();
        $name = $request->input('txtName');
        $txtDescription = $request->input('txtDescription');
        if ($modelConstructionWork->existEditName($constructId, $name)) {
            return "Tên <b>'$name'</b> đã tồn tại.";
        } else {
            $modelConstructionWork->updateInfo($constructId, $name, $txtDescription);
        }

    }

    // delete
    public function delete($constructId)
    {

        if (!empty($constructId)) {
            $modelConstructionWork = new QcConstructionWork();
            $modelConstructionWork->delete($constructId);
        }

    }
}
