/**
 * Created by HUY on 4/8/2017.
 */
var qc_master = {
    bodyIdName: function () {
        return 'qc_master';
    },
    bodyClassName: function () {
        return 'qc_master';
    },
    mainWrapRemove: function () {
        // qc_main.remove('.qc_main_wrap');
    },
    mainWrapToggle: function () {
        //qc_main.toggle('.qc_main_wrap');
    },
    containActionClose: function () {
        qc_main.remove('#qc_container_wrap');
    },
    // load status
    loadStatus: function () {
        qc_main.toggle('.qc_loading_status');
    },
    scrollTop: function () {

    }
};

var qc_master_submit = {
    ajaxNotReload: function (href, containResponse, empty) {
        $.ajax({
            url: href,
            type: 'GET',
            cache: false,
            data: {},
            beforeSend: function () {
                qc_master.loadStatus();
            },
            success: function (data) {
                qc_master.containActionClose();
                if (data) {
                    if (containResponse.length > 0) {
                        if (empty) qc_main.empty(containResponse);
                        $(containResponse).append(data);
                    }
                }
            },
            complete: function () {
                qc_master.loadStatus();
                qc_main.scrollTop();
            }
        });
        return false;
    },
    ajaxNotReloadNoScrollTop: function (href, containResponse, empty) {
        $.ajax({
            url: href,
            type: 'GET',
            cache: false,
            data: {},
            beforeSend: function () {
                qc_master.loadStatus();
            },
            success: function (data) {
                qc_master.containActionClose();
                if (data) {
                    if (containResponse.length > 0) {
                        if (empty) qc_main.empty(containResponse);
                        $(containResponse).append(data);
                    }
                }
            },
            complete: function () {
                qc_master.loadStatus();
            }
        });
        return false;
    },
    ajaxNotReloadHasRemove: function (href, containResponse, empty, removeObject) {
        $.ajax({
            url: href,
            type: 'GET',
            cache: false,
            data: {},
            beforeSend: function () {
                qc_master.loadStatus();
            },
            success: function (data) {
                qc_master.containActionClose();
                qc_main.remove(removeObject);
                if (data) {
                    if (containResponse.length > 0) {
                        if (empty) qc_main.empty(containResponse);
                        $(containResponse).append(data);
                    }
                }
            },
            complete: function () {
                qc_master.loadStatus();
            }
        });
        return false;
    },
    ajaxHasReload: function (href, containResponse, empty) {
        $.ajax({
            url: href,
            type: 'GET',
            cache: false,
            data: {},
            beforeSend: function () {
                qc_master.loadStatus();
            },
            success: function (data) {
                if (data) {
                    if (containResponse.length > 0) {
                        if (empty) qc_main.empty(containResponse);
                        $(containResponse).append(data);
                    } else {
                        qc_main.window_reload();
                    }
                } else {
                    qc_main.window_reload();
                }
            },
            complete: function () {
                qc_master.loadStatus();
            }
        });
        return false;
    },
    ajaxFormNotReload: function (form, containResponse, empty) {
        //var data = $(form).serialize();
        $(form).ajaxForm({
            /*
             type: 'POST',
             cache: false,
             data: data,
             */
            beforeSend: function () {
                qc_master.loadStatus();
            },
            success: function (data) {
                if (data) {
                    if (containResponse.length > 0) {
                        if (empty) qc_main.empty(containResponse);
                        $(containResponse).append(data);
                    }
                }
            },
            complete: function () {
                qc_master.containActionClose();
                qc_master.loadStatus();
            }
        }).submit();
        return false;
    },
    ajaxFormNotReloadHasContinue: function (form, containResponse, empty, successNotify) {
        $(form).ajaxForm({
            beforeSend: function () {
                qc_master.loadStatus();
            },
            success: function (data) {
                if (containResponse.length > 0) {
                    if (empty) qc_main.empty(containResponse);
                    if (data) {
                        $(containResponse).append(data)
                    } else {
                        $(containResponse).append(successNotify)
                    }
                }
            },
            complete: function () {
                qc_master.loadStatus();
                $(form).find("input[type= 'reset']").click();
            }
        }).submit();
        return false;
    },
    ajaxFormNotReloadHasRemove: function (form, containResponse, empty, removeObject) {
        $(form).ajaxForm({
            beforeSend: function () {
                qc_master.loadStatus();
            },
            success: function (data) {
                qc_main.remove(removeObject);
                if (data) {
                    if (containResponse.length > 0) {
                        if (empty) qc_main.empty(containResponse);
                        $(containResponse).append(data);
                    }
                } else {
                    qc_main.page_back();
                }
            },
            complete: function () {
                qc_master.containActionClose();
                qc_master.loadStatus();
            }
        }).submit();
        return false;
    },
    ajaxFormHasReload: function (form, containResponse, empty) {
        $(form).ajaxForm({
            beforeSend: function () {
                qc_master.loadStatus();
            },
            success: function (data) {
                if (data) {
                    if (containResponse.length > 0) {
                        if (empty) qc_main.empty(containResponse);
                        $(containResponse).append(data);
                    } else {
                        qc_main.window_reload();
                    }
                } else {
                    qc_main.window_reload();
                }
            },
            complete: function () {
                qc_master.loadStatus();
            }
        }).submit();
        return false;
    },
    normalForm: function (form) {
        $(form).submit();
        return false;
    }
}
$(document).ready(function () {
    $('body').on('click', '.qc_container_close', function () {
        qc_master.containActionClose();
    });
    $('#qc_master').on('touchend', 'a', function () {
        $(this).click();
    });
});