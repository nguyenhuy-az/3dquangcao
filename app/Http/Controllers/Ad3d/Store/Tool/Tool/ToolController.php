<?php

namespace App\Http\Controllers\Ad3d\Store\Tool\Tool;

use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use Input;
use File;
use DB;
use Request;

class ToolController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'tool'
        ];
        $dataTool = QcTool::orderBy('name', 'ASC')->select('*')->paginate(30);
        return view('ad3d.store.tool.tool.list', compact('modelStaff', 'dataTool', 'dataAccess'));
    }

    public function view($toolId)
    {
        $modelTool = new QcTool();
        if (!empty($toolId)) {
            $dataTool = $modelTool->getInfo($toolId);
            return view('ad3d.store.tool.tool.view', compact('dataTool'));
        }
    }

    //add
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'tool'
        ];

        return view('ad3d.store.tool.tool.add', compact('dataAccess','modelStaff'));
    }

    public function postAdd()
    {
        $modelTool = new QcTool();
        $name = Request::input('txtName');
        $unit = Request::input('txtUnit');
        // kiểm tra tồn tại vật tư
        if ($modelTool->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelTool->insert($name, $unit, null)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }
    }

    //edit
    public function getEdit($toolId)
    {
        $modelTool = new QcTool();
        $dataTool = $modelTool->getInfo($toolId);
        if (count($dataTool) > 0) {
            return view('ad3d.store.tool.tool.edit', compact('dataTool'));
        }
    }

    public function postEdit($toolId)
    {
        $modelTool = new QcTool();
        $name = Request::input('txtName');
        $unit = Request::input('txtUnit');
        $notifyContent = null;
        if ($modelTool->existEditName($toolId, $name)) {
            $notifyContent = "Tên <b>'$name'</b> đã tồn tại.";
        }
        if (!empty($notifyContent)) {
            return $notifyContent;
        } else {
            $modelTool->updateInfo($toolId, $name, $unit, null);
        }
    }
    public function deleteTool($toolId)
    {
        if (!empty($toolId)) {
            $modelTool = new QcTool();
            $modelTool->deleteTool($toolId);
        }
    }
}
