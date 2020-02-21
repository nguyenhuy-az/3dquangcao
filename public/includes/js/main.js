//==========  ==========  ==========  GENERAL ========== ========== ==========
var qc_main = {
    setCookie: function (cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    },

    getCookie: function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
        }
        return "";
    },

    deleteCookie: function (cname) {
        qc_main.setCookie(cname, null, -100);
    },

    confirmDelete: function (smg) {
        if (window.confirm(smg)) {
            return true;
        }
        return false;
    },
    empty: function (object) {
        if ($(object).length > 0) $(object).empty();
    },
    remove: function (object) {
        if ($(object).length > 0) $(object).remove();
    },
    show: function (object) {
        if ($(object).length > 0) $(object).show();
    },
    hide: function (object) {
        if ($(object).length > 0) $(object).hide();
    },
    toggle: function (object) {
        if ($(object).length > 0) $(object).toggle();
    },
    window: {
        reload: function () {
            window.location.reload();
        },
        replace: function (newurl) {
            window.location.replace(newUrl);
        }
    },
    check: {
        stringValid: function (str, strHandle) {
            if (str.length > 0) {
                for (var $i = 0; $i <= strHandle.length; $i++) {
                    if (str.indexOf(strHandle.charAt($i)) > 0)
                        return false;
                }
            }
            return true;
        },

        //---------- ---------- Email ---------- ----------
        emailRegex: function (email) {
            if (email == "") return false;
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) result = true;     //cach 1 ko cho cham(dau email)
            // var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;    ////cach 2 cho cham(dau email)
            if (email.indexOf(" ") > 0) return false;
            //if(regex.test($Email)) $res = true;
            return true;
        },
        emailJavascript: function (email) {
            if (email == "") return false;
            if (email.indexOf(" ") > 0) return false;
            if (email.indexOf("@") == -1) return false;
            var i = 1;
            var sLength = email.length;
            if (email.indexOf(".") == -1) return false;
            if (email.indexOf("..") != -1) return false;
            if (email.indexOf("@") != email.lastIndexOf("@")) return false;
            if (email.lastIndexOf(".") == email.length - 1) return false;
            var str = "abcdefghikjlmnopqrstuvwxyz-@._0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            for (var j = 0; j < email.length; j++) {
                if (str.indexOf(email.charAt(j)) == -1)return false;
            }
            return true;
        },

        //---------- ---------- check input text ---------- ----------
        inputNull: function (object, smg) {
            var v = $(object).val();
            if (v == '') {
                if (smg.length > 0) alert(smg);
                $(object).focus();
                return true;
            } else {
                return false;
            }
        },
        inputMaxLength: function (object, limit, smg) {
            var v = $(object).val();
            if (v.length > limit) {
                if (smg.length > 0) alert(smg);
                $(object).focus();
                return true;
            } else {
                return false;
            }
        },
        inputMinLength: function (object, limit, smg) {
            var v = $(object).val();
            if (v.length < limit) {
                if (smg.length > 0) alert(smg);
                $(object).focus();
                return true;
            } else {
                return false;
            }
        },

        //---------- ---------- upload file ---------- ----------
        file: function (fileUp, typeCheck) {
            var extension = fileUp.split('.').pop().toLowerCase();
            if ($.inArray(extension, typeCheck.split(',')) == -1) {
                return false;
            }
            else {
                return true;
            }
        },
        fileValid: function (fileUp, typeCheck, smg) {
            if (!qc_main.check.file(fileUp, typeCheck)) {
                if (smg.length) alert(smg);
                return false;
            } else {
                return true
            }
        },

    },
    select: {
        //view image upload
        image: function (file, wrapView, idViewImage, typeSelect) {
            //file: id cua file upload
            //wrapView : id contain view
            // idViewImage: image view
            //typeSelect : type of select file
            if (typeSelect == '') typeSelect = 'gif,jpg,jpge,png'; //default
            var photo = $(file).val();
            if (!qc_main.check.file(photo, typeSelect)) {
                alert('Invalid ! File type: ' + typeSelect);
                $(file).val('');
                $(file).focus();
                return false;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.getElementById(idViewImage);
                img.src = e.target.result;
                img.style.display = 'inline';
            };
            reader.readAsDataURL(file.files[0]);
            $(wrapView).show();
        },
        // view image upload follow size
        imageFollowSize: function (file, wrapView, idViewImage, typeSelect, idImageCheckSize) {
            //file: id cua file upload
            //wrapView : id contain view
            // idViewImage: image view
            //typeSelect : type of select file
            if (typeSelect == '') typeSelect = 'gif,jpg,jpge,png';
            var photo = $(file).val();
            if (!qc_main.check.file(photo, typeSelect)) {
                alert('Invalid ! File type: ' + typeSelect);
                $(file).val('');
                $(file).focus();
                return false;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.getElementById(idViewImage);
                img.src = e.target.result;
                img.style.display = 'inline';

                var checkImg = document.getElementById(idImageCheckSize);
                checkImg.src = e.target.result;
            };
            reader.readAsDataURL(file.files[0]);
            $(wrapView).show();
        },
        imageCancel: function (idFile, wrapView) {
            $(idFile).val('');
            $(wrapView).hide();
        }
    },

    //----------- ----------- ----------- DATE ----------- ----------- -----------
    // set datePicker for input
    setDatepicker: function (object) {
        $(object).datepicker({
            dateFormat: "yy-mm-dd",
            dayNames: "Sunday Monday Tuesday Wednessday Thursday Friday Satuday".split(" "),
            dayNamesMin: "Sun Mon Tue Wed Thur Fri Sa".split(" "),
            dayNamesShort: "Su Mo Tu We Th Fr Sa".split(" "),
            monthNames: "January Febuary March April May Jun July Agust Septembe Octobe November December".split(" "),
            monthNamesShort: "Jan Feb Mar Apr May Jun July Agu Sep Oct Nov Dec".split(" "),
            prevText: "Ant",
            nextText: "Sig",
            currentText: "Hoy",
            changeMonth: !0,
            changeYear: !0,
            showAnim: "slideDown",
            yearRange: "1950:2100",
            beforeShow: function () {
                setTimeout(function () {
                    $('.ui-datepicker').css('z-index', 999999999999);
                }, 0);
            }
        });
    },

    getCurrentDate: function (style, str) {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        if (style == 'd-m-y') {
            return dd + str + mm + str + yyyy;
        } else {
            return yyyy + str + mm + str + dd;
        }
    },

    existSpecialCharacter: function (str) {
        if (/^[a-zA-Z0-9- ]*$/.test(str) == false) {
            return true;
        } else {
            return false;
        }
    },
    //----------- ----------- ----------- check string\ email ----------- ----------- -----------
    // fix when first character is $
    checkStringValid: function (str, strHandle) {
        if (str.length > 0) {
            for (var $i = 0; $i <= strHandle.length; $i++) {
                if (str.indexOf(strHandle.charAt($i)) > 0) {
                    return true;
                }

            }
        }
        return false;
    },

    //----------- ----------- ----------- check input ----------- ----------- ----------
    // radio
    getRadioValue: function (formName, radioName) {
        var val;
        var radios = $("form[name='" + formName + "']").find("input[name= '" + radioName + "']");
        for (var i = 0, len = radios.length; i < len; i++) {
            if (radios[i].checked) {
                val = radios[i].value;
                break;
            }
        }
        return val;
    },
    checkRadioNull: function (formName, radioName) {
        var result = true;
        var radios = $("form[name='" + formName + "']").find("input[name= '" + radioName + "']");
        for (var i = 0, len = radios.length; i < len; i++) {
            if (radios[i].checked) {
                result = false;
            }
        }
        return result;
    },

    // check box
    checkCheckboxChecked: function (formName, checkboxName) {
        var checkbox = $("form[name='" + formName + "']").find("input[name= '" + checkboxName + "']");
        if (checkbox.is(':checked')) {
            return true;
        } else {
            checkbox.focus();
            return false;
        }
    },

    //========= ========= submit ajax ========== ========= =========
    // submit ajax
    getSelectAppend: function (url_s, token, id, object_contain) {
        token = $(token).val();
        $.ajax({
            url: url_s + '/' + id,
            type: 'GET',
            cache: false,
            data: {"_token": token, 'id': id},
            success: function (data) {
                if ($(object_contain.length > 0)) {
                    $(object_contain).append(data);
                }
            }
        });
    },

    //----------- ----------- ----------- general ----------- ----------- -----------
    textareaAutoHeight: function (object, row) {
        var t = $(object).val();
        var line = parseInt((t == '') ? 1 : (t.split("\n").length));
        $(object).attr('rows', line + row);
    },
    closeWindow: function (smg) {
        if (smg == '') {
            window.open('', '_self');
            window.close();
        } else {
            if (confirm(smg)) window.close();
        }

    },
    click: function (object) {
        $(object).click();
    },
    //replay url
    url_replace: function (href) {
        window.location.replace(href);
    },
    //window reload
    window_reload: function () {
        window.location.reload();
    },

    scrollTop: function () {
        $('body,html').animate({
            scrollTop: 0
        })
    },

    page_new: function (href) {
        window.location.href = href;
    },
    page_back: function () {
        window.history.back(-1);
    },

    page_back_go: function (pageBack) {
        window.history.back(-pageBack);
    },
    getNumberInput: function (string) {
        var stringNumber = string.match(/\d/g);
        if (stringNumber != null) {
            stringNumber = stringNumber.join("");
        }
        return stringNumber;
    },
    showNumberInput: function (object) {
        var number = $(object).val();
        $(object).val(qc_main.getNumberInput(number));
    },
    showFormatCurrency: function (object) {
        var number = $(object).val();
        var stringNumber = qc_main.getNumberInput(number);
        if (stringNumber != null) {
            var abc = qc_main.formatCurrency(stringNumber);
        } else {
            var abc = '';
        }
        $(object).val(abc);
    },
    formatCurrency: function (number) {
        var n = number.split('').reverse().join("");
        var n2 = n.replace(/\d\d\d(?!$)/g, "$&,");
        return n2.split('').reverse().join('');
    },
    rotateImage: function ($object) {
        if (!$($object).hasClass('click_1')) {//click lan 1
            $($object).addClass('click_1');
            $($object).css('transform', 'rotate(90deg)');
        } else {
            if (!$($object).hasClass('click_2')) {//click lan 2
                $($object).addClass('click_2');
                $($object).css('transform', 'rotate(180deg)');
            } else {
                $($object).css('transform', 'rotate(360deg)');
                $($object).removeClass('click_1');
                $($object).removeClass('click_2');
            }
        }

    }
};