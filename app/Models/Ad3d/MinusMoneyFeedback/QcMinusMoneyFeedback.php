<?php

namespace App\Models\Ad3d\MinusMoneyFeedback;

use Illuminate\Database\Eloquent\Model;

class QcMinusMoneyFeedback extends Model
{
    protected $table = 'qc_minus_money_feedback';
    protected $fillable = ['feedback_id', 'content', 'image', 'confirmDate', 'confirmStatus', 'confirmAccept', 'created_at', 'minus_id', 'confirmStaff_id'];
    protected $primaryKey = 'feedback_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($content, $image, $minusId)
    {
        $hFunction = new \Hfunction();
        $modelMinusMoneyFeedback = new QcMinusMoneyFeedback();
        $modelMinusMoneyFeedback->content = $content;
        $modelMinusMoneyFeedback->image = $image;
        $modelMinusMoneyFeedback->minus_id = $minusId;
        $modelMinusMoneyFeedback->created_at = $hFunction->createdAt();
        if ($modelMinusMoneyFeedback->save()) {
            $this->lastId = $modelMinusMoneyFeedback->feedback_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($feedbackId)
    {
        return (empty($feedbackId)) ? $this->feedbackId() : $feedbackId;
    }

    // xac nhan chay kpi
    public function confirmFeedback($feedbackId, $confirmAccept, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        return QcMinusMoneyFeedback::where('feedback_id', $feedbackId)->update([
            'confirmStatus' => 1,
            'confirmStaff_id' => $confirmStaffId,
            'confirmDate' => $hFunction->carbonNow(),
            'confirmAccept' => $confirmAccept,
        ]);
    }

    # hinh anh
    public function rootPathFullImage()
    {
        return 'public/images/minus-money-feedback/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/minus-money-feedback/small';
    }

    # xóa 1 hình ảnh
    public function deleteFeedback($feedbackId = null)
    {
        $imageName = $this->image($feedbackId);
        if (QcMinusMoneyFeedback::where('feedback_id', $feedbackId)->delete()) {
            $this->dropImage($imageName);
        }
    }

    //upload image
    public function uploadImage($source_img, $imageName, $resize = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($source_img, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $resize);
    }

    //drop image
    public function dropImage($imageName)
    {
        if (file_exists($this->rootPathSmallImage() . '/' . $imageName)) unlink($this->rootPathSmallImage() . '/' . $imageName);
        if (file_exists($this->rootPathFullImage() . '/' . $imageName)) unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    // get path image
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
    //---------- thong tin phat -----------
    public function minusMoney()
    {
        return $this->belongsTo('App\Models\Ad3d\MinusMoney\QcMinusMoney', 'minus_id', 'minus_id');
    }

    public function infoOfMinusMoney($minusId)
    {
        return QcMinusMoneyFeedback::where('minus_id', $minusId)->first();
    }

    //---------- NHAN VIEN XAC NHAN -----------
    # nha vien xac nhan
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //========= ========== ========== LAY THONG TIN ========== ========== ==========
    public function getInfo($feedbackId = '', $field = '')
    {
        if (empty($feedbackId)) {
            return QcMinusMoneyFeedback::get();
        } else {
            $result = QcMinusMoneyFeedback::where('feedback_id', $feedbackId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcMinusMoneyFeedback::where('feedback_id', $objectId)->pluck($column);
        }
    }

    public function feedbackId()
    {
        return $this->feedback_id;
    }

    public function content($feedbackId = null)
    {
        return $this->pluck('content', $feedbackId);
    }


    public function image($feedbackId = null)
    {

        return $this->pluck('image', $feedbackId);
    }

    public function confirmStatus($feedbackId = null)
    {

        return $this->pluck('confirmStatus', $feedbackId);
    }

    public function confirmDate($feedbackId = null)
    {

        return $this->pluck('confirmDate', $feedbackId);
    }

    public function confirmAccept($feedbackId = null)
    {

        return $this->pluck('confirmAccept', $feedbackId);
    }

    public function action($feedbackId = null)
    {

        return $this->pluck('action', $feedbackId);
    }

    public function createdAt($feedbackId = null)
    {
        return $this->pluck('created_at', $feedbackId);
    }

    public function minusId($feedbackId = null)
    {
        return $this->pluck('minus_id', $feedbackId);
    }

    public function confirmStaffId($feedbackId = null)
    {
        return $this->pluck('confirmStaff_id', $feedbackId);
    }

    // last id
    public function lastId()
    {
        $result = QcMinusMoneyFeedback::orderBy('feedback_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->feedback_id;
    }

    public function checkConfirm($feedbackId = null)
    {
        return ($this->confirmStatus($feedbackId) == 1) ? true : false;
    }

    public function checkConfirmAccept($feedbackId = null)
    {
        return ($this->confirmAccept($feedbackId) == 1) ? true : false;
    }
}
