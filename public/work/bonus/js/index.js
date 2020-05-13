/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_bonus = {

}
$(document).ready(function () {
    $('body').on('change', '.qc_work_bonus_month', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_bonus_year').val());
    });
    $('body').on('change', '.qc_work_bonus_year', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.qc_work_bonus_month').val() + '/' + $(this).val());
    });
});

