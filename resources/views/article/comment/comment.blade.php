<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 11/2/2016
 * Time: 1:28 AM
 */
?>
<div class="row">

    <div class="xv-padding-top-10 xv-padding-bot-10 col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
        <form method="post" action="">
            <textarea class="form-control " style="max-width: 100%;" placeholder="nhap binh luan...." rows="2"></textarea><br/>
            <button type="button" class="pull-right btn btn-primary btn-sm ">Gui</button>
        </form>
    </div>
    <div class="xv-padding-bot-10 col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
        <div class="col-md-12" style="background-color: whitesmoke;">
            @for($i=0; $i<=4;$i++)
                @include('article.comment.comment-object')
            @endfor

            <div class="xv-padding-10 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                <a href="#">Xem them binh luan</a>
            </div>
        </div>
    </div>
</div>
