/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_store_tool_allocation = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    add: {
        checkAmount: function () {

        },
        save: function (formObject) {
            var staff = $(formObject).find("select[name='cbReceiveStaff']");
            if (qc_main.check.inputNull(staff, 'Chọn nhân viên')) {
                staff.focus();
                return false;
            } else {
                qc_ad3d_submit.normalForm(formObject);
            }

            //qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
            //qc_main.scrollTop();
        }
    },
}
//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter + '/' + $('.textFilterName').val();
        }
        qc_main.url_replace(href);
    })

    //khi tìm theo tên ..
    $('body').on('click', '.btFilterName', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val()
        if (name.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
        }
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbMonthFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbYearFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
});


//view
/*
 $(document).ready(function () {
 $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
 qc_ad3d_store_tool_allocation.view($(this).parents('.qc_ad3d_list_object'));
 })
 });
 */

//-------------------- bàn giao de nghe ------------
$(document).ready(function () {
    //chon cong ty
    $('#frm_work_tool_allocation_add ').on('change', '.cbCompanyAllocation', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    });

    //chọn công cụ
    $('#frm_work_tool_allocation_add ').on('click', '.qc_store_select .qc_store', function () {
        var qc_store_select = $(this).parents('.qc_store_select');
        if ($(this).is(":checked")) {
            qc_store_select.find('.qc_txtAllocationAmount').prop('disabled', false);
            qc_store_select.find('.cbNewStatus').prop('disabled', false);
        } else {
            qc_store_select.find('.qc_txtAllocationAmount').prop('disabled', true);
            qc_store_select.find('.cbNewStatus').prop('disabled', true);
        }
        ;
    });

    //kiểm tra số lượng nhập
    $('#frm_work_tool_allocation_add ').on('change', '.qc_txtAllocationAmount', function () {
        if ($(this).data('inventor') < $(this).val()) {
            alert('Số lượng cấp phải nhỏ hơn tồn kho');
            $(this).val(1);
            $(this).focus();
            return false;
        }
        if (1 > $(this).val()) {
            alert('Số lượng cấp phải nhỏ hơn 1');
            $(this).val(1);
            $(this).focus();
            return false;
        }
    })
    $('#frm_work_tool_allocation_add').on('click', '.qc_ad3d_tool_allocation_save', function () {
        qc_ad3d_store_tool_allocation.add.save($(this).parents('#frm_work_tool_allocation_add'));
    })
});
