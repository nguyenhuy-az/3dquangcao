/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_work_time_keeping = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    viewImage: function (href) {
        qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    confirm: {
        get: function (listObject) {
            var href = $(listObject).parents('.qc_ad3d_list_content').data('href-confirm') + '/' + $(listObject).data('object');
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (formObject) {
            var cbDayEnd = $(formObject).find("select[name='cbDayEnd']");
            var cbMonthEnd = $(formObject).find("select[name='cbMonthEnd']");
            var cbYearEnd = $(formObject).find("select[name='cbYearEnd']");
            var cbHoursEnd = $(formObject).find("select[name='cbHoursEnd']");
            var notifyContent = $(formObject).find('.notifyConfirm');
            if (qc_main.check.inputNull(cbDayEnd, 'Chọn ngày làm việc')) {
                $(cbDayEnd).focus();
                return false;
            }
            if (qc_main.check.inputNull(cbMonthEnd, 'Chọn tháng')) {
                cbMonthEnd.focus();
                return false;
            }
            if (qc_main.check.inputNull(cbYearEnd, 'Chọn năm')) {
                cbYearEnd.focus();
                return false;
            }
            if (qc_main.check.inputNull(cbHoursEnd, 'Chọn giờ ra')) {
                cbYearEnd.focus();
                return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, notifyContent, true);
            qc_main.scrollTop();
        }
    },
    off: {
        save: function (formObject) {
            var cbCompany = $(formObject).find("select[name='cbCompany']");
            var cbWork = $(formObject).find("select[name='cbWork']");
            var cbDayOff = $(formObject).find("select[name='cbDayOff']");
            var cbMonthOff = $(formObject).find("select[name='cbMonthOff']");
            var cbYearOff = $(formObject).find("select[name='cbYearOff']");
            if (qc_main.check.inputNull(cbWork, 'Chọn nhân viên')) {
                return false;
            }
            if (qc_main.check.inputNull(cbDayOff, 'Chọn ngày nghĩ')) {
                $(cbDayOff).focus();
                return false;
            }

            qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
            qc_main.scrollTop();
        }
    },
    add: {
        save: function (formObject) {
            var cbCompany = $(formObject).find("select[name='cbCompany']");
            var cbWork = $(formObject).find("select[name='cbWork']");
            var cbDayBegin = $(formObject).find("select[name='cbDayBegin']");
            var cbMonthBegin = $(formObject).find("select[name='cbMonthBegin']");
            var cbYearBegin = $(formObject).find("select[name='cbYearBegin']");
            var cbHoursBegin = $(formObject).find("select[name='cbHoursBegin']");
            var cbMinuteBegin = $(formObject).find("select[name='cbMinuteBegin']");
            /*
             var cbDayEnd = $(formObject).find("select[name='cbDayEnd']");
             var cbMonthEnd = $(formObject).find("select[name='cbMonthEnd']");
             var cbYearEnd = $(formObject).find("select[name='cbYearEnd']");
             var cbHoursEnd = $(formObject).find("select[name='cbHoursEnd']");
             var cbMinuteEnd = $(formObject).find("select[name='cbMinuteEnd']");
             */
            if (qc_main.check.inputNull(cbWork, 'Chọn nhân viên')) {
                return false;
            }
            if (qc_main.check.inputNull(cbDayBegin, 'Chọn ngày làm việc')) {
                $(cbDayBegin).focus();
                return false;
            }
            if (qc_main.check.inputNull(cbHoursBegin, 'Chọn giờ vào')) {
                cbHoursBegin.focus();
                return false;
            }
            /* add extend*/
            /*
             if ($(cbDayEnd).val() != '' || $(cbMonthEnd).val() != '' || $(cbYearEnd).val() != '' || $(cbHoursEnd).val() != '') {
             var invalidDate = true;
             if (qc_main.check.inputNull(cbDayEnd, 'Chọn ngày kết thúc')) {
             $(cbDayEnd).focus();
             invalidDate = false;
             return false;
             }
             if (qc_main.check.inputNull(cbMonthEnd, 'Chọn tháng kết thúc')) {
             $(cbMonthEnd).focus();
             invalidDate = false;
             return false;
             }
             if (qc_main.check.inputNull(cbYearEnd, 'Chọn năm kết thúc')) {
             $(cbYearEnd).focus();
             invalidDate = false;
             return false;
             }
             if (qc_main.check.inputNull(cbHoursEnd, 'Chọn giờ kết thúc')) {
             $(cbHoursEnd).focus();
             invalidDate = false;
             return false;
             } else {
             invalidDate = true;
             }

             if (invalidDate) {
             var dateBegin = new Date($(cbYearBegin).val(), $(cbMonthBegin).val(), $(cbDayBegin).val(), $(cbHoursBegin).val(), $(cbMinuteBegin).val() );
             var dateEnd = new Date($(cbYearEnd).val(), $(cbMonthEnd).val(), $(cbDayEnd).val(), $(cbHoursEnd).val(), $(cbMinuteEnd).val());
             var dateBegin_1 = Date.parse($(cbYearBegin).val() + '-' + $(cbMonthBegin).val() + '-' + $(cbDayBegin).val() + " " + $(cbHoursBegin).val() + ":" + $(cbMinuteBegin).val());
             var dateEnd_1   = Date.parse($(cbYearEnd).val() + '-' + $(cbMonthEnd).val() + '-' + $(cbDayEnd).val() + " " + $(cbHoursEnd).val() + ":" + $(cbMinuteEnd).val(), "Y-m-d g:i");
             alert('---' + dateBegin_1);
             if (dateBegin_1 < dateEnd_1) {
             alert('hợp lệ');
             } else {
             alert('Không hợp lệ');
             }
             return false;
             }


             }
             */
            //qc_ad3d_submit.normalForm(formObject);
            qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
            qc_main.scrollTop();
        }
    },
    edit: {
        get: function (listObject) {
            var href = $(listObject).parents('.qc_ad3d_list_content').data('href-edit');
            var contain = qc_ad3d.bodyIdName();
            qc_ad3d_submit.ajaxNotReload(href, $('#' + contain), false);
        },
        post: function () {

        }
    },
    delete: function (listObject) {
        if (confirm('Bạn muốn xóa đơn hàng này')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}
//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter;
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
 $(document).ready(function () {
     $('.qc_ad3d_list_object').on('click', '.qc_ad3d_timekeeping_image_view', function () {
        qc_ad3d_work_time_keeping.viewImage($(this).data('href'));
     })
 });

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_work_time_keeping.delete($(this).parents('.qc_ad3d_list_object'));
    })
});


//-------------------- add ------------
$(document).ready(function () {
    //select company
    $('.qc_ad3d_index_content').on('change', '.frmAdd .cbCompany', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    })

    //select workId
    $('.qc_ad3d_index_content').on('change', '.frmAdd .cbWork', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompany').val() + '/' + $(this).val());
    })

    //save
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_work_time_keeping.add.save($(this).parents('.frmAdd'));
    })
});

//-------------------- confirm ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm', function () {
        qc_ad3d_work_time_keeping.confirm.get($(this).parents('.qc_ad3d_list_object'));
    })
    $('body').on('click', '.frmConfirm .qc_save', function () {
        qc_ad3d_work_time_keeping.confirm.save($(this).parents('.frmConfirm'));
    })
});

//-------------------- off ------------
$(document).ready(function () {
    //select company
    $('.qc_ad3d_index_content').on('change', '.frmAddOff .cbCompany', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    })

    //select workId
    $('.qc_ad3d_index_content').on('change', '.frmAddOff .cbWork', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompany').val() + '/' + $(this).val());
    })

    //save
    $('.qc_ad3d_index_content').on('click', '.frmAddOff .qc_save', function () {
        qc_ad3d_work_time_keeping.off.save($(this).parents('.frmAddOff'));
    })
});
