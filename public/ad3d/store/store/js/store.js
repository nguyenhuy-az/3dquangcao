/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_store_store = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    }
}
//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var name = $('.textFilterName').val();
        if(name.length == 0) name = 'null';
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + name +'/' + $('.cbType').val());
    });
    //khi theo loai cong cu
    $('body').on('change', '.cbType', function () {
        var name = $('.textFilterName').val();
        if(name.length == 0) name = 'null';
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + name +'/' + $(this).val());
    });

    //khi tìm theo tên ..
    $('body').on('click', '.btFilterName', function () {
        var name = $('.textFilterName').val();
        if (name.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + name +'/' + $('.cbType').val());
        }
    });
});

