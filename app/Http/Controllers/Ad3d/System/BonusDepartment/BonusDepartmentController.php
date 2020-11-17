<?php

namespace App\Http\Controllers\Ad3d\System\BonusDepartment;

use App\Models\Ad3d\BonusDepartment\QcBonusDepartment;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BonusDepartmentController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelRank = new QcRank();
        $modelDepartment = new QcDepartment();
        $dataAccess = [
            'accessObject' => 'bonusDepartment'
        ];

        $dataDepartment = $modelDepartment->selectInfoAllActivity();//
        $dataDepartment = $dataDepartment->paginate(30);
        return view('ad3d.system.bonus-department.list', compact('modelStaff', 'modelRank', 'dataDepartment', 'dataAccess'));

    }

    public function getAdd($departmentId, $rankId)
    {
        $modelRank = new QcRank();
        $modelDepartment = new QcDepartment();
        $modelBonusDepartment = new QcBonusDepartment();
        $dataDepartment = $modelDepartment->getInfo($departmentId);
        $dataRank = $modelRank->getInfo($rankId);
        $dataBonusDepartmentActivity = $modelBonusDepartment->infoActivityOfDepartmentRank($departmentId, $rankId);
        return view('ad3d.system.bonus-department.add', compact('modelStaff', 'dataDepartment', 'dataRank', 'dataBonusDepartmentActivity'));
    }

    public function postAdd(Request $request, $departmentId, $rankId)
    {
        $modelBonusDepartment = new QcBonusDepartment();
        $txtPercent = $request->input('txtPercent');
        # khong trung thong tin co san

        if (!$modelBonusDepartment->existPercentActivityOfDepartmentAndRank($txtPercent, $departmentId, $rankId)) {
            # vo hieu muc thuong cu neu co
            $modelBonusDepartment->disableBonusOfDepartmentAndRank($departmentId, $rankId);
            # neu co thuong
            if ($txtPercent > 0) {
                # them thong tin thuong
                //echo "$departmentId, $rankId, $txtPercent";
                $modelBonusDepartment->insert($departmentId, $rankId, $txtPercent, null);
            }
        }
    }

    // ======== ======== cap nhat trang ap dung thuong phat ======== =====
    # ap dung thuong
    public function updateApplyBonus($bonusId, $applyBonus)
    {
        $modelBonusDepartment = new QcBonusDepartment();
        return $modelBonusDepartment->updateApplyBonus($bonusId, $applyBonus);
    }
    # ap dung phat
    public function updateApplyMinus($bonusId, $applyBonus)
    {
        $modelBonusDepartment = new QcBonusDepartment();
        return $modelBonusDepartment->updateApplyMinus($bonusId, $applyBonus);
    }
}
