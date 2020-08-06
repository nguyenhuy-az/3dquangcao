<?php

namespace App\Models\Ad3d\ImportImage;

use Illuminate\Database\Eloquent\Model;

class QcImportImage extends Model
{
    protected $table = 'qc_import_image';
    protected $fillable = ['image_id', 'name', 'created_at', 'import_id'];
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($name, $importId)
    {
        $hFunction = new \Hfunction();
        $modelImportImage = new QcImportImage();
        $modelImportImage->name = $name;
        $modelImportImage->import_id = $importId;
        $modelImportImage->created_at = $hFunction->createdAt();
        if ($modelImportImage->save()) {
            $this->lastId = $modelImportImage->image_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    #----------- update ----------
    public function rootPathFullImage()
    {
        return 'public/images/import-image/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/import-image/small';
    }

    public function updateInfo($imageId, $name)
    {
        return QcImportImage::where('image_id', $imageId)->update([
            'name' => $name
        ]);
    }

    # xóa
    public function actionDelete($imageId = null)
    {
        if (empty($imageId)) $imageId = $this->imageId();
        return QcImportImage::where('image_id', $imageId)->delete();
    }

    //thêm hình ảnh
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

    #========== ========== ========== RELATION ========== ========== ==========
    #----------- thông tin nhập ------------
    public function import()
    {
        return $this->belongsTo('App\Models\Ad3d\Import\QcImport', 'import_id', 'import_id');
    }

    public function infoOfImport($importId)
    {
        return QcImportImage::where('import_id', $importId)->get();
    }

    # chi lay 1 anh
    public function oneInfoOfImport($importId)
    {
        return QcImportImage::where('import_id', $importId)->first();
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($imageId = '', $field = '')
    {
        if (empty($imageId)) {
            return QcImportImage::get();
        } else {
            $result = QcImportImage::where('image_id', $imageId)->first();
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
            return QcImportImage::where('image_id', $objectId)->pluck($column);
        }
    }

    #----------- DEPARTMENT INFO -------------
    public function imageId()
    {
        return $this->image_id;
    }

    public function name($imageId = null)
    {
        return $this->pluck('name', $imageId);
    }

    public function createdAt($imageId)
    {
        return $this->pluck('created_at', $imageId);
    }

    // get path image
    public function pathSmallImage($image = null)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset('public/images/import-image/small/' . $image);
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
}
