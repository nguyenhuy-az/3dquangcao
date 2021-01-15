<?php

namespace App\Models\Ad3d\Customer;

use App\Models\Ad3d\Order\QcOrder;
use Illuminate\Database\Eloquent\Model;
use DB;
use File, input, Request;

class QcCustomer extends Model
{
    protected $table = 'qc_customers';
    protected $fillable = ['customer_id', 'nameCode', 'name', 'birthday', 'address', 'phone', 'email', 'zalo', 'created_at'];
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    private $lastId;

    #mac dinh ngay sinh
    public function getDefaultBirthday()
    {
        return null;
    }

    #mac dinh dia chi
    public function getDefaultAddress()
    {
        return null;
    }

    # mac dinh so dien thoai
    public function getDefaultPhone()
    {
        return null;
    }

    # mac dinh email
    public function getDefaultEmail()
    {
        return null;
    }

    # mac dinh zalo
    public function getDefaultZalo()
    {
        return null;
    }

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- Insert ----------

    // insert
    public function insert($name, $birthday = null, $email = null, $address = null, $phone = null, $zalo)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcCustomer();
        //create code
        $nameCode = $hFunction->getTimeCode();
        // insert
        //$name = $hFunction->convertValidHTML($name);
        $modelStaff->nameCode = $nameCode;
        $modelStaff->name = $name;
        $modelStaff->birthday = $birthday;
        $modelStaff->email = $hFunction->convertValidHTML($email);
        $modelStaff->address = $hFunction->convertValidHTML($address);
        $modelStaff->phone = $phone;
        $modelStaff->zalo = $zalo;
        $modelStaff->created_at = $hFunction->createdAt();
        if ($modelStaff->save()) {
            $this->lastId = $modelStaff->customer_id;
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

    //cap nhat thong tin
    public function updateInfo($customerId, $name, $birthday, $email, $address, $phone, $zalo)
    {
        $hFunction = new \Hfunction();
        return QcCustomer::where('customer_id', $customerId)->update([
            'name' => $hFunction->convertValidHTML($name),
            'birthday' => $birthday,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'zalo' => $zalo
        ]);
    }

    //========== ========= ========= RELATION ========== ========= ==========

    //---------- ORDER -----------
    public function order()
    {
        return $this->hasMany('App\Models\Ad3d\Order\QcOrder', 'customer_id', 'customer_id');
    }

    public function orderInfoAllOfCustomer($customerId)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->infoAllOfCustomer($customerId);
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    public function getInfo($customerId = '', $field = '')
    {
        if (empty($customerId)) {
            return QcCustomer::get();
        } else {
            $result = QcCustomer::where('customer_id', $customerId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    // create option of select
    public function getOption($selected = '')
    {
        $hFunction = new \Hfunction();
        $dataStaff = DB::select("select customer_id as optionKey, name as optionValue from qc_customers ");
        return $hFunction->option($dataStaff, $selected);
    }


    // ---------- ---------- STAFF INFO --------- -------
    public function listIdByKeywordName($name)
    {
        return QcCustomer::where('name', 'like', "%$name%")->pluck('customer_id');
    }

    public function infoFromSuggestionName($name)
    {
        return QcCustomer::where('name', 'like', "%$name%")->get();
    }

    public function infoFromSuggestionPhone($phone)
    {
        return QcCustomer::where('phone', 'like', "%$phone%")->get();
    }

    public function infoFromPhone($phone)
    {
        return QcCustomer::where('phone', $phone)->first();
    }

    public function infoFromZalo($zalo)
    {
        return QcCustomer::where('zalo', $zalo)->first();
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcCustomer::where('customer_id', $objectId)->pluck($column)[0];
        }
    }

    public function customerId()
    {
        return $this->customer_id;
    }

    public function name($customerId = null)
    {
        $hFunction = new \Hfunction();
        return $this->pluck('name', $customerId);
    }

    public function nameCode($customerId = null)
    {
        return $this->pluck('nameCode', $customerId);
    }


    public function birthday($customerId = null)
    {

        return $this->pluck('birthday', $customerId);
    }

    public function email($customerId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('email', $customerId));
    }

    public function phone($customerId = null)
    {

        return $this->pluck('phone', $customerId);
    }

    public function address($customerId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('address', $customerId));
    }

    public function zalo($customerId = null)
    {
        return $this->pluck('zalo', $customerId);
    }

    public function createdAt($customerId = null)
    {
        return $this->pluck('created_at', $customerId);
    }

    // total records
    public function totalRecords()
    {
        return QcCustomer::count();
    }

    // last id
    public function lastId()
    {
        $hFunction = new \Hfunction();
        $result = QcCustomer::orderBy('customer_id', 'DESC')->first();
        return ($hFunction->checkEmpty($result)) ? 0 : $result->customer_id;
    }

    //========== ========== ========= CHECK INFO ========== ========= =========
    // exist of phone
    public function existPhone($phone)
    {
        return QcCustomer::where('phone', $phone)->exists();
    }
}
