<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 9:42 AM
 */
?>
<div style="position: fixed; right: 10px; bottom:0; width: 310px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="position: relative; ">
        <div style="position: absolute; top: -30px; right: 0; padding: 5px 10px; background-color: #6b8e23; border-radius: 5px 5px 0px 0px ;">
            <a class="qc-link-white-bold qc-font-size-16" onclick="qc_main.toggle('#qc_support_wrap');"
               style="color: white;">
                H? tr? &nbsp;
                <i class="qc-font-size-20 glyphicon glyphicon-th-list"></i>
            </a>
        </div>
        <div class="row">
            <div id="qc_support_wrap" class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                 style="padding: 5px; background-color: #6b8e23;border-radius: 5px 0 0 0 ;">
                <div style="width: 300px; height: 300px; background-color: white;">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                         style="height: 50px; background-color: whitesmoke; border-bottom: 1px solid #D7D7D7;">
                        <div class="pull-left text-left qc-padding-5 " style="width: 50px">
                            <img style="width:40px; height: 40px;border: 1px solid #D7D7D7; border-radius: 5px 5px;" src="{!! asset('public/imgtest/nvtv.jpg') !!}">
                        </div>
                        <div class="pull-left qc-padding-5">
                            <b>Công ty TNHH ba chi?u </b><br/>
                            <em>H? tr? tr?c tuy?n</em>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 qc-padding-5"
                         style="height: 30px; border-bottom: 1px solid whitesmoke;">
                        Công ty Ba Chi?u chào Anh\Ch?.
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 180px;overflow: auto;">
                        <div class="row">
                            <table class="table" style="margin-bottom: 10px;">
                                <tr>
                                    <td class="qc-padding-5" style="border-bottom: none; width: 40px">
                                        <i class="glyphicon glyphicon-user"
                                           style="font-size: 25px; border: 1px solid #D7D7D7; border-radius: 5px 5px;"></i>
                                    </td>
                                    <td class="qc-padding-5" style="border-bottom: none; width: 260px">
                                        N?i dung câu h?i - N?i dung câu h?i N?i dung câu h?i -N?i dung câu h?i
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="row">
                            <table class="table" style="margin-bottom: 10px;">
                                <tr>
                                    <td class="qc-padding-5 text-right" style="border-bottom: none; width: 260px">
                                        N?i dung tr? l?i - N?i dung tr? l?i N?i dung tr? l?i -N?i dung tr? l?i
                                    </td>
                                    <td class="qc-padding-5 text-right" style="border-bottom: none; width: 40px">
                                        <i class="glyphicon glyphicon-user"
                                           style="font-size: 25px; border: 1px solid #D7D7D7; border-radius: 5px 5px;"></i>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <form class="col-xs-12 col-sm-12 col-md-12 col-lg-12" method="post"
                          style="height: 40px; padding: 5px 0 0 0;background-color:#6b8e23; ">
                        <div class="input-group">
                            <input type="text" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">G?i</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
