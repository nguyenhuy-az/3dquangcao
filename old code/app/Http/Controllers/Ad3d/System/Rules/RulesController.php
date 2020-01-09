<?php

namespace App\Http\Controllers\Ad3d\System\Rules;

use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class RulesController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelRules = new QcRules();
        $dataAccess = [
            'accessObject' => 'rules'
        ];
        $dataRules = QcRules::orderBy('rules_id', 'ASC')->select('*')->paginate(30);
        return view('ad3d.system.rules.list', compact('modelStaff', 'dataRules', 'dataAccess'));

    }

    public function view($rulesId)
    {
        $modelStaff = new QcStaff();
        $modelRules = new QcRules();
        if (!empty($rulesId)) {
            $dataRules = $modelRules->getInfo($rulesId);
            return view('ad3d.system.rules.view', compact('modelStaff','dataRules'));
        }
    }

    //add
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'rules'
        ];
        return view('ad3d.system.rules.add', compact('modelStaff','dataAccess'));
    }

    public function postAdd()
    {
        $modelRules = new QcRules();
        $txtTitle = Request::input('txtTitle');
        $txtContent = Request::input('txtRuleContent');
        // check exist of name
        if ($modelRules->existTitle($txtTitle)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$txtTitle'</b> đã tồn tại.");
        } else {
            if ($modelRules->insert($txtTitle, $txtContent)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }

    }

    //edit
    public function getEdit($rulesId)
    {
        $modelStaff = new QcStaff();
        $modelRules = new QcRules();
        $dataRules = $modelRules->getInfo($rulesId);
        if (count($dataRules) > 0) {
            return view('ad3d.system.rules.edit', compact('modelStaff','dataRules'));
        }
    }

    public function postEdit($rulesId)
    {
        $modelRules = new QcRules();
        $txtTitle = Request::input('txtTitle');
        $txtContent = Request::input('txtRuleContent');
        if ($modelRules->existEditTitle($rulesId, $txtTitle)) {
            return "Tên <b>'$txtTitle'</b> đã tồn tại.";
        } else {
            $modelRules->updateInfo($rulesId, $txtTitle, $txtContent);
        }
    }

    // delete
    public function deleteDelete($rulesId)
    {
        if (!empty($rulesId)) {
            $modelRules = new QcRules();
            $modelRules->actionDelete($rulesId);
        }
    }
}
