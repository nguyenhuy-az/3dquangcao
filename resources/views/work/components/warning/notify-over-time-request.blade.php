<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$n_o = 1;
$amount = $hFunction->getCount($dataOverTimeRequest);
?>
<div class="row">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
         style="padding-top: 3px; padding-bottom: 3px; background-color: red;">
        <i class="glyphicon glyphicon-warning-sign" style="color: white; font-size: 16px;"></i>
        <span style="color: yellow;">YÊU CẦU TĂNG CA NGÀY: </span>
        @foreach($dataOverTimeRequest as $overTimeRequest)
            <?php $n_o = $n_o + 1; ?>
            <span style="color: white; font-size: 1.5em;">{!! date('d/m/Y', strtotime($overTimeRequest->requestDate())) !!}</span>
            @if($amount > 1 && $n_o <= $amount)
                <i class="glyphicon glyphicon-minus" style="color: yellow;"></i>
            @endif
        @endforeach
       {{-- <br/>
        <span style="color: yellow;">Thời gian Ăn uống: </span>
        <span style="color: white; font-size: 1.5em;">30 Phút</span>
        <span style="color: yellow;"> Vẫn tính công tăng ca</span>--}}
    </div>
</div>