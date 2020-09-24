<?php

namespace App\Models\Ad3d\Statistical;

use App\Models\Ad3d\JobApplication\QcJobApplication;
use App\Models\Ad3d\JobApplicationInterview\QcJobApplicationInterview;
use Illuminate\Database\Eloquent\Model;

class QcStatistical extends Model
{
    # lay tong so luong ho so tuyen dung chua duyet
    public function totalJobApplicationUnconfirmed()
    {
        $modelJobApplication = new QcJobApplication();
        return $modelJobApplication->totalUnconfirmed();
    }

    # ho so phong van chua duoc phong van
    public function totalJobApplicationInterviewUnconfirmed()
    {
        $modelJobApplicationInterview = new QcJobApplicationInterview();
        return $modelJobApplicationInterview->totalUnconfirmed();
    }

}
