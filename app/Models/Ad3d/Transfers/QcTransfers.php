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

    /*
    transferType = 1; // kinh doanh nop tien cho thu quỹ cap nhan vien
    transferType = 2; // Thu quy cap quan ly chuyen tien dau tu cho thu quy nhan vien chi hoat dong cong ty
    transferType = 3; // Thu quy cap nhan vien nop tien cho Thu quy cap quan ly - nop cho cong ty
    */
    # mac dinh kinh doanh nop tien thu quy
    public function getDefaultTransferTypeOfBusiness()
    {
        return 1;
    }

    # mac dinh thu quy quan ly chuyen tien dau tu
    public function getDefaultTransferTypeOfInvestment()
    {
        return 2;
    }

    # mac dinh thu quy nop cho cty
    public function getDefaultTransferTypeOfTreasurer()
    {
        return 3;
    }

    # mac dinh tat ca hinh thuc chuyen tien
    public function getDefaultAllTransferType()
    {
        return 100;
    }

    # mac dinh co xac nhan
    public function getDefaultHasConfirmReceive()
    {
        return 1;
    }

    # mac dinh khong xac nhan
    public function getDefaultNotConfirmReceive()
    {
        return 0;
    }

    # mac dinh co chap nhan
    public function getDefaultHasAccept()
    {
        return 1;
    }

    # mac dinh khong chap nhan
    public function getDefaultNotAccept()
    {
        return 0;
    }

    # mac dinh hinh anh
    public function getDefaultTransferImage()
    {
        return null;
    }
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
        $modelTransfers->company_id = ($hFunction->checkEmpty($companyId)) ? $modelStaff->companyId($receiveStaffId) : $companyId;
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

    # kiem tra id
    public function checkNullId($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->transfersId() : $id;
    }

    // insert
    public function updateInfo($transfersId, $money, $reason)
    {
        return QcTransfers::where('transfers_id', $transfersId)->update([
            'money' => $money,
            'reason' => $reason
        ]);
    }

    # cap nhat so tien'
    public function updateMoney($transferId, $money)
    {
        return QcTransfers::where('transfers_id', $transferId)->update(['money' => $money]);
    }

    public function updateConfirmReceive($transfersId, $confirmNote, $acceptStatus)
    {
        $hFunction = new \Hfunction();
        return QcTransfers::where('transfers_id', $transfersId)->update([
            'confirmReceive' => $this->getDefaultHasConfirmReceive(),
            'confirmDate' => $hFunction->carbonNow(),
            'confirmNote' => $confirmNote,
            'acceptStatus' => $acceptStatus
        ]);
    }

    public function deleteTransfers($transfersId = null)
    {
        return QcTransfers::where('transfers_id', $this->checkNullId($transfersId))->delete();
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
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
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
        $hFunction = new \Hfunction();
        # kinh doanh nop tien
        $transferType = $this->getDefaultTransferTypeOfBusiness();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('company_id', $companyId)->where('transferType', $transferType)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('company_id', $companyId)->where('transferType', $transferType)->where('confirmReceive', $confirmStatus)->sum('money');
        }
    }

    # tong tien da nhan va da xac nhan tu dau tu cua 1 cong ty
    public function totalMoneyReceivedFromInvestmentOfCompanyAndDate($companyId, $date = null)
    {
        $hFunction = new \Hfunction();
        # tien dau tu
        $transferType = $this->getDefaultTransferTypeOfInvestment();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('company_id', $companyId)->where('transferType', $transferType)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('company_id', $companyId)->where('transferType', $transferType)->where('confirmReceive', $confirmStatus)->sum('money');
        }
    }

    # tong tien da nhan va chua xac nhan cua 1 cong ty
    public function sumReceiveUnconfirmedOfCompany($companyId, $date = null)
    {
        $hFunction = new \Hfunction();
        # chua xac nhan
        $notConfirm = $this->getDefaultNotConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('company_id', $companyId)->where('confirmReceive', $notConfirm)->where('transfersDate', 'like', "%$date%")->count();
        } else {
            return QcTransfers::where('company_id', $companyId)->where('confirmReceive', $notConfirm)->count();
        }

    }

    # chon thong tin chuyen tien theo 1 danh sach ma nhan vien / cong ty theo ngay thang
    public function selectInfoOfCompanyAndDate($companyId, $date, $transfersType = 100)
    {
        if ($transfersType == $this->getDefaultAllTransferType()) {
            return QcTransfers::where('transfersDate', 'like', "%$date%")->where('company_id', $companyId)->orderBy('transfersDate', 'DESC')->select('*');
        } else {
            return QcTransfers::where('transfersDate', 'like', "%$date%")->where('company_id', $companyId)->where('transferType', $transfersType)->orderBy('transfersDate', 'DESC')->select('*');
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
        return QcTransfers::where('transfersStaff_id', $staffId)->where('transfers_id', $this->checkNullId($transfersId))->exists();
    }

    public function infoOfTransferStaff($staffId, $date, $orderBy = 'DESC')
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('transfersDate', 'like', "%$date%")->orderBy('transfers_id', $orderBy)->get();
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->orderBy('transfers_id', $orderBy)->get();
        }
    }

    public function infoConfirmedOfTransferStaff($staffId, $date)
    {
        $hFunction = new \Hfunction();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->orderBy('transfers_id', 'DESC')->get();
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->orderBy('transfers_id', 'DESC')->get();
        }

    }

    // tong tien da giao cua 1 nhan vien
    public function totalMoneyOfTransferStaffAndDate($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->sum('money');
        }

    }

    # tong tien da giao va chua xac nhan
    public function totalMoneyUnConfirmOfTransferStaff($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        # chua xac nhan
        $confirmStatus = $this->getDefaultNotConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->sum('money');
        }

    }

    # tong tien da giao va da duoc xac nhan
    public function totalMoneyConfirmedOfTransferStaffAndDate($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->sum('money');
        }

    }

    # tong tien da giao va da duoc xac nhan dong y
    public function totalMoneyConfirmedAndAcceptedOfTransferStaff($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        # co dong y
        $acceptStatus = $this->getDefaultHasAccept();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('acceptStatus', $acceptStatus)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('acceptStatus', $acceptStatus)->where('confirmReceive', $confirmStatus)->sum('money');
        }

    }

    # tong tien nop cho cong ty cua 1 nhan vien - nop cho thu quy cap quan ly
    public function totalMoneyConfirmedTransfersForTreasurerManage($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        # thu quy nop cong ty
        $transferType = $this->getDefaultTransferTypeOfTreasurer();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->where('transferType', $transferType)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->where('transferType', $transferType)->sum('money');
        }

    }

    //---------- receive - staff -----------
    public function receiveStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'receiveStaff_id', 'staff_id');
    }

    public function infoOfReceiveStaff($staffId, $date, $orderBy = 'DESC')
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transfersDate', 'like', "%$date%")->orderBy('transfers_id', $orderBy)->get();
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->orderBy('transfers_id', $orderBy)->get();
        }

    }

    # tong tien da nhan va da duoc xac nhan
    public function infoConfirmedOfReceiveStaff($staffId, $date)
    {
        $hFunction = new \Hfunction();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->orderBy('transfers_id', 'DESC')->get();
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->orderBy('transfers_id', 'DESC')->get();
        }

    }

    public function totalMoneyOfReceiveStaffAndDate($staffId, $date)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->sum('money');
        }
    }

    # tong tien da nhan va da xac nhan
    public function totalMoneyConfirmedOfReceivedStaffAndDate($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', $confirmStatus)->sum('money');
        }
    }

    # tong tien da nhan va da xac nhan tu tien thu don hang cua 1 NV
    public function totalMoneyReceivedFromOrderPayOfStaffAndDate($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        # kinh doanh nop tien
        $transferType = $this->getDefaultTransferTypeOfBusiness();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transferType', $transferType)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transferType', $transferType)->where('confirmReceive', $confirmStatus)->sum('money');
        }
    }

    # tong tien da nhan va da xac nhan tu dau tu cua 1 NV
    public function totalMoneyReceivedFromInvestmentOfStaffAndDate($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirmReceive();
        # chuyen dau tu
        $transferType = $this->getDefaultTransferTypeOfInvestment();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transferType', $transferType)->where('confirmReceive', $confirmStatus)->where('transfersDate', 'like', "%$date%")->sum('money');
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('transferType', $transferType)->where('confirmReceive', $confirmStatus)->sum('money');
        }
    }

    # tong tien da nhan va chua xac nhan cua 1 nhan vien
    public function sumReceiveUnconfirmedOfStaff($staffId, $date)
    {
        $hFunction = new \Hfunction();
        # chua xac nhan
        $notConfirm = $this->getDefaultNotConfirmReceive();
        if (!$hFunction->checkEmpty($date)) {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', $notConfirm)->where('transfersDate', 'like', "%$date%")->count();
        } else {
            return QcTransfers::where('receiveStaff_id', $staffId)->where('confirmReceive', $notConfirm)->count();
        }

    }

    //---------- chi tiet chuyen tien -----------
    public function transfersDetail()
    {
        return $this->hasMany('App\Models\Ad3d\TransfersDetail\QcTransfersDetail', 'transfers_id', 'transfers_id');
    }

    # thong tin chi tiet chuyen
    public function transfersDetailInfo($transfersId = null)
    {
        $modelTransfersDetail = new QcTransfersDetail();
        return $modelTransfersDetail->infoOfTransfers($this->checkNullId($transfersId));
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    public function getInfo($transfersId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($transfersId)) {
            return QcTransfers::get();
        } else {
            $result = QcTransfers::where('transfers_id', $transfersId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    // ---------- ----------  LAY THONG TIN --------- -------
    # chon thong tin chuyen tien theo 1 danh sach ma nhan vien / cong ty theo ngay thang
    public function selectInfoByListReceiveStaffAndDate($listStaffId, $companyId, $date, $transfersType = 100)
    {
        if ($transfersType == $this->getDefaultAllTransferType()) {
            return QcTransfers::wherein('receiveStaff_id', $listStaffId)->where('transfersDate', 'like', "%$date%")->where('company_id', $companyId)->orderBy('transfersDate', 'DESC')->select('*');
        } else {
            return QcTransfers::wherein('receiveStaff_id', $listStaffId)->where('transfersDate', 'like', "%$date%")->where('company_id', $companyId)->where('transferType', $transfersType)->orderBy('transfersDate', 'DESC')->select('*');
        }
    }

    # chon thong tin chuyen tien theo 1 danh sach ma nhan vien / cong ty theo ngay thang
    public function selectInfoByListTransfersStaffAndDate($listStaffId, $companyId, $date, $transfersType = 100)
    {
        if ($transfersType == $this->getDefaultAllTransferType()) {
            return QcTransfers::wherein('transfersStaff_id', $listStaffId)->where('transfersDate', 'like', "%$date%")->where('company_id', $companyId)->orderBy('transfersDate', 'DESC')->select('*');
        } else {
            return QcTransfers::wherein('transfersStaff_id', $listStaffId)->where('transfersDate', 'like', "%$date%")->where('company_id', $companyId)->where('transferType', $transfersType)->orderBy('transfersDate', 'DESC')->select('*');
        }
    }

    # tong so tien chuyen theo danh sach thong tin chuyen
    public function totalMoneyByListInfo($dataTransfers)
    {
        $hFunction = new \Hfunction();
        $totalMoney = 0;
        if ($hFunction->checkCount($dataTransfers)) {
            foreach ($dataTransfers as $transfers)
                $totalMoney = $totalMoney + $transfers->money();
        }
        return $totalMoney;
    }

    # lay thong tin thep ma thanh toan
    public function infoFromPaymentCode($transfersCode)
    {
        return QcTransfers::where('transfersCode', $transfersCode)->first();
    }

    # lay thong tin 1 cot theo ma nhap vao
    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcTransfers::where('transfers_id', $objectId)->pluck($column)[0];
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

    public function transferTypeLabel($transferType)
    {
        if ($transferType == $this->getDefaultTransferTypeOfBusiness()) {
            return 'Chuyển doanh thu';
        } elseif ($transferType == $this->getDefaultTransferTypeOfInvestment()) {
            return 'Chuyển đầu tư';
        } elseif ($transferType == $this->getDefaultTransferTypeOfTreasurer()) {
            return 'Nộp tiền lên công ty';
        } else {
            return 'Không xác định';
        }
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

    # total records
    public function totalRecords()
    {
        return QcTransfers::count();
    }

    # last id
    public function lastId()
    {
        $hFunction = new \Hfunction();
        $result = QcTransfers::orderBy('transfers_id', 'DESC')->first();
        return ($hFunction->checkEmpty($result)) ? 0 : $result->transfers_id;
    }

    # kiem tra giao tien duoc xac nhan hay chua
    public function checkConfirmReceive($transfersId = null)
    {
        return ($this->confirmReceive($transfersId) == $this->getDefaultNotConfirmReceive()) ? false : true;
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
        return ($this->transferType($transfersId) == $this->getDefaultTransferTypeOfBusiness()) ? true : false;
    }

    # chuyen tien dau tu
    public function checkTransferInvestment($transfersId = null)
    {
        return ($this->transferType($transfersId) == $this->getDefaultTransferTypeOfInvestment()) ? true : false;
    }

    #============ =========== ============ thong ke ============= =========== ==========
    public function totalReceiveMoneyOfCompany($listCompanyId, $staffId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcTransfers::whereIn('company_id', $listCompanyId)->where('receiveStaff_id', $staffId)->sum('money');
        } else {
            return QcTransfers::whereIn('company_id', $listCompanyId)->where('receiveStaff_id', $staffId)->where('transfersDate', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalTransfersMoneyOfStaff($staffId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcTransfers::where('transfersStaff_id', $staffId)->sum('money');
        } else {
            return QcTransfers::where('transfersStaff_id', $staffId)->where('transfersDate', 'like', "%$dateFilter%")->sum('money');
        }
    }
}
