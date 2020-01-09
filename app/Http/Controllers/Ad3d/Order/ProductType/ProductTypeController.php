<?php

namespace App\Http\Controllers\Ad3d\Order\ProductType;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\ProductType\QcProductType;
use App\Models\Ad3d\ProductTypeImage\QcProductTypeImage;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class ProductTypeController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataAccess = [
            'accessObject' => 'productType'
        ];
        $dataProductType = QcProductType::where('action', 1)->orderBy('name', 'ASC')->select('*')->paginate(30);
        return view('ad3d.order.product-type.list', compact('modelStaff', 'modelCompany', 'dataProductType', 'dataAccess'));

    }

    public function view($typeId)
    {
        $modelProductType = new QcProductType();
        if (!empty($typeId)) {
            $dataProductType = $modelProductType->getInfo($typeId);
            return view('ad3d.order.product-type.view', compact('dataProductType'));
        }
    }

    //add
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'productType'
        ];
        return view('ad3d.order.product-type.add', compact('modelStaff', 'dataAccess'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelProductType = new QcProductType();
        $modelProductTypeImage = new QcProductTypeImage();
        $name = Request::input('txtName');
        $typeCode = Request::input('txtTypeCode');
        $txtUnit = Request::input('txtUnit');
        $txtDescription = Request::input('txtDescription');
        $txtImage_1 = Request::file('txtImage_1');
        $txtImage_2 = Request::file('txtImage_2');
        $txtImage_3 = Request::file('txtImage_3');

        // check exist of name
        if ($modelProductType->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelProductType->existTypeCode($name)) {
                Session::put('notifyAdd', "Thêm thất bại, mã <b>'$typeCode'</b> đã tồn tại.");
            } else {
                if ($modelProductType->insert($typeCode, $name, $txtDescription, $txtUnit)) {
                    $newTypeId = $modelProductType->insertGetId();
                    # anh bao cao 1
                    if (!empty($txtImage_1)) {
                        $name_img = stripslashes($_FILES['txtImage_1']['name']);
                        $name_img = $hFunction->getTimeCode() . '_1.' . $hFunction->getTypeImg($name_img);
                        $source_img = $_FILES['txtImage_1']['tmp_name'];
                        if ($modelProductTypeImage->uploadImage($source_img, $name_img, 500)) {
                            $modelProductTypeImage->insert($name_img, $newTypeId);
                        }
                    }
                    # anh bao cao 2
                    if (!empty($txtImage_2)) {
                        $name_img = stripslashes($_FILES['txtImage_2']['name']);
                        $name_img = $hFunction->getTimeCode() . '_2.' . $hFunction->getTypeImg($name_img);
                        $source_img = $_FILES['txtImage_2']['tmp_name'];
                        if ($modelProductTypeImage->uploadImage($source_img, $name_img, 500)) {
                            $modelProductTypeImage->insert($name_img, $newTypeId);
                        }
                    }

                    # anh bao cao 3
                    if (!empty($txtImage_3)) {
                        $name_img = stripslashes($_FILES['txtImage_3']['name']);
                        $name_img = $hFunction->getTimeCode() . '_3.' . $hFunction->getTypeImg($name_img);
                        $source_img = $_FILES['txtImage_3']['tmp_name'];
                        if ($modelProductTypeImage->uploadImage($source_img, $name_img, 500)) {
                            $modelProductTypeImage->insert($name_img, $newTypeId);
                        }
                    }
                    Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
                } else {
                    Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
                }
            }
        }
    }

    //edit
    public function getEdit($typeId)
    {
        $modelProductType = new QcProductType();
        $dataProductType = $modelProductType->getInfo($typeId);
        if (count($dataProductType) > 0) {
            return view('ad3d.order.product-type.edit', compact('dataProductType'));
        }
    }

    public function postEdit($typeId)
    {
        $dataProductType = new QcProductType();
        $name = Request::input('txtName');
        $typeCode = Request::input('txtTypeCode');
        $txtUnit = Request::input('txtUnit');
        $txtDescription = Request::input('txtDescription');
        $notifyContent = null;
        if ($dataProductType->existEditName($typeId, $name)) {
            $notifyContent = "Tên <b>'$name'</b> đã tồn tại.";
        }
        if ($dataProductType->existEditTypeCode($typeId, $typeCode)) {
            if (empty($notifyContent)) {
                $notifyContent = "Mã <b>'$typeCode'</b> đã tồn tại.";
            } else {
                $notifyContent = $notifyContent . "<br/> Mã <b>'$typeCode'</b> đã tồn tại.";
            }
        }
        if (!empty($notifyContent)) {
            return $notifyContent;
        } else {
            $dataProductType->updateInfo($typeId, $typeCode, $name, $txtDescription, $txtUnit);
        }
    }

    public function deleteImage($imageId)
    {
        $modelProductTypeImage = new QcProductTypeImage();
        return $modelProductTypeImage->actionDelete($imageId);
    }

    // ANH MAU
    public function getAddImage($typeId)
    {
        $modelProductType = new QcProductType();
        $dataProductType = $modelProductType->getInfo($typeId);
        if (count($dataProductType) > 0) {
            return view('ad3d.order.product-type.add-image', compact('dataProductType'));
        }
    }

    public function postAddImage($typeId)
    {
        $hFunction = new \Hfunction();
        $modelProductTypeImage = new QcProductTypeImage();
        $txtImage_1 = Request::file('txtImage_1');
        $txtImage_2 = Request::file('txtImage_2');
        $txtImage_3 = Request::file('txtImage_3');
        if (!empty($txtImage_1)) {
            $name_img = stripslashes($_FILES['txtImage_1']['name']);
            $name_img = $hFunction->getTimeCode() . '_1.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtImage_1']['tmp_name'];
            if ($modelProductTypeImage->uploadImage($source_img, $name_img, 500)) {
                $modelProductTypeImage->insert($name_img, $typeId);
            }
        }
        # anh bao cao 2
        if (!empty($txtImage_2)) {
            $name_img = stripslashes($_FILES['txtImage_2']['name']);
            $name_img = $hFunction->getTimeCode() . '_2.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtImage_2']['tmp_name'];
            if ($modelProductTypeImage->uploadImage($source_img, $name_img, 500)) {
                $modelProductTypeImage->insert($name_img, $typeId);
            }
        }

        # anh bao cao 3
        if (!empty($txtImage_3)) {
            $name_img = stripslashes($_FILES['txtImage_3']['name']);
            $name_img = $hFunction->getTimeCode() . '_3.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtImage_3']['tmp_name'];
            if ($modelProductTypeImage->uploadImage($source_img, $name_img, 500)) {
                $modelProductTypeImage->insert($name_img, $typeId);
            }
        }
    }
    public function viewImage($imageId)
    {
        $modelProductTypeImage = new QcProductTypeImage();
        if (!empty($imageId)) {
            $dataProductTypeImage = $modelProductTypeImage->getInfo($imageId);
            return view('ad3d.order.product-type.view-image', compact('dataProductTypeImage'));
        }
    }

    // delete
    public function deleteType($typeId)
    {
        if (!empty($typeId)) {
            $dataProductType = new QcProductType();
            $dataProductType->actionDelete($typeId);
        }
    }
}
