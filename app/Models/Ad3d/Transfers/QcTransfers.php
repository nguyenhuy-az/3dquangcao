<?php

namespace App\Models\Ad3d\Transfers;

use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\TransfersDetail\QcTransfersDetail;
use Illuminate\Database\Eloquent\Model;

class QcTransfers extends Model
{
    protected $table = 'qc_transfers';
    protected $fillable = ['transfers_id', 'transfersCode', 'money', 'transfersDate', 'reason', 'transferImage', 'transferType', 'confirmReceive', 'confirmDate', 'confirmNote', 'acceptStatus', 'created_at', 'transfersStaff_id', 'receiveStaff_id', 'company_id'];
    protected $primaryKey = 'transfers_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- Insert ----------

    // insert
    public function insert($money, $transfersDate, $reason, $transferImage, $transfersStaffId, $receiveStaffId, $companyId = null, $transferType = 1)
    {
        #transferType 1 - nhan tu don hang / 2 - nhan dau tu
        $hFunction = new \Hfunction();
        $modelTransfers = new QcTransfers();
        $modelStaff = new QcStaff();
        //create code
        $transfersCode = $hFunction->getTimeCode();
        // insert
        $modelTransfers->transfersCode = $transfersCode;
        $modelTransfers->money = $money;
        $modelTransfers->transfersDate = $transfersDate;
        $modelTransfers->reason = $reason;
        $modelTransfers->transferImage = $transferImage;
        $modelTransfers->transferType = $transferType;
        $modelTransfers->transfersStaff_id = $transfersStaffId;
        $modelTransfers->receiveStaff_id = $receiveStaffId;
        $modelTransfers->company_id = (empty($companyId)) ? $modelStaff->companyId($receiveStaffId) : $companyId;
        $modelTransfers->created_at = $hFunction->createdAt();
        if ($modelTransfers->save()) {
            $this->lastId = $modelTransfers->transfers_id;
            return true;
        } else {
            return false;
        }
    }

    // get new id after insert
    public function insertGetId()
    {
        return $this->lastId;
    }

    // insert
    public function updateInfo($transfersId, $money, $transfersDate, $reason, $receiveStaffId, $companyId = null)
    {
        $modelStaff = new QcStaff();
        $companyId = (empty($companyId)) ? $modelStaff->companyId($receiveStaffId) : $companyId;
        return QcTransfers::where('transfers_id', $transfersId)->update([
            'money' => $money,
            'transfersDate' => $transfersDate,
            'reason' => $reason,
            'receiveStaff_id' => $receiveStaffId,
            'company_id' => $companyId
        ]);
    }

    public function updateConfirmReceive($transfersId, $confirmNote, $acceptStatus)
    {
        $hFunction = new \Hfunction();
        return QcTransfers::where('transfers_id', $transfersId)->update([
            'confirmReceive' => 1,
            'confirmDate' => $hFunction->carbonNow(),
            'confirmNote' => $confirmNote,
            'acceptStatus' => $acceptStatus
        ]);
    }

    public function deleteTransfers($transfersId = null)
    {
        $transfersId = (empty($transfersId)) ? $this->paymentId() : $transfersId;
        return QcTransfers::where('transfers_id', $transfersId)->delete();
    }

    //-----------  quan ly hinh anh ----------
    public function rootPathFullImage()
    {
        return 'public/images/transfer/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/transfer/small';
    }

    public function uploadImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //Xóa ảnh
    public function dropImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    // get path image
    public function pathSmallImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
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
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- company -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'transfersStaff_id', 'company_id');
    }

    # tong tien da nhan va da xac nhan tu tien thu don hang cua 1 cong ty
    public function totalMoneyReceivedFromOrderPayOfCompanyAndDate($companyId, $date = null)
    {
        if (!empty($date)) {
            return QcTransfers::where('company_id', $companyId)->where('transferType', 1)->where('confirmReceive', 1)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('company_id', $companyId)->where('transferType', 1)->where('confirmReceive', 1)->sum('money');
        }
    }

    # tong tien da nhan va da xac nhan tu dau tu cua 1 cong ty
    public function totalMoneyReceivedFromInvestmentOfCompanyAndDate($companyId, $date = null)
    {
        if (!empty($date)) {
            return QcTransfers::where('company_id', $companyId)->where('transferType', 2)->where('confirmReceive', 1)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('company_id', $companyId)->where('transferType', 2)->where('confirmReceive', 1)->sum('money');
        }
    }

    //---------- transfers - staff -----------
    public function transfersStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'transfersStaff_id', 'staff_id');
    }

    //kiển tra người nhập
    public function checkStaffInput($staffId, $transfersId = null)
    {
        $transfersId = (empty($transfersId)) ? $this->transfersId() : $transfersId;
        return (QcTransfers::where('transfersStaff_id', $staffId)->where('transfers_id', $transfersId)->count() > 0) ? true : false;
    }

    public function infoOfTransferStaff($staffId, $date, $orderBy = 'DESC')
    {
        if (!empty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('transfersDate', 'like', "%$date%")->orderBy('transfers_id', $orderBy)->get();
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->orderBy('transfers_id', $orderBy)->get();
        }
    }

    public function infoConfirmedOfTransferStaff($staffId, $date)
    {
        if (!empty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', 1)->where('transfersDate', 'like', "%$date%")->orderBy('transfers_id', 'DESC')->get();
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', 1)->orderBy('transfers_id', 'DESC')->get();
        }

    }

    public function totalMoneyOfTransferStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->sum('money');
        }

    }

    // tong tien da giao va chua xac nhan
    public function totalMoneyUnConfirmOfTransferStaff($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', 0)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', 0)->sum('money');
        }

    }

    // tong tien da giao va da duoc xac nhan
    public function totalMoneyConfirmedOfTransferStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', 1)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', 1)->sum('money');
        }

    }

    // tong tien da giao va da duoc xac nhan dong y
    public function totalMoneyConfirmedAndAcceptedOfTransferStaff($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('acceptStatus', 1)->where('confirmReceive', 1)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('acceptStatus', 1)->where('confirmReceive', 1)->sum('money');
        }

    }

    //---------- receive - staff -----------
    public function receiveStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'receiveStaff_id', 'staff_id');
    }

    public function infoOfReceiveStaff($staffId, $date, $orderBy = 'DESC')
    {
        if (!empty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transfersDate', 'like', "%$date%")->orderBy('transfers_id', $orderBy)->get();
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->orderBy('transfers_id', $orderBy)->get();
        }

    }

    // tong tien da nhan va da duoc xac nhan
    public function infoConfirmedOfReceiveStaff($staffId, $date)
    {
        if (!empty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', 1)->where('transfersDate', 'like', "%$date%")->orderBy('transfers_id', 'DESC')->get();
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', 1)->orderBy('transfers_id', 'DESC')->get();
        }

    }

    public function totalMoneyOfReceiveStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->sum('money');
        }
    }

    # tong tien da nhan va da xac nhan
    public function totalMoneyConfirmedOfReceivedStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', 1)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', 1)->sum('money');
        }
    }

# tong tien da nhan va da xac nhan tu tien thu don hang cua 1 NV
    public function totalMoneyReceivedFromOrderPayOfStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transferType', 1)->where('confirmReceive', 1)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transferType', 1)->where('confirmReceive', 1)->sum('money');
        }
    }

# tong tien da nhan va da xac nhan tu dau tu cua 1 NV
    public function totalMoneyReceivedFromInvestmentOfStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transferType', 2)->where('confirmReceive', 1)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transferType', 2)->where('confirmReceive', 1)->sum('money');
        }
    }

    //---------- chi tiet chuyen tien -----------
    public function transfersDetail()
    {
        return $this->hasMany('App\Models\Ad3d\TransfersDetail\QcTransfersDetail', 'transfers_id', 'transfers_id');
    }

    public function transfersDetailInfo($transfersId = null)
    {
        $modelTransfersDetail = new QcTransfersDetail();
        return $modelTransfersDetail->infoOfTransfers((empty($transfersId)) ? $this->transfersId() : $transfersId);
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    public function getInfo($transfersId = '', $field = '')
    {
        if (empty($transfersId)) {
            return QcTransfers::get();
        } else {
            $result = QcTransfers::where('transfers_id', $transfersId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    // ---------- ---------- STAFF INFO --------- -------
    public function infoFromPaymentCode($transfersCode)
    {
        return QcTransfers::where('transfersCode', $transfersCode)->first();
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcTransfers::where('transfers_id', $objectId)->pluck($column);
        }
    }

    public function transfersId()
    {
        return $this->transfers_id;
    }

    public function transfersCode($transfersId = null)
    {
        return $this->pluck('transfersCode', $transfersId);
    }


    public function money($transfersId = null)
    {

        return $this->pluck('money', $transfersId);
    }

    public function transfersDate($transfersId = null)
    {

        return $this->pluck('transfersDate', $transfersId);
    }

    public function reason($transfersId = null)
    {

        return $this->pluck('reason', $transfersId);
    }

    public function transferImage($transfersId = null)
    {

        return $this->pluck('transferImage', $transfersId);
    }

    public function transferType($transfersId = null)
    {

        return $this->pluck('transferType', $transfersId);
    }

    public function confirmDate($transfersId = null)
    {

        return $this->pluck('confirmDate', $transfersId);
    }

    public function confirmReceive($transfersId = null)
    {

        return $this->pluck('confirmReceive', $transfersId);
    }

    public function createdAt($transfersId = null)
    {
        return $this->pluck('created_at', $transfersId);
    }

    public function transfersStaffId($transfersId = null)
    {
        return $this->pluck('transfersStaff_id', $transfersId);
    }

    public function receiveStaffId($transfersId = null)
    {
        return $this->pluck('receiveStaff_id', $transfersId);
    }

    public function companyId($transfersId = null)
    {
        return $this->pluck('company_id', $transfersId);
    }

    // total records
    public function totalRecords()
    {
        return QcTransfers::count();
    }

    // last id
    public function lastId()
    {
        $result = QcTransfers::orderBy('transfers_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->transfers_id;
    }

    # kiem tra giao tien duoc xac nhan hay chua
    public function checkConfirmReceive($transfersId = null)
    {
        return ($this->confirmReceive($transfersId) == 0) ? false : true;
    }

    #ten hinh thuc giao tien
    public function transferTypeName($transfersId = null)
    {
        if ($this->checkTransferOrderPay($transfersId)) {
            return 'Thu từ đơn hàng';
        } elseif ($this->checkTransferInvestment($transfersId)) {
            return 'Tiền đầu tư';
        } else {
            return 'Chưa xác định';
        }

    }

    # chuyen tien nhan don hang
    public function checkTransferOrderPay($transfersId = null)
    {
        return ($this->transferType($transfersId) == 1) ? true : false;
    }

    # chuyen tien dau tu
    public function checkTransferInvestment($transfersId = null)
    {
        return ($this->transferType($transfersId) == 2) ? true : false;
    }

    #============ =========== ============ thong ke ============= =========== ==========
    public function totalReceiveMoneyOfCompany($listCompanyId, $staffId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcTransfers::whereIn('company_id', $listCompanyId)->where('receiveStaff_id', $staffId)->sum('money');
        } else {
            return QcTransfers::whereIn('company_id', $listCompanyId)->where('receiveStaff_id', $staffId)->where('transfersDate', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalTransfersMoneyOfStaff($staffId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('transfersDate', 'like', "%$dateFilter%")->sum('money');
        }
    }
}
