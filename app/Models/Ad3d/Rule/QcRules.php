<?php

namespace App\Models\Ad3d\Rule;

use Illuminate\Database\Eloquent\Model;

class QcRules extends Model
{
    protected $table = 'qc_rules';
    protected $fillable = ['rules_id', 'title', 'content', 'created_at', 'company_id'];
    protected $primaryKey = 'rules_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    public function checkNullId($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->rulesId() : $id;
    }

    #---------- Insert ----------
    public function insert($title, $content, $companyId)
    {
        $hFunction = new \Hfunction();
        $modelRules = new QcRules();
        $modelRules->title = $title;
        $modelRules->content = $content;
        $modelRules->company_id = $companyId;
        $modelRules->created_at = $hFunction->createdAt();
        if ($modelRules->save()) {
            $this->lastId = $modelRules->rules_id;
            return true;
        } else {
            return false;
        }
    }

    # get new id
    public function insertGetId()
    {
        return $this->lastId;
    }

    #----------- update ----------
    public function updateInfo($rulesId, $title, $content)
    {
        return QcRules::where('rules_id', $rulesId)->update([
            'title' => $title,
            'content' => $content
        ]);
    }

    # delete
    public function actionDelete($rulesId = null)
    {
        return QcRules::where('rules_id', $this->checkNullId($rulesId))->delete();
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($rulesId = null, $field = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($rulesId)) {
            return QcRules::get();
        } else {
            $result = QcRules::where('rules_id', $rulesId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }


    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcRules::where('rules_id', $objectId)->pluck($column)[0];
        }
    }

    #----------- DEPARTMENT INFO -------------
    public function rulesId()
    {
        return $this->rules_id;
    }

    public function title($rulesId = null)
    {
        return $this->pluck('title', $rulesId);
    }

    public function content($rulesId = null)
    {
        return $this->pluck('content', $rulesId);
    }

    public function createdAt($rulesId = null)
    {
        return $this->pluck('created_at', $rulesId);
    }

    # total record
    public function totalRecords()
    {
        return QcRules::count();
    }

    # ---------- cong ty -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    public function getInfoOfCompany($companyId)
    {
        return QcRules::where('company_id', $companyId)->first();
    }

    # exist name (add new)
    public function existTitle($title)
    {
        return QcRules::where('title', $title)->exists();
    }

    public function existEditTitle($rulesId, $title)
    {
        return QcRules::where('title', $title)->where('rules_id', '<>', $rulesId)->exists();
    }
}
