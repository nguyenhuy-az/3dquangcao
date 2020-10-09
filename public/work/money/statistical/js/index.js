/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_money_statistical = {
    filter: function (href) {
        qc_main.url_replace(href);
    }
}
//----------- chuyen tien - nhan tien chuyen ------------------------
$(document).ready(function () {
    //theo tháng
    $('.qc_work_money_statistical_wrap').on('change', '.qc_month_filter', function () {
        qc_work_money_statistical.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_year_filter').val());
    });

    // năm
    $('.qc_work_money_statistical_wrap').on('change', '.qc_year_filter', function () {
        qc_work_money_statistical.filter($(this).data('href') + '/' + $('.qc_month_filter').val() + '/' + $(this).val());
    });
});
// huy 1 thong tin chuyen tien
$(document).ready(function () {
    $('.qc_work_money_transfer_transfer_info').on('click', '.qc_transfer_detail_del', function () {
        //qc_work_money_transfer.info.delete($(this).data('href'));
    });
});