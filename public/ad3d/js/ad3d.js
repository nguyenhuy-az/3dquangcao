/**
 * Created by HUY on 12/28/2017.
 */

var qc_ad3d = {
    bodyIdName: function () {
        return 'qc_ad3d';
    },
    bodyClassName: function () {
        return 'qc_ad3d';
    },
    mainWrapRemove: function () {
        // qc_main.remove('.qc_main_wrap');
    },
    mainWrapToggle: function () {
        //qc_main.toggle('.qc_main_wrap');
    },
    containActionClose: function () {
        qc_main.remove('#qc_ad3d_container_wrap');
    },
    // load status
    loadStatus: function () {
        qc_main.toggle('.qc_loading_status');
    },
    scrollTop: function () {

    }
};

var qc_ad3d_submit = {
    ajaxNotReload: function (href, containResponse, empty) {
        $.ajax({
            url: href,
            type: 'GET',
            cache: false,
            data: {},
            beforeSend: function () {
                qc_ad3d.loadStatus();
            },
            success: function (data) {
                qc_ad3d.containActionClose();
                if (data) {
                    if (containResponse.length > 0) {
                        if (empty) qc_main.empty(containResponse);
                        $(containResponse).append(data);
                    }
                }
            },
            complete: function () {
                qc_ad3d.loadStatus();
            }
        });
    },
    ajaxNotReloadHasRemove: function (href, containResponse, empty, removeObject) {
        $.ajax({
            url: href,
            type: 'GET',
            cache: false,
            data: {},
            beforeSend: function () {
                qc_ad3d.loadStatus();
            },
            success: function (data) {
                qc_ad3d.containActionClose();
                qc_main.remove(removeObject);
                if (data) {
                    if (containResponse.length > 0) {
                        if (empty) qc_main.empty(containResponse);
                        $(containResponse).append(data);
                    }
                }
            },
            complete: function () {
                qc_ad3d.loadStatus();
            }
        });
    },
    ajaxHasReload: function (href, containResponse, empty) {
        $.ajax({
            url: href,
            type: 'GET',
            cache: false,
            data: {},
            beforeSend: function () {
                qc_ad3d.loadStatus();
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
                qc_ad3d.loadStatus();
            }
        });
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
                qc_ad3d.loadStatus();
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
                qc_ad3d.containActionClose();
                qc_ad3d.loadStatus();
            }
        }).submit();
    },
    ajaxFormNotReloadHasContinue: function (form, containResponse, empty, successNotify) {
        $(form).ajaxForm({
            beforeSend: function () {
                qc_ad3d.loadStatus();
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
                qc_ad3d.loadStatus();
                $(form).find("input[type= 'reset']").click();
            }
        }).submit();
    },
    ajaxFormNotReloadHasRemove: function (form, containResponse, empty, removeObject) {
        $(form).ajaxForm({
            beforeSend: function () {
                qc_ad3d.loadStatus();
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
                qc_ad3d.containActionClose();
                qc_ad3d.loadStatus();
            }
        }).submit();
    },
    ajaxFormHasReload: function (form, containResponse, empty) {
        $(form).ajaxForm({
            beforeSend: function () {
                qc_ad3d.loadStatus();
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
                qc_ad3d.loadStatus();
            }
        }).submit();
    },
    normalForm: function (form) {
        $(form).submit();
    }
}

$(document).ready(function () {
    $('body').on('click', '.qc_ad3d_container_close', function () {
        qc_ad3d.containActionClose();
    })
});
