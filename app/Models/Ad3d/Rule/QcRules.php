<?php

namespace App\Models\Ad3d\Rule;

use Illuminate\Database\Eloquent\Model;

class QcRules extends Model
{
    protected $table = 'qc_rules';
    protected $fillable = ['rules_id', 'title', 'content', 'created_at'];
    protected $primaryKey = 'rules_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($title, $content)
    {
        $hFunction = new \Hfunction();
        $modelRules = new QcRules();
        $modelRules->title = $title;
        $modelRules->content = $content;
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
        if (empty($rulesId)) $rulesId = $this->rulesId();
        return QcRules::where('rules_id', $rulesId)->delete();
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($rulesId = '', $field = '')
    {
        if (empty($rulesId)) {
            return QcRules::get();
        } else {
            $result = QcRules::where('rules_id', $rulesId)->first();
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
            return QcRules::where('rules_id', $objectId)->pluck($column);
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

    # exist name (add new)
    public function existTitle($title)
    {
        $result = QcRules::where('title', $title)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditTitle($rulesId, $title)
    {
        $result = QcRules::where('title', $title)->where('rules_id', '<>', $rulesId)->count();
        return ($result > 0) ? true : false;
    }
}
