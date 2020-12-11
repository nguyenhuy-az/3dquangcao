<?php

namespace App\Models\Ad3d\PayActivityDetail;

use Illuminate\Database\Eloquent\Model;

class QcPayActivityDetail extends Model
{
    protected $table = 'qc_pay_activity_detail';
    protected $fillable = ['pay_id', 'payCode', 'money', 'payDate', 'payImage', 'note', 'confirmStatus', 'confirmDate', 'confirmNote', 'invalidStatus', 'created_at', 'payList_id', 'staff_id', 'confirmStaff_id', 'company_id'];
    protected $primaryKey = 'pay_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    #mac dinh co xac nhan
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    #mac dinh khong xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    #mac dinh nhap dung
    public function getDefaultHasInvalid()
    {
        return 1;
    }

    #mac dinh nhap sai
    public function getDefaultNotInvalid()
    {
        return 0;
    }
    //---------- Insert ----------

    // insert
    public function insert($money, $payDate, $payImage = null, $note, $payListId, $staffId, $companyId)
    {
        $hFunction = new \Hfunction();
        $modelPayActivity = new QcPayActivityDetail();
        //create code
        $nameCode = $hFunction->getTimeCode();
        // insert
        $modelPayActivity->payCode = $nameCode;
        $modelPayActivity->money = $money;
        $modelPayActivity->payDate = $payDate;
        $modelPayActivity->payImage = $payImage;
        $modelPayActivity->note = $note;
        $modelPayActivity->payList_id = $payListId;
        $modelPayActivity->company_id = $companyId;
        $modelPayActivity->staff_id = $staffId;
        $modelPayActivity->created_at = $hFunction->createdAt();
        if ($modelPayActivity->save()) {
            $this->lastId = $modelPayActivity->pay_id;
            return true;
        } else {
            return false;
        }
    }

    //lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    # kiem tra id
    public function checkNullId($id = null)
    {
        return (empty($id)) ? $this->paymentId() : $id;
    }

    // cap nhat thong tin
    public function updateInfo($payId, $money, $payDate, $note, $payListId, $companyId)
    {
        return QcPayActivityDetail::where('pay_id', $payId)->update([
            'money' => $money,
            'payDate' => $payDate,
            'note' => $note,
            'company_id' => $companyId,
            'payList_id' => $payListId
        ]);
    }

    // duyet chi
    public function confirmPay($payId, $invalidStatus, $confirmNote, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        return QcPayActivityDetail::where('pay_id', $payId)->update([
            'invalidStatus' => $invalidStatus,
            'confirmStatus' => $this->getDefaultHasConfirm(),
            'confirmNote' => $confirmNote,
            'confirmDate' => $hFunction->carbonNow(),
            'confirmStaff_id' => $confirmStaffId,
        ]);
    }

    public function deletePay($payId = null)
    {
        $payId = $this->checkNullId($payId);
        $image = $this->payImage($payId);
        if (QcPayActivityDetail::where('pay_id', $payId)->delete()) {
            $this->dropImage($image);
        }
    }

    public function rootPathFullImage()
    {
        return 'public/images/pay/activity/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/pay/activity/small';
    }

    //upload image
    public function uploadImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //drop image
    public function dropImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    public function pathSmallImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathSmallImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }

    //========== ========= ========= CAC MOI QUAN HE DU LIEU ========== ========= ==========
    //---------- Danh muc thanh toan -----------
    public function payActivityList()
    {
        return $this->belongsTo('App\Models\Ad3d\PayActivityList\QcPayActivityList', 'payList_id', 'payList_id');
    }


    //---------- nha vien chi -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }


    // tong tien da chua bao gom da duyet va chua duyet
    public function totalMoneyOfStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcPayActivityDetail::where('staff_id', $staffId)->where('payDate', 'like', "%$date%")->sum('money');
        } else {
            return QcPayActivityDetail::where('staff_id', $staffId)->sum('money');
        }

    }

    // tong tien da duoc duyet va hop le
    public function totalMoneyConfirmedAndInvalidOfStaffAndDate($staffId, $date = null)
    {
        # co xac nhan
        $hasConfirm = $this->getDefaultHasConfirm();
        # nhap dung
        $hasInvalid = $this->getDefaultHasInvalid();
        if (!empty($date)) {
            return QcPayActivityDetail::where('staff_id', $staffId)->where('confirmStatus', $hasConfirm)->where('invalidStatus', $hasInvalid)->where('payDate', 'like', "%$date%")->sum('money');
        } else {
            return QcPayActivityDetail::where('staff_id', $staffId)->where('confirmStatus', $hasConfirm)->where('invalidStatus', $hasInvalid)->sum('money');
        }

    }

    public function totalMoneyOfListStaffAndDate($listStaffId, $date)
    {
        if (!empty($date)) {
            return QcPayActivityDetail::whereIn('staff_id', $listStaffId)->where('payDate', 'like', "%$date%")->sum('money');
        } else {
            return QcPayActivityDetail::whereIn('staff_id', $listStaffId)->sum('money');
        }

    }

    public function totalMoneyConfirmedOfListStaffAndDate($listStaffId, $date)
    {
        # co xac nhan
        $hasConfirm = $this->getDefaultHasConfirm();
        if (!empty($date)) {
            return QcPayActivityDetail::whereIn('staff_id', $listStaffId)->where('confirmStatus', $hasConfirm)->where('payDate', 'like', "%$date%")->sum('money');
        } else {
            return QcPayActivityDetail::whereIn('staff_id', $listStaffId)->where('confirmStatus', $hasConfirm)->sum('money');
        }

    }

    //---------- nha vien duyet -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    #kiem nhan vien nhan vien chi
    public function checkStaffPay($staffId, $payId = null)
    {
        return QcPayActivityDetail::where('staff_id', $staffId)->where('pay_id', $this->checkNullId($payId))->exists();
    }

    public function infoOfStaff($staffId, $date = null, $confirmStatus = 3, $order = 'DESC')#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        if (empty($date)) {
            if ($confirmStatus == 3) {# chua thanh toan
                return QcPayActivityDetail::where('Staff_id', $staffId)->orderBy('payDate', $order)->get();
            } else {
                return QcPayActivityDetail::where('Staff_id', $staffId)->where('confirmStatus', $confirmStatus)->orderBy('payDate', $order)->get();
            }
        } else {
            if ($confirmStatus == 3) {# chua thanh toan
                return QcPayActivityDetail::where('Staff_id', $staffId)->where('payDate', 'like', "%$date%")->orderBy('payDate', $order)->get();
            } else {
                return QcPayActivityDetail::where('Staff_id', $staffId)->where('payDate', 'like', "%$date%")->where('confirmStatus', $confirmStatus)->orderBy('payDate', $order)->get();
            }
        }
    }

    # lay thong in nhap dung va da xac nhan cua 1 nhan vien
    public function infoConfirmAndInvalidOfStaffAndDate($staffId, $date = null, $order = 'DESC')
    {
        # nhap dung
        $hasInvalid = $this->getDefaultHasInvalid();
        if (empty($date)) {
            return QcPayActivityDetail::where('Staff_id', $staffId)->where('invalidStatus', $hasInvalid)->orderBy('payDate', $order)->get();
        } else {
            return QcPayActivityDetail::where('Staff_id', $staffId)->where('invalidStatus', $hasInvalid)->where('payDate', 'like', "%$date%")->orderBy('payDate', $order)->get();
        }
    }

    # thong tin da xac nhan va hop le
    public function infoConfirmedAndInvalidOfStaffAndDate($staffId, $date = null, $order = 'DESC')
    {
        # da xac nhan
        $hasConfirm = $this->getDefaultHasConfirm();
        # nhap dung
        $hasInvalid = $this->getDefaultHasInvalid();
        if (empty($date)) {
            return QcPayActivityDetail::where('Staff_id', $staffId)->where('confirmStatus', $hasConfirm)->where('invalidStatus', $hasInvalid)->orderBy('payDate', $order)->get();
        } else {
            return QcPayActivityDetail::where('Staff_id', $staffId)->where('confirmStatus', $hasConfirm)->where('invalidStatus', $hasInvalid)->where('payDate', 'like', "%$date%")->orderBy('payDate', $order)->get();
        }
    }

    public function selectInfoOfListCompany($listCompanyId, $date = null, $confirmStatus = 3, $order = 'DESC')#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        if (empty($date)) {
            if ($confirmStatus == 3) {# chua thanh toan
                return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->orderBy('payDate', $order)->select('*');
            } else {
                return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('confirmStatus', $confirmStatus)->orderBy('payDate', $order)->select('*');
            }
        } else {
            if ($confirmStatus == 3) {# chua thanh toan
                return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('payDate', 'like', "%$date%")->orderBy('payDate', $order)->select('*');
            } else {
                return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('payDate', 'like', "%$date%")->where('confirmStatus', $confirmStatus)->orderBy('payDate', $order)->select('*');
            }
        }
    }

    public function selectInfoOfListCompanyAndStaff($listCompanyId, $staffId, $date = null, $confirmStatus = 3, $order = 'DESC')#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        if (empty($date)) {
            if ($confirmStatus == 3) {# chua thanh toan
                return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->orderBy('payDate', $order)->select('*');
            } else {
                return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->where('confirmStatus', $confirmStatus)->orderBy('payDate', $order)->select('*');
            }
        } else {
            if ($confirmStatus == 3) {# chua thanh toan
                return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->where('payDate', 'like', "%$date%")->orderBy('payDate', $order)->select('*');
            } else {
                return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->where('payDate', 'like', "%$date%")->where('confirmStatus', $confirmStatus)->orderBy('payDate', $order)->select('*');
            }
        }
    }

    public function totalMoneyOfListPayActivity($dataPayActivityDetail)
    {
        $hFunction = new \Hfunction();
        $totalMoney = 0;
        if ($hFunction->checkCount($dataPayActivityDetail)) {
            foreach ($dataPayActivityDetail as $value) {
                $totalMoney = $totalMoney + $value->money();
            }
        }
        return $totalMoney;
    }

    //---------- cong ty -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    # tong thong tin chi chua xac nhan
    public function totalPayActivityNotConfirmOfCompany($companyId)
    {
        return QcPayActivityDetail::where('company_id', $companyId)->where('confirmStatus', $this->getDefaultNotConfirm())->count('pay_id');
    }

    //========= ========== ========== LAY THONG TIN========== ========== ==========
    public function getInfo($payId = '', $field = '')
    {
        if (empty($payId)) {
            return QcPayActivityDetail::get();
        } else {
            $result = QcPayActivityDetail::where('pay_id', $payId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    // lay thong tin tu ma thanh toan
    public function infoFromPayCode($payCode)
    {
        return QcPayActivityDetail::where('payCode', $payCode)->first();
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcPayActivityDetail::where('pay_id', $objectId)->pluck($column);
        }
    }

    public function payId()
    {
        return $this->pay_id;
    }

    public function payCode($payId = null)
    {
        return $this->pluck('payCode', $payId);
    }


    public function money($payId = null)
    {

        return $this->pluck('money', $payId);
    }

    public function payDate($payId = null)
    {

        return $this->pluck('payDate', $payId);
    }

    public function payImage($payId = null)
    {

        return $this->pluck('payImage', $payId);
    }

    public function note($payId = null)
    {

        return $this->pluck('note', $payId);
    }

    public function confirmStatus($payId = null)
    {

        return $this->pluck('confirmStatus', $payId);
    }

    public function confirmDate($payId = null)
    {

        return $this->pluck('confirmDate', $payId);
    }

    public function confirmNote($payId = null)
    {

        return $this->pluck('confirmNote', $payId);
    }

    public function invalidStatus($payId = null)
    {

        return $this->pluck('invalidStatus', $payId);
    }

    public function createdAt($payId = null)
    {
        return $this->pluck('created_at', $payId);
    }

    public function typeList($payId = null)
    {
        return $this->pluck('typeList_id', $payId);
    }

    public function companyId($payId = null)
    {
        return $this->pluck('company_id', $payId);
    }

    public function staffId($payId = null)
    {
        return $this->pluck('staff_id', $payId);
    }

    public function confirmStaffId($payId = null)
    {
        return $this->pluck('confirmStaff_id', $payId);
    }

    // tong so thanh toan
    public function totalRecords()
    {
        return QcPayActivityDetail::count();
    }

    // lay id cuoi
    public function lastId()
    {
        $result = QcPayActivityDetail::orderBy('pay_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->pay_id;
    }

    public function checkConfirm($payId = null)
    {
        return ($this->confirmStatus($payId) == $this->getDefaultNotConfirm()) ? false : true;
    }

    public function checkInvalid($payId = null)
    {
        return ($this->invalidStatus($payId) == $this->getDefaultNotInvalid()) ? false : true;
    }

    #============ =========== ============ THONG KE ============= =========== ==========

    public function totalPaidOfCompany($listCompanyId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->sum('money');
        } else {
            return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('payDate', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalPaidOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->sum('money');
        } else {
            return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->where('payDate', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalPaidOfStaffAndCompany($listCompanyId, $staffId, $dateFilter = null)
    {
        if ($dateFilter == null) {
            return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->sum('money');
        } else {
            return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->where('payDate', 'like', "%$dateFilter%")->sum('money');
        }

    }

    # lay ma nhan vien chi theo danh sach ma cty
    public function infoStaffPay($listCompanyId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->groupBy('staff_id')->pluck('staff_id');
        } else {
            return QcPayActivityDetail::whereIn('company_id', $listCompanyId)->where('payDate', 'like', "%$dateFilter%")->groupBy('staff_id')->pluck('staff_id');
        }
    }
}
