/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_orders = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    payment: {
        save: function (frm) {
            var txtMoney = $(frm).find("input[name='txtMoney']");
            var txtName = $(frm).find("input[name='txtName']");
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền thanh toán')) {
                txtMoney.focus();
                return false;
            } else {
                //alert(txtMoney);
                var money = qc_main.getNumberInput(txtMoney.val());
                var checkMoney = txtMoney.data('money');
                if (money > checkMoney) {
                    alert('Nhập số tiền không đúng');
                    txtMoney.focus();
                    return false;
                }
            }
            if (qc_main.check.inputNull(txtName, 'Nhập tên người thanh toán')) {
                txtName.focus();
                return false;
            } else {
                qc_master_submit.normalForm('.qc_frm_work_orders_payment');
            }
            //$('#qc_frm_work_orders_payment').submit();
            //qc_master_submit.normalForm('#qc_frm_work_orders_payment');
        },
    },
    report: {
        getFinish: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        saveFinish: function (frm) {
            var containerNotify = $(frm).find(".notifyConfirm");
            if (confirm('Tôi đồng ý báo cáo kết thúc công trình này')) {
                qc_master_submit.ajaxFormHasReload(frm, containerNotify, true);
            }

        }
    },
    add: {
        addProduct: function (href) {
            //qc_main.url_replace(href);
            qc_master_submit.ajaxNotReloadNoScrollTop(href, '#qc_work_orders_add_product_wrap', false);
            //qc_master_submit.ajaxHasReload(href, '#qc_work_orders_add_product_wrap', false);

            /*$.ajax({
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
             $('#qc_work_orders_add_product_wrap').append(data);
             }
             qc_main.window_reload();
             },
             complete: function () {
             qc_master.loadStatus();
             }
             });*/
            /*$.ajax({
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
             //qc_main.deleteCookie('rowsProductAdd');
             var rowsProduct = [];
             if ( qc_main.getCookie('rowsProductAdd').length > 0 ) {
             rowsProduct = JSON.parse(qc_main.getCookie('rowsProductAdd'));
             //rowsProduct.push(data);
             //qc_main.setCookie('rowsProductAdd',JSON.stringify(rowsProduct),3);
             console.log(rowsProduct);
             }
             else {
             rowsProduct.push(data);
             qc_main.setCookie('rowsProductAdd',JSON.stringify(rowsProduct),3);
             console.log(rowsProduct);
             }
             $('#qc_work_orders_add_product_wrap').append(data);
             }
             },
             complete: function () {
             qc_master.loadStatus();
             }
             });*/
        },
        save: function (frm) {
            // thong tin khach hang
            var txtCustomerName = $(frm).find("input[name='txtCustomerName']");
            var txtPhone = $(frm).find("input[name='txtPhone']");
            var txtZalo = $(frm).find("input[name='txtZalo']");
            var txtAddress = $(frm).find("input[name='txtAddress']");

            // kiem tra thong tin khach hang
            if (qc_main.check.inputNull(txtCustomerName, 'Nhập tên khách hàng')) {
                txtCustomerName.focus();
                return false;
            } else {
                if ($(txtCustomerName).val().length > 30) {
                    alert('Tên không quá 30 ký tự');
                    txtCustomerName.focus();
                    return false;
                }
            }
            if (txtPhone.val() == '' && txtZalo.val() == '' && txtAddress.val() == '') {
                alert('Phải nhập thông tin liên lạc của khách hàng: Điện thoại / Zalo / Địa chỉ');
                txtPhone.focus();
                return false;
            }
            // thông tin sản phẩm
            if ($('#frmWorkOrdersAdd .qc_work_orders_product_add').length > 0) {
                qc_work_orders.add.checkProductInput();
            } else {
                alert('Nhập sản phẩm');
                return false;
            }
        },
        checkProductInput: function () {
            $('#frmWorkOrdersAdd .qc_work_orders_product_add').filter(function () {
                if ($(this).is(':last-child')) {
                    var txtProductType = $(this).find('.txtProductType');
                    var txtWidth = $(this).find('.txtWidth');
                    var txtHeight = $(this).find('.txtHeight');
                    //var depth = $(this).find('.cbProductType');
                    var txtUnit = $(this).find('.txtUnit');
                    var txtAmount = $(this).find('.txtAmount');
                    var txtPrice = $(this).find('.txtPrice');
                    if (txtProductType.val() != '' || txtWidth.val() != '' || txtHeight.val() != '' || txtAmount.val() != '') {
                        if (qc_main.check.inputNull(txtProductType, 'Nhập loại sản phẩm')) {
                            txtProductType.focus();
                            return false;
                        } else {
                            if (txtProductType.val().length > 50) {
                                alert('Tên loại sản phẩm không dài quá 50 ký tự');
                                txtProductType.focus();
                                return false;
                            }
                        }
                        if (txtWidth.val() == 0 || txtWidth.val() == '') {
                            alert('Nhập chiều rộng');
                            txtWidth.focus();
                            return false;
                        }

                        if (txtHeight.val() == 0 || txtHeight.val() == '') {
                            alert('Nhập chiều cao');
                            txtHeight.focus();
                            return false;
                        }
                        if (txtUnit.val() == 0 || txtUnit.val() == '') {
                            alert('Nhập đơn vị tính');
                            txtUnit.focus();
                            return false;
                        }
                        if (qc_main.check.inputNull(txtAmount, 'Nhập số lượng sản phẩm')) {
                            txtAmount.focus();
                            return false;
                        }
                        if (qc_main.check.inputNull(txtPrice, 'Nhập giá sản phẩm')) {
                            txtPrice.focus();
                            return false;
                        } else {
                            var productPrice = parseInt(qc_main.getNumberInput(txtPrice.val()));
                            if (productPrice < 0 || productPrice > 1000000000) {
                                alert('Giá sản phảm không đúng');
                                txtPrice.focus();
                                return false;
                            } else {
                                qc_work_orders.add.checkOrder(true);
                            }

                        }
                    } else {
                        alert('Nhập thông tin sản phẩm');
                        return false;
                    }
                } else {
                    var txtProductType = $(this).find('.txtProductType');
                    var txtWidth = $(this).find('.txtWidth');
                    var txtHeight = $(this).find('.txtHeight');
                    //var depth = $(this).find('.cbProductType');
                    var txtAmount = $(this).find('.txtAmount');
                    var txtPrice = $(this).find('.txtPrice');
                    if (txtProductType.val() != '' || txtWidth.val() != '' || txtHeight.val() != '' || txtAmount.val() != '') {
                        if (qc_main.check.inputNull(txtProductType, 'Nhập loại sản phẩm')) {
                            txtProductType.focus();
                            return false;
                        }
                        if (txtWidth.val() == 0 || txtWidth.val() == '') {
                            alert('Nhập chiều rộng');
                            txtWidth.focus();
                            return false;
                        }

                        if (txtHeight.val() == 0 || txtHeight.val() == '') {
                            alert('Nhập chiều cao');
                            txtHeight.focus();
                            return false;
                        }
                        if (qc_main.check.inputNull(txtAmount, 'Nhập số lượng sản phẩm')) {
                            txtAmount.focus();
                            return false;
                        }
                        if (qc_main.check.inputNull(txtPrice, 'Nhập giá sản phẩm')) {
                            txtPrice.focus();
                            return false;
                        }
                    } else {
                        alert('Nhập thông tin sản phẩm');
                        return false;
                    }
                }

            });
        },
        checkOrder: function (productStatus) {
            if (productStatus) {
                var orderType = $('#frmWorkOrdersAdd').find("input[name='orderType']");
                var txtOrderName = $('#frmWorkOrdersAdd').find("input[name='txtOrderName']");
                var txtDateReceive = $('#frmWorkOrdersAdd').find("input[name='txtDateReceive']");
                var cbDayDelivery = $('#frmWorkOrdersAdd').find("select[name='cbDayDelivery']");
                var txtTotalPricePay = $('#frmWorkOrdersAdd').find("input[name='txtTotalPricePay']");
                var txtBeforePay = $('#frmWorkOrdersAdd').find("input[name='txtBeforePay']");
                var beforePay = parseInt(qc_main.getNumberInput(txtBeforePay.val()));
                var totalPricePay = parseInt(qc_main.getNumberInput(txtTotalPricePay.val()));
                // thông tin đơn hàng
                if (qc_main.check.inputNull(txtOrderName, 'Nhập tên đơn hàng')) {
                    txtOrderName.focus();
                    return false;
                }
                //alert(orderType);
                if (orderType.val() == 1) {// don hang thuc
                    if (beforePay > totalPricePay) {
                        alert('Tiền cọc không lớn hơn số tiền cần thanh toán');
                        txtBeforePay.focus();
                        return false;
                    }

                    if (qc_main.check.inputNull(txtDateReceive, 'Nhập ngày nhận')) {
                        txtDateReceive.focus();
                        return false;
                    }
                    if (qc_main.check.inputNull(cbDayDelivery, 'Chọn ngày giao')) {
                        cbDayDelivery.focus();
                        return false;
                    } else {
                        /* var cbDay = cbDayDelivery.val();
                         var cbMonth = $('#frmWorkOrdersAdd').find("select[name='cbMonthDelivery']").val();
                         var cbYear = $('#frmWorkOrdersAdd').find("select[name='cbYearDelivery']").val();
                         var cbHours = $('#frmWorkOrdersAdd').find("select[name='cbHoursDelivery']").val();
                         var cbMinute = $('#frmWorkOrdersAdd').find("select[name='cbMinuteDelivery']").val();
                         var strDate = cbYear +'-' + cbMonth +'-' + cbDay +'-' + cbHours +'-' + cbMinute;
                         var dateDelivery = new Date(strDate);
                         alert(txtDateReceive.val() + '-----' + dateDelivery.toDateString());*/
                        if (confirm('Thông tin đã đúng và tạo đơn hàng ?')) {
                            qc_master_submit.normalForm('#frmWorkOrdersAdd');
                            qc_main.scrollTop();
                        }
                    }
                } else { //dơn hang tam
                    if (beforePay > totalPricePay) {
                        alert('Tiền cọc không lớn hơn số tiền cần thanh toán');
                        txtBeforePay.focus();
                        return false;
                    } else {
                        if (confirm('Thông tin đã đúng và tạo đơn hàng báo giá ?')) {
                            qc_master_submit.normalForm('#frmWorkOrdersAdd');
                            qc_main.scrollTop();
                        }
                    }
                }

            } else {
                alert('Nhập thông tin sản phẩm');
                return false;
            }
        },
        updateOrderPrice: function () {
            var txtTotalPriceShow = $('#frmWorkOrdersAdd').find("input[name='txtTotalPriceShow']");
            var txtTotalPricePay = $('#frmWorkOrdersAdd').find("input[name='txtTotalPricePay']");
            var txtTotalPriceDebt = $('#frmWorkOrdersAdd').find("input[name='txtTotalPriceDebt']");
            var txtBeforePay = $('#frmWorkOrdersAdd').find("input[name='txtBeforePay']");
            var cbDiscount = $('#frmWorkOrdersAdd').find("select[name='cbDiscount']");
            var cbVat = $('#frmWorkOrdersAdd').find("select[name='cbVat']");
            var totalPriceProduct = qc_work_orders.add.getTotalPriceOfProduct(); // tong gia tien cua cac san pham

            var totalPricePay = parseInt(qc_main.getNumberInput(txtTotalPricePay.val()));
            var discount = parseInt(qc_main.getNumberInput(cbDiscount.val()));
            var vat = parseInt(qc_main.getNumberInput(cbVat.val()));
            var beforePay = parseInt(qc_main.getNumberInput(txtBeforePay.val()));
            //var totalPriceDebt = parseInt(qc_main.getNumberInput(txtTotalPriceShow.val()));
            if (discount > 0) {
                totalPricePay = totalPriceProduct - (totalPriceProduct * (discount / 100));
            } else {
                totalPricePay = totalPriceProduct;
            }
            if (vat > 0) totalPricePay = totalPricePay + (totalPriceProduct * 0.1);
            beforePay = (isNaN(beforePay)) ? 0 : beforePay;
            var totalPriceDebt = totalPricePay - beforePay;
            txtTotalPriceShow.val(qc_main.formatCurrency(String(totalPriceProduct)));
            txtTotalPricePay.val(qc_main.formatCurrency(String(totalPricePay)));
            txtTotalPriceDebt.val(qc_main.formatCurrency(String(totalPriceDebt)));
        },
        getTotalPriceOfProduct: function () {
            var totalPrice = 0;
            $('#frmWorkOrdersAdd .qc_work_orders_product_add').filter(function () {
                var txtAmount = $(this).find('.txtAmount');
                var txtPrice = $(this).find('.txtPrice');
                var price = parseInt(qc_main.getNumberInput(txtPrice.val()));
                price = (isNaN(price)) ? 0 : price;
                totalPrice = totalPrice + (price * txtAmount.val());
            });
            return totalPrice;

        }
    },
    delete: {
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (frm) {
            var containerNotify = $(frm).find(".qc_notify");
            var txtPayment = $(frm).find("input[name='txtPayment']");
            var txtTotalPaid = $(frm).find("input[name='txtTotalPaid']");
            var txtReason = $(frm).find("input[name='txtReason']");
            if (qc_main.check.inputNull(txtPayment, 'Nhập số hoàn trả')) {
                return false;
            } else {
                var money = parseInt(qc_main.getNumberInput(txtPayment.val()));
                money = (money < 0) ? 0 : money;
                var totalPaid = parseInt(qc_main.getNumberInput(txtTotalPaid.val()));
                if (money > totalPaid) {
                    alert('Số tiền hoàn trả không lớn hơn: ' + totalPaid);
                    txtPayment.focus();
                    return false;
                } else {
                    if (qc_main.check.inputNull(txtReason, 'Nhập lý do hủy đơn hàng')) {
                        return false;
                    } else {
                        if (confirm('Tôi đồng ý hủy đơn hàng này')) {
                            qc_master_submit.ajaxFormHasReload(frm, containerNotify, true);
                        }
                    }
                }
            }
        },
    },
    product: {
        info: {
            getFrm: function (href) {
                qc_master_submit.ajaxNotReload(href, '#qc_master', false);
            },
            save: function (frm) {
                var containerNotify = $(frm).find(".notifyConfirm");
                var txtWidth = $(frm).find("input[name='txtWidth']");
                var txtHeight = $(frm).find("input[name='txtHeight']");
                var txtAmount = $(frm).find("input[name='txtAmount']");
                var txtPrice = $(frm).find("input[name='txtPrice']");
                var txtDescription = $(frm).find("input[name='txtDescription']");
                if (txtWidth.val() != '' || txtHeight.val() != '' || txtAmount.val() != '') {
                    if (txtWidth.val() == 0 || txtWidth.val() == '') {
                        alert('Nhập chiều rộng');
                        txtWidth.focus();
                        return false;
                    }

                    if (txtHeight.val() == 0 || txtHeight.val() == '') {
                        alert('Nhập chiều cao');
                        txtHeight.focus();
                        return false;
                    }
                    if (qc_main.check.inputNull(txtAmount, 'Nhập số lượng sản phẩm')) {
                        txtAmount.focus();
                        return false;
                    }
                    if (qc_main.check.inputNull(txtPrice, 'Nhập giá sản phẩm')) {
                        txtPrice.focus();
                        return false;
                    } else {
                        qc_master_submit.ajaxFormHasReload(frm, containerNotify, true);
                    }
                } else {
                    alert('Nhập thông tin sản phẩm');
                    return false;
                }
            }
        },
        getConfirm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        saveConfirm: function (form) {
            if (confirm('Bạn đông ý xác nhận?')) {
                qc_master_submit.ajaxFormHasReload(form, '', true);
            }
        },
        cancel: {
            getForm: function (href) {
                qc_master_submit.ajaxNotReload(href, '#qc_master', false);
            },
            save: function (frm) {
                var containerNotify = $(frm).find(".qc_notify");
                var txtReason = $(frm).find("input[name='txtReason']");
                if (qc_main.check.inputNull(txtReason, 'Nhập lý do hủy sản phẩm')) {
                    return false;
                } else {
                    if (confirm('Tôi đồng ý hủy sản phẩm này này')) {
                        qc_master_submit.ajaxFormHasReload(frm, containerNotify, true);
                    }
                }
            },
        },
        viewDesign: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        design: {
            getDesignImage: function (href) {
                qc_master_submit.ajaxNotReload(href, '#qc_master', false);
            },
            saveDesign: function (form) {
                var notifyContent = $(form).find('.frm_notify');
                if (confirm('Tôi đồng ý thêm thiết kế này?')) {
                    qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
                    qc_main.scrollTop();
                }
            },
            getApply: function (href) {
                qc_master_submit.ajaxNotReload(href, '#qc_master', false);
            },
            saveApply: function (form) {
                var notifyContent = $(form).find('.frm_notify');
                if (confirm('Tôi đồng ý sử dụng thiết kế này?')) {
                    qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
                    qc_main.scrollTop();
                }
            },
        }

    },
    info: {
        saveEditInfoCustomer: function (frm) {
            var txtCustomerName = $(frm).find("input[name='txtCustomerName']");
            var txtPhone = $(frm).find("input[name='txtCustomerPhone']");
            var txtZalo = $(frm).find("input[name='txtCustomerZalo']");
            var txtAddress = $(frm).find("input[name='txtCustomerAddress']");
            var notify = $(frm).find('.frm_info_edit_notify');

            // thông in khách hàng
            if (qc_main.check.inputNull(txtCustomerName, 'Nhập tên khách hàng')) {
                txtCustomerName.focus();
                return false;
            } else {
                if ($(txtCustomerName).val().length > 30) {
                    alert('Tên không quá 30 ký tự');
                    txtCustomerName.focus();
                    return false;
                }
            }
            if (txtPhone.val() == '' && txtZalo.val() == '' && txtAddress.val() == '') {
                alert('Phải nhập thông tin liên lạc của khách hàng: Điện thoại / Zalo / Địa chỉ');
                txtPhone.focus();
                return false;
            } else {
                if (confirm('Cập nhật thông tin khách hàng này?')) {
                    qc_master_submit.ajaxFormHasReload(frm, notify, false);
                }
            }
        },
        saveEditInfoOrder: function (frm) {
            var txtOrderName = $(frm).find("input[name='txtOrderName']");
            var txtDateReceive = $(frm).find("input[name='txtDateReceive']");
            var txtDateDelivery = $(frm).find("input[name='txtDateDelivery']");
            var txtProvisionalConfirm = $(frm).find("input[name='txtProvisionalConfirm']");
            var notify = $(frm).find('.frm_info_edit_notify');
            // thông in khách hàng
            if (qc_main.check.inputNull(txtOrderName, 'Nhập tên đơn hàng')) {
                txtOrderName.focus();
                return false;
            } else {
                if ($(txtOrderName).val().length > 100) {
                    alert('Tên không quá 100 ký tự');
                    txtOrderName.focus();
                    return false;
                } else {
                    if (txtProvisionalConfirm == 1) {
                        if (txtDateReceive.val() < txtDateDelivery.val()) {
                            if (confirm('Cập nhật thông tin đơn hàng này?')) {
                                qc_master_submit.ajaxFormHasReload(frm, notify, false);
                            }
                        } else {
                            alert('Ngày giao phải lớn hơn ngày nhận');
                            txtDateDelivery.focus();
                            return false;
                        }
                    } else {
                        if (confirm('Cập nhật thông tin đơn hàng này?')) {
                            qc_master_submit.ajaxFormHasReload(frm, notify, false);
                        }
                    }


                }
            }
        },
        getEditPay: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        saveEditPay: function (frm) {
            var txtPayName = $(frm).find("input[name='txtPayName']");
            var txtPayMoney = $(frm).find("input[name='txtPayMoney']");
            var notify = $(frm).find('.frm_info_edit_notify');
            if (qc_main.check.inputNull(txtPayName, 'Nhập tên người thanh toán')) {
                txtPayName.focus();
                return false;
            } else {
                if ($(txtPayName).val().length > 30) {
                    alert('Tên không quá 30 ký tự');
                    txtPayName.focus();
                    return false;
                }
            }
            if (qc_main.check.inputNull(txtPayMoney, 'Nhập số tiền thanh toán')) {
                txtPayMoney.focus();
                return false;
            } else {
                if (confirm('Cập nhật thông tin thanh toán này?')) {
                    qc_master_submit.ajaxFormHasReload(frm, notify, false);
                }
            }
        },
        design: {
            getDesignImage: function (href) {
                qc_master_submit.ajaxNotReload(href, '#qc_master', false);
            },
            saveDesign: function (form) {
                var notifyContent = $(form).find('.frm_notify');
                if (confirm('Tôi đồng ý thêm thiết kế này?')) {
                    qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
                    qc_main.scrollTop();
                }
            },
        }
    },
    edit: {
        addProduct: function (href) {
            qc_master_submit.ajaxNotReloadNoScrollTop(href, '#qc_work_orders_edit_add_product_wrap', false);
        },
        checkProductInput: function () {
            $('#frmWorkOrdersEditAddProduct .qc_work_orders_product_add').filter(function () {
                if ($(this).is(':last-child')) {
                    var txtProductType = $(this).find('.txtProductType');
                    var txtWidth = $(this).find('.txtWidth');
                    var txtHeight = $(this).find('.txtHeight');
                    //var depth = $(this).find('.cbProductType');
                    var txtAmount = $(this).find('.txtAmount');
                    var txtPrice = $(this).find('.txtPrice');
                    if (txtProductType.val() != '' || txtWidth.val() != '' || txtHeight.val() != '' || txtAmount.val() != '') {
                        if (qc_main.check.inputNull(txtProductType, 'Nhập loại sản phẩm')) {
                            txtProductType.focus();
                            return false;
                        }
                        if (txtWidth.val() == 0 || txtWidth.val() == '') {
                            alert('Nhập chiều rộng');
                            txtWidth.focus();
                            return false;
                        }

                        if (txtHeight.val() == 0 || txtHeight.val() == '') {
                            alert('Nhập chiều cao');
                            txtHeight.focus();
                            return false;
                        }
                        if (qc_main.check.inputNull(txtAmount, 'Nhập số lượng sản phẩm')) {
                            txtAmount.focus();
                            return false;
                        }
                        if (qc_main.check.inputNull(txtPrice, 'Nhập giá sản phẩm')) {
                            txtPrice.focus();
                            return false;
                        } else {
                            if (confirm('Thông tin đã đúng và thêm sản phẩm ?')) {
                                qc_master_submit.normalForm('#frmWorkOrdersEditAddProduct');
                                qc_main.scrollTop();
                            }
                        }
                    } else {
                        alert('Nhập thông tin sản phẩm');
                        return false;
                    }
                } else {
                    var txtProductType = $(this).find('.txtProductType');
                    var txtWidth = $(this).find('.txtWidth');
                    var txtHeight = $(this).find('.txtHeight');
                    //var depth = $(this).find('.cbProductType');
                    var txtAmount = $(this).find('.txtAmount');
                    var txtPrice = $(this).find('.txtPrice');
                    if (txtProductType.val() != '' || txtWidth.val() != '' || txtHeight.val() != '' || txtAmount.val() != '') {
                        if (qc_main.check.inputNull(txtProductType, 'Chọn loại sản phẩm')) {
                            txtProductType.focus();
                            return false;
                        } else {
                            if (txtProductType.val().length > 50) {
                                alert('Tên loại sản phẩm không dài quá 50 ký tự');
                                txtProductType.focus();
                                return false;
                            }
                        }
                        if (txtWidth.val() == 0 || txtWidth.val() == '') {
                            alert('Nhập chiều rộng');
                            txtWidth.focus();
                            return false;
                        }

                        if (txtHeight.val() == 0 || txtHeight.val() == '') {
                            alert('Nhập chiều cao');
                            txtHeight.focus();
                            return false;
                        }
                        if (qc_main.check.inputNull(txtAmount, 'Nhập số lượng sản phẩm')) {
                            txtAmount.focus();
                            return false;
                        }
                        if (qc_main.check.inputNull(txtPrice, 'Nhập giá sản phẩm')) {
                            txtPrice.focus();
                            return false;
                        }
                    } else {
                        alert('Nhập thông tin sản phẩm');
                        return false;
                    }
                }

            });
        },
        save: function (frm) {
            // thông tin sản phẩm
            if ($('#frmWorkOrdersEditAddProduct .qc_work_orders_product_add').length > 0) {
                qc_work_orders.edit.checkProductInput();
            } else {
                alert('Nhập sản phẩm');
                return false;
            }
        },
    },
    provisional: {
        getConfirm: function (listObject) {
            qc_master_submit.ajaxNotReload($(listObject).parents('.qc_work_orders_provisional_list_content').data('href-confirm') + '/' + $(listObject).data('object'), '#qc_master', false);
        },
        saveConfirm: function (frm) {
            var txtDateReceive = $(frm).find("input[name='txtDateReceive']");
            var txtDateDelivery = $(frm).find("input[name='txtDateDelivery']");
            var txtMoney = $(frm).find("input[name='txtMoney']");
            var txtName = $(frm).find("input[name='txtName']");
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền thanh toán')) {
                txtMoney.focus();
                return false;
            } else {
                var money = qc_main.getNumberInput(txtMoney.val());
                var checkMoney = txtMoney.data('check-money');
                if (money > checkMoney) {
                    alert('Tiền cọc không được lớn hơn tiền thanh toán');
                    txtMoney.focus();
                    return false;
                }
            }
            if (qc_main.check.inputNull(txtDateDelivery, 'Ngày giao')) {
                txtName.focus();
                return false;
            } else {
                if (txtDateReceive.val() < txtDateDelivery.val()) {
                    if (confirm('Đặt hàng cho báo giá này?')) {
                        //qc_master_submit.ajaxFormHasReload(frm, notify, false);
                        qc_master_submit.ajaxFormNotReload(frm, '#qc_master', false);
                    }
                } else {
                    alert('Ngày giao phải lớn hơn ngày nhận');
                    txtDateDelivery.focus();
                    return false;
                }
                //qc_master_submit.normalForm('.qc_frm_work_orders_payment');
            }
        },
        cancel: function (listObject) {
            if (confirm('Bạn muốn hủy báo giá này?')) {
                qc_master_submit.ajaxHasReload($(listObject).parents('.qc_work_orders_provisional_list_content').data('href-cancel') + '/' + $(listObject).data('object'), '', false);
            }
        },
    },
    construction: {
        product: {
            /*thong bao sua san pham*/
            getRepair: function (href) {
                qc_master_submit.ajaxNotReload(href, '#qc_master', false);
            },
            saveRepair: function (frm) {
                var containerNotify = $(frm).find(".frm_notify");
                var txtNote = $(frm).find("input[name='txtNote']");
                if (qc_main.check.inputNull(txtNote, 'Nhập ghi chú cho thông báo')) {
                    txtNote.focus();
                    return false;
                } else {
                    if (confirm('Bạn đồng ý với thông báo này?'))
                        qc_master_submit.ajaxFormHasReload(frm, containerNotify, true);
                }
            }
        }
    }
}

//===================== LOAI SAN PHAM ===========================
$(document).ready(function () {
    // loc loai SP theo ten
    $('.qc_work_product_type_wrap').on('click', '.btFilterName', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.qc_work_textNameFilter').val());
    });
});

//=========== ======== ========= QUAN LY DON HANG THUC ======== ========= =============
/* ---------- THONG TIN CHINH -----------------*/
$(document).ready(function () {
    /*xem thong tin khach hang*/
    $('body').on('click', '.qc_work_orders_list_content .qc_view_customer', function () {
        var href = $(this).data('href');
        qc_master_submit.ajaxNotReload(href, '#qc_master', false);
    });

    /*xem thong tin thanh toán*/
    $('body').on('click', '.qc_work_orders_list_content .qc_order_pay_view', function () {
        var amountPay = parseInt($(this).data('amount'));
        if (amountPay > 0) {
            var href = $(this).parents('.qc_work_orders_list_content').data('href-view-pay') + '/' + $(this).parents('.qc_work_list_content_object').data('object');
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        } else {
            alert('Không có thông tin thanh toán');
            return false;
        }

    });
    /*xac nhan hoan thanh*/
    $('#qc_work_order_info_product_show').on('click', '.qc_work_orders_product_confirm_act', function () {
        qc_work_orders.product.getConfirm($(this).data('href'));
    });

    $('body').on('click', '.frmWorkOrderProductConfirm .qc_save', function () {
        qc_work_orders.product.saveConfirm($(this).parents('.frmWorkOrderProductConfirm'));
    });

    /*in don hang*/
    $('#qc_work_order_print_wrap').on('click', '.qc_work_order_order_print', function () {
        $(this).parents('#qc_work_order_order_print_wrap_act').remove();
        window.print();
    });

    /*in nghiem thu*/
    $('#qc_work_order_order_print_confirm_wrap').on('click', '.qc_print', function () {
        $(this).parents('#qc_work_order_order_print_confirm_wrap_act').remove();
        window.print();
    });
});
/* ---------- LOC THONG TIN -----------------*/
$(document).ready(function () {
    /*lọc theo nhan vien cua bo phan quan ly*/
    $('body').on('change', '.qcWorkOrdersStaffFilterId', function () {
        var txtOrderFilterKeyword = 'null';
        var txtOrderCustomerFilterKeyword = 'null';
        qc_work_orders.filter($(this).data('href') + '/' + $('.qcWorkOrdersFinishStatusFilter').val() + '/' + $('.qcWorkOrderMonthFilter').val() + '/' + $('.qcWorkOrderYearFilter').val() + '/' + $('.qcWorkOrderPaymentStatusFilter').val() + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword + '/' + $(this).val());
    });

    /*theo tháng*/
    $('body').on('change', '.qcWorkOrderMonthFilter', function () {
        var txtOrderFilterKeyword = $('.txtOrderFilterKeyword').val();
        txtOrderFilterKeyword = (txtOrderFilterKeyword.length == 0) ? 'null' : txtOrderFilterKeyword;
        var txtOrderCustomerFilterKeyword = $('.txtOrderCustomerFilterKeyword').val();
        txtOrderCustomerFilterKeyword = (txtOrderCustomerFilterKeyword.length == 0) ? 'null' : txtOrderCustomerFilterKeyword;
        qc_work_orders.filter($(this).data('href') + '/' + $('.qcWorkOrdersFinishStatusFilter').val() + '/' + $(this).val() + '/' + $('.qcWorkOrderYearFilter').val() + '/' + $('.qcWorkOrderPaymentStatusFilter').val() + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword + '/' + $('.qcWorkOrdersStaffFilterId').val());
    });
    /* năm*/
    $('body').on('change', '.qcWorkOrderYearFilter', function () {
        var txtOrderFilterKeyword = $('.txtOrderFilterKeyword').val();
        txtOrderFilterKeyword = (txtOrderFilterKeyword.length == 0) ? 'null' : txtOrderFilterKeyword;
        var txtOrderCustomerFilterKeyword = $('.txtOrderCustomerFilterKeyword').val();
        txtOrderCustomerFilterKeyword = (txtOrderCustomerFilterKeyword.length == 0) ? 'null' : txtOrderCustomerFilterKeyword;
        qc_work_orders.filter($(this).data('href') + '/' + $('.qcWorkOrdersFinishStatusFilter').val() + '/' + $('.qcWorkOrderMonthFilter').val() + '/' + $(this).val() + '/' + $('.qcWorkOrderPaymentStatusFilter').val() + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword + '/' + $('.qcWorkOrdersStaffFilterId').val());
    });
    /* loc theo ten don hang*/
    $('body').on('keyup', '#qc_work_orders_wrap .txtOrderFilterKeyword', function () {
        $('#qc_order_filter_customer_name_suggestions_wrap').hide();
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $('#qc_order_filter_order_name_suggestions_wrap').show();
                        $('#qc_order_filter_order_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var contentAddress = content[i]['constructionAddress'];
                            $('#qc_order_filter_order_name_suggestions_content').append(
                                "<a class='qc_order_filter_order_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + contentAddress + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_order_filter_order_name_suggestions_wrap').hide();
                        $('#qc_order_filter_order_name_suggestions_content').empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    /* click vao ten don hang goi y*/
    $('body').on('click', '.qc_order_filter_order_name_suggestions_select', function () {
        var txtOrderFilterKeyword = $(this).data('name');
        if ($('.txtOrderFilterKeyword').val(txtOrderFilterKeyword)) {
            $('.btOrderFilterKeyword').click();
        }
    });

    $('body').on('click', '.btOrderFilterKeyword', function () {
        var txtOrderCustomerFilterKeyword = 'null';
        if ($('.txtOrderFilterKeyword').val().length == 0) {
            alert('Nhận từ khóa tìm kiếm');
            $('.txtOrderFilterKeyword').focus();
            return false;
        } else {
            var href = $(this).data('href');
            //100 tim tat ca thoi gian
            qc_work_orders.filter($(this).data('href') + '/' + $('.qcWorkOrdersFinishStatusFilter').val() + '/' + 100 + '/' + 100 + '/' + $('.qcWorkOrderPaymentStatusFilter').val() + '/' + $('.txtOrderFilterKeyword').val() + '/' + txtOrderCustomerFilterKeyword);
        }
    });

    /* loc theo ten khach hang*/
    $('body').on('keyup', '#qc_work_orders_wrap .txtOrderCustomerFilterKeyword', function () {
        $('#qc_order_filter_order_name_suggestions_wrap').hide();
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $('#qc_order_filter_customer_name_suggestions_wrap').show();
                        $('#qc_order_filter_customer_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var phone = content[i]['phone'];
                            $('#qc_order_filter_customer_name_suggestions_content').append(
                                "<a class='qc_order_filter_customer_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + phone + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_order_filter_customer_name_suggestions_wrap').hide();
                        $('#qc_order_filter_customer_name_suggestions_content').empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    /* click vao ten khach hang hang goi y*/
    $('body').on('click', '.qc_order_filter_customer_name_suggestions_select', function () {
        var txtOrderCustomerFilterKeyword = $(this).data('name');
        if ($('.txtOrderCustomerFilterKeyword').val(txtOrderCustomerFilterKeyword)) {
            $('.btOrderCustomerFilterKeyword').click();
        }
    });
    $('body').on('click', '#qc_work_orders_wrap .btOrderCustomerFilterKeyword', function () {
        var txtOrderFilterKeyword = 'null';
        if ($('.txtOrderCustomerFilterKeyword').val().length == 0) {
            alert('Nhận từ khóa tìm kiếm');
            $('.txtOrderCustomerFilterKeyword').focus();
            return false;
        } else {
            var href = $(this).data('href');
            //100 tim tat ca thoi gian
            qc_work_orders.filter($(this).data('href') + '/' + $('.qcWorkOrdersFinishStatusFilter').val() + '/' + 100 + '/' + 100 + '/' + $('.qcWorkOrderPaymentStatusFilter').val() + '/' + txtOrderFilterKeyword + '/' + $('.txtOrderCustomerFilterKeyword').val());
        }
    });
    /*theo trang thai hoan thanh*/
    $('body').on('change', '.qcWorkOrdersFinishStatusFilter', function () {
        qc_work_orders.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qcWorkOrderMonthFilter').val() + '/' + $('.qcWorkOrderYearFilter').val() + '/' + $('.qcWorkOrderPaymentStatusFilter').val() + '/' + 'null' + '/' + 'null' + '/' + $('.qcWorkOrdersStaffFilterId').val());
    });
    /*theo trang thai thanh toam*/
    $('body').on('change', '.qcWorkOrderPaymentStatusFilter', function () {
        qc_work_orders.filter($(this).data('href') + '/' + $('.qcWorkOrdersFinishStatusFilter').val() + '/' + $('.qcWorkOrderMonthFilter').val() + '/' + $('.qcWorkOrderYearFilter').val() + '/' + $(this).val() + '/' + 'null' + '/' + 'null' + '/' + $('.qcWorkOrdersStaffFilterId').val());
    });
});
/* ---------- THANH TOAN DON HANG -----------------*/
$(document).ready(function () {
    /*Thanh toan don hang*/
    $('.qc_frm_work_orders_payment').on('click', '.qc_save', function () {
        qc_work_orders.payment.save('.qc_frm_work_orders_payment');
    });
});

/* ---------  THIET KE TONG THE --------------- */
$(document).ready(function () {
    /*------- ------ them anh thiet ke ----- ---- */
    $('#qc_work_orders_wrap').on('click', '.qc_work_order_design_image_add', function () {
        qc_work_orders.info.design.getDesignImage($(this).data('href'));
    });
    $('body').on('click', '.qc_frm_order_add_design .qc_save', function () {
        qc_work_orders.info.design.saveDesign($(this).parents('.qc_frm_order_add_design'));
    });
    // xoa tk
    $('#qc_work_orders_wrap').on('click', '.qc_work_order_design_image_delete', function () {
        if (confirm('Thiết kế hủy sẽ không được phục hồi, hủy?')) {
            qc_master_submit.ajaxHasReload($(this).data('href'), '', false);
        }
    });
});
/* ---------- CHI TIET THI CONG DON HANG --------- */
$(document).ready(function () {
    /*thong bao sua san pham*/
    $('#qc_order_order_construction_wrap').on('click', '.qc_product_repair_get', function () {
        qc_work_orders.construction.product.getRepair($(this).data('href'))
    });
    $('body').on('click', '.qc_frm_product_repair_add .qc_save', function () {
        qc_work_orders.construction.product.saveRepair($(this).parents('.qc_frm_product_repair_add'));
    });
});
/* ---------- THEM DON HANG THUC -----------------*/
$(document).ready(function () {
    /*nhap gia sp*/
    $('body').on('keyup', '#frmWorkOrdersAdd .txtPrice', function () {
        qc_work_orders.add.updateOrderPrice();
        qc_main.showFormatCurrency(this);
    });
    /*nhap so luong sp*/
    $('body').on('keyup', '#frmWorkOrdersAdd .txtAmount', function () {
        qc_work_orders.add.updateOrderPrice();
    });
    /*chon thue*/
    $('body').on('change', '#frmWorkOrdersAdd .cbVat', function () {
        qc_work_orders.add.updateOrderPrice();
    });

    /*giam gia*/
    $('body').on('change', '#frmWorkOrdersAdd .cbDiscount', function () {
        qc_work_orders.add.updateOrderPrice();
    });

    /*nhap tien coc*/
    $('body').on('keyup', '#frmWorkOrdersAdd .txtBeforePay', function () {
        qc_work_orders.add.updateOrderPrice();
        qc_main.showFormatCurrency(this);
    });
    /*kiem tra so đt khi nhap don hang*/
    $('body').on('keyup', '#frmWorkOrdersAdd .txtPhone', function () {
        $('#qc_customer_name_suggestions_wrap').hide();
        $('#qc_order_add_name_suggestions_wrap').hide();
        var phone = $(this).val();
        var replaceHref = $(this).data('href-replace');
        var checkHref = $(this).data('href-check');
        if (phone.length > 0) {
            var data = {
                txtPhone: phone
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: checkHref + '/' + phone,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $('#qc_customer_phone_suggestions_wrap').show();
                        $('#qc_customer_phone_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentId = content[i]['customer_id'];
                            var contentPhone = content[i]['phone'];
                            $('#qc_customer_phone_suggestions_content').append(
                                "<a href='" + replaceHref + '/' + contentId + "'>" + contentPhone + "</a><br/>"
                            );
                        }
                        /*var customerId = result['customerId'];
                         qc_main.url_replace(addHref + '/' + customerId);*/
                    } else if (result['status'] == 'notExist') {
                        $('#qc_customer_phone_suggestions_wrap').hide();
                        $('#qc_customer_phone_suggestions_content').empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    alert('Error');
                }
            });
        }
    });

    /*kiem tra so đt khi nhap don hang*/
    $('body').on('keyup', '#frmWorkOrdersAdd .txtCustomerName', function () {
        $('#qc_customer_phone_suggestions_wrap').hide();
        $('#qc_order_add_name_suggestions_wrap').hide();
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        var replaceHref = $(this).data('href-replace');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $('#qc_customer_name_suggestions_wrap').show();
                        $('#qc_customer_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentId = content[i]['customer_id'];
                            var contentName = content[i]['name'];
                            var contentPhone = content[i]['phone'];
                            $('#qc_customer_name_suggestions_content').append(
                                "<a href='" + replaceHref + '/' + contentId + "'>" + contentName + "</a> - " + contentPhone + "<br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_customer_name_suggestions_wrap').hide();
                        $('#qc_customer_name_suggestions_content').empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });

    /*-------- ------- kiem tra goi y don hang co san ------- --------- */
    $('body').on('keyup', '#frmWorkOrdersAdd .txtOrderName', function () {
        $('#qc_customer_phone_suggestions_wrap').hide();
        $('#qc_customer_name_suggestions_wrap').hide();
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $('#qc_order_add_name_suggestions_wrap').show();
                        $('#qc_order_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var contentAddress = content[i]['constructionAddress'];
                            var contentPhone = content[i]['constructionPhone'];
                            var contentContact = content[i]['constructionContact'];
                            $('#qc_order_name_suggestions_content').append(
                                "<a class='qc_order_name_suggestions_select qc-link' data-name='" + contentName + "' data-address='" + contentAddress + "' data-contact='" + contentContact + "' data-phone='" + contentPhone + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + contentAddress + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_order_add_name_suggestions_wrap').hide();
                        $('#qc_order_add_name_suggestions_content').empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    //chon don hang goi ý
    $('body').on('click', '#frmWorkOrdersAdd .qc_order_name_suggestions_select', function () {
        var name = $(this).data('name');
        var address = $(this).data('address');
        var phone = $(this).data('phone');
        var contact = $(this).data('contact');
        $('.txtOrderName').val(name);
        $('.txtConstructionAddress').val(address);
        $('.txtConstructionPhone').val(phone);
        $('.txtConstructionContact').val(contact);
        $('#qc_order_add_name_suggestions_wrap').hide();
        $('#qc_order_add_name_suggestions_content').empty();
    });

    /* ------- ----------- kiem tra loai san pham -------- -------- */
    $('body').on('keyup', '#frmWorkOrdersAdd .qc_work_orders_product_add .txtProductType', function () {
        var objectWrap = $(this).parents('.qc_work_orders_product_add');
        var suggestions_wrap = objectWrap.find('.qc_order_add_product_type_suggestions_wrap');
        var suggestions_content = objectWrap.find('.qc_order_add_product_type_suggestions_content');
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $(suggestions_wrap).show();
                        $(suggestions_content).empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var contentUnit = content[i]['unit'];
                            var contentWarrantyTime = content[i]['warrantyTime'];
                            $(suggestions_content).append(
                                "<a class='qc_order_add_product_type_suggestions_select qc-link' data-name='" + contentName + "' data-unit='" + contentUnit + "' data-warranty-time='" + contentWarrantyTime + "'> " + contentName + "</a><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $(suggestions_wrap).hide();
                        $(suggestions_content).empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    $('body').on('click', '#frmWorkOrdersAdd .qc_work_orders_product_add .qc_order_add_product_type_suggestions_wrap', function () {
        $(this).parents('.qc_order_add_product_type_suggestions_wrap').hide();
    });
    /*chon don loai san pham goi y*/
    $('body').on('click', '#frmWorkOrdersAdd .qc_order_add_product_type_suggestions_select', function () {
        var objectWrap = $(this).parents('.qc_work_orders_product_add');
        objectWrap.find('.txtProductType').val($(this).data('name'));
        objectWrap.find('.txtUnit').val($(this).data('unit'));
        objectWrap.find('.txtWarrantyTime').val($(this).data('warranty-time'));
        objectWrap.find('.qc_order_add_product_type_suggestions_wrap').hide();

    });

    /*them san pham cho don hang*/
    $('#frmWorkOrdersAdd').on('click', '.qc_orders_product_add_act', function () {
        qc_work_orders.add.addProduct($(this).data('href'));
    });
    /*xoa san pham khi them*/
    //alert("Tổng số trang đã lưu trong history là: " + window.history.length);
    $('body').on('click', '#frmWorkOrdersAdd .qc_delete', function () {
        // var checkRow = parseInt($(this).parents('.qc_work_orders_product_add').data('row'));
        // var href = $(this).data('href');// + '/' + checkRow ;
        $(this).parents('.qc_work_orders_product_add').remove();
        /*$('#frmWorkOrdersAdd .qc_work_orders_product_add').filter(function () {
         var currentRow = $(this).data('row');
         if (currentRow > checkRow) {
         currentRow = currentRow - 1;
         $(this).attr('data-row', currentRow);
         $(this).find('.qc_show_row').html(currentRow);
         }

         });*/
        //qc_master_submit.ajaxHasReload(href,'',false);
        //qc_master_submit.ajaxHasReload(href, '', false);
        qc_work_orders.add.updateOrderPrice();
    });

    /*LLU don hang chinh*/
    $('#frmWorkOrdersAdd').on('click', '.qc_save', function () {
        qc_work_orders.add.save($(this).parents('#frmWorkOrdersAdd'));
    });


});
/* ---------- BAO CAO HOAN THANH -----------------*/
$(document).ready(function () {
    /*lay form bao cao*/
    $('body').on('click', '.qc_work_orders_list_content .qc_finish_report', function () {
        qc_work_orders.report.getFinish($(this).data('href'), '#qc_master', false);
    });
    /*luu bao cao*/
    $('body').on('click', '.frmOrdersOrderReportFinish .qc_save', function () {
        qc_work_orders.report.saveFinish('.frmOrdersOrderReportFinish', '#qc_master', false);
    });
});
/* ---------- HUY DON HANG -----------------*/
$(document).ready(function () {
    /*xoa don hang*/
    $('body').on('click', '.qc_work_list_content_object .qc_delete', function () {
        qc_work_orders.delete.getForm($(this).data('href'));
    });
    $('body').on('click', '#frmWorkOrderOrderCancel .qc_save', function () {
        qc_work_orders.delete.save($(this).parents('#frmWorkOrderOrderCancel'));
    });
});
/* ---------- CAP NHAT THONG TIN DON HANG + BAO GIA -----------------*/
$(document).ready(function () {
    /*thay doi thong tin KH*/
    $('body').on('click', '#qc_work_order_frm_customer_edit .qc_save', function () {
        qc_work_orders.info.saveEditInfoCustomer($(this).parents('#qc_work_order_frm_customer_edit'));
    });
    /*thay doi thong tin don hang*/
    $('body').on('click', '#qc_work_order_frm_order_edit .qc_save', function () {
        qc_work_orders.info.saveEditInfoOrder($(this).parents('#qc_work_order_frm_order_edit'));
    });

    /*thay doi thong tin thanh toan*/
    $('#qc_work_order_info_payment_show').on('click', '.qc_work_order_info_payment_edit_act', function () {
        qc_work_orders.info.getEditPay($(this).data('href'));
    });
    $('body').on('click', '#qc_work_order_frm_pay_edit .qc_save', function () {
        qc_work_orders.info.saveEditPay($(this).parents('#qc_work_order_frm_pay_edit'));
    });
});
/* ---------- QL SAN PHAM DON HANG + BAO GIA  -----------------*/
$(document).ready(function () {

    /*xem anh thiet ke chi tiet*/
    $('body').on('click', '.qc_work_order_product_design_image_view', function () {
        qc_work_orders.product.viewDesign($(this).data('href'));
    });

    /*------- ------ them anh thiet ke ----- ---- */
    $('#qc_work_order_info_product_show').on('click', '.qc_work_order_product_design_image_add', function () {
        qc_work_orders.product.design.getDesignImage($(this).data('href'));
    });
    $('body').on('click', '.qc_frm_product_add_design .qc_save', function () {
        qc_work_orders.product.design.saveDesign($(this).parents('.qc_frm_product_add_design'));
    });

    /*------- ------ xac nhan ap dung thiet ke ----- ---- */
    // tat / mo thiet ke
    $('body').on('click', '.qc_orders_product_design_apply_act', function () {
        qc_work_orders.product.design.getApply($(this).data('href'));
    });
    $('body').on('click', '.frmWorkOrderProductDesignApplyConfirm .qc_save', function () {
        qc_work_orders.product.design.saveApply($(this).parents('.frmWorkOrderProductDesignApplyConfirm'));
    });

    /* ----- ------ huy thiet ke cua san pham --- ------ ---- */

    $('body').on('click', '.qc_orders_product_design_cancel_act', function () {
        if (confirm('Sau khi hủy thiết kế sẽ KHÔNG ĐƯỢC PHỤC HỒI, đồng ý hủy?')) {
            qc_master_submit.ajaxHasReload($(this).data('href'), '', false);
        }
    });

    /* ----- ------ huy san pham --- ------ ---- */
    $('#qc_work_order_info_product_show').on('click', '.qc_work_orders_product_cancel_act', function () {
        qc_work_orders.product.cancel.getForm($(this).data('href'));
    });
    $('body').on('click', '#frmWorkOrderOrderProductCancel .qc_save', function () {
        qc_work_orders.product.cancel.save($(this).parents('#frmWorkOrderOrderProductCancel'));
    });

    /*----- ----- sua thong tin san pham ----- ------ */
    $('#qc_work_order_info_product_show').on('click', '.qc_work_orders_product_edit_act', function () {
        qc_work_orders.product.info.getFrm($(this).data('href'));
    });
    $('body').on('click', '.frmWorkOrderProductInfoEdit .qc_save', function () {
        qc_work_orders.product.info.save($(this).parents('.frmWorkOrderProductInfoEdit'));
    });
});
/* ---------- THEM SAN PHAM VAO DON HANG -----------------*/
$(document).ready(function () {
    /*kiem tra loai san pham */
    $('body').on('keyup', '#frmWorkOrdersEditAddProduct .qc_work_orders_product_add .txtProductType', function () {
        var objectWrap = $(this).parents('.qc_work_orders_product_add');
        var suggestions_wrap = objectWrap.find('.qc_order_add_product_type_suggestions_wrap');
        var suggestions_content = objectWrap.find('.qc_order_add_product_type_suggestions_content');
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $(suggestions_wrap).show();
                        $(suggestions_content).empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            $(suggestions_content).append(
                                "<a class='qc_order_add_product_type_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $(suggestions_wrap).hide();
                        $(suggestions_content).empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    $('body').on('click', '#frmWorkOrdersEditAddProduct .qc_work_orders_product_add .qc_order_add_product_type_suggestions_close', function () {
        $(this).parents('.qc_order_add_product_type_suggestions_wrap').hide();
    });
    /*chon don loai san pham goi y*/
    $('body').on('click', '#frmWorkOrdersEditAddProduct .qc_order_add_product_type_suggestions_select', function () {
        var name = $(this).data('name');
        var objectWrap = $(this).parents('.qc_work_orders_product_add');
        objectWrap.find('.txtProductType').val(name);
        objectWrap.find('.qc_order_add_product_type_suggestions_wrap').hide();
    });
    /* cap nhat san pham cua don hang*/
    $('#frmWorkOrdersEditAddProduct').on('click', '.qc_orders_edit_product_add_act', function () {
        qc_work_orders.edit.addProduct($(this).data('href'));
    });

    /*nhap gia sp*/
    $('body').on('keyup', '#frmWorkOrdersEditAddProduct .txtPrice', function () {
        qc_main.showFormatCurrency(this);
    });
    /*xoa san pham khi them*/
    $('body').on('click', '#frmWorkOrdersEditAddProduct .qc_work_orders_product_add .qc_delete', function () {
        $(this).parents('.qc_work_orders_product_add').remove();
    });
    /*luu san pham*/
    $('#frmWorkOrdersEditAddProduct').on('click', '.qc_save', function () {
        qc_work_orders.edit.save($(this).parents('#frmWorkOrdersEditAddProduct'));
    });
});


//========= ========== ========= QUAN LY BAO GIA ======= ============= ==========
$(document).ready(function () {
    /*thong tin khach hang*/
    $('body').on('click', '.qc_work_orders_provisional_wrap .qc_view_customer', function () {
        var href = $(this).data('href');
        qc_master_submit.ajaxNotReload(href, '#qc_master', false);
    });
});

/* ---------- HUY BAO GIA -----------------*/
$(document).ready(function () {
    /*xoa don hang*/
    $('body').on('click', '.qc_work_orders_provisional_list_content .qc_order_provisional_cancel', function () {
        qc_work_orders.provisional.cancel($(this).parents('.qc_work_list_content_object'));
    });
});
/* ---------- LOC THONG TIN -----------------*/
$(document).ready(function () {
    /*theo tháng*/
    $('body').on('change', '.qc_work_orders_provisional_login_month', function () {
        var txtOrderProvisionFilterKeyword = $('.txtOrderProvisionFilterKeyword').val();
        txtOrderProvisionFilterKeyword = (txtOrderProvisionFilterKeyword.length == 0) ? 'null' : txtOrderProvisionFilterKeyword;
        var txtOrderProvisionalCustomerFilterKeyword = $('.txtOrderProvisionalCustomerFilterKeyword').val();
        txtOrderProvisionalCustomerFilterKeyword = (txtOrderProvisionalCustomerFilterKeyword.length == 0) ? 'null' : txtOrderProvisionalCustomerFilterKeyword;
        qc_work_orders.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_orders_provisional_login_year').val() + '/' + txtOrderProvisionFilterKeyword + '/' + txtOrderProvisionalCustomerFilterKeyword);
    });
    /* năm*/
    $('body').on('change', '.qc_work_orders_provisional_login_year', function () {
        var txtOrderProvisionFilterKeyword = $('.txtOrderProvisionFilterKeyword').val();
        txtOrderProvisionFilterKeyword = (txtOrderProvisionFilterKeyword.length == 0) ? 'null' : txtOrderProvisionFilterKeyword;
        var txtOrderProvisionalCustomerFilterKeyword = $('.txtOrderProvisionalCustomerFilterKeyword').val();
        txtOrderProvisionalCustomerFilterKeyword = (txtOrderProvisionalCustomerFilterKeyword.length == 0) ? 'null' : txtOrderProvisionalCustomerFilterKeyword;
        qc_work_orders.filter($(this).data('href') + '/' + $('.qc_work_orders_provisional_login_month').val() + '/' + $(this).val() + '/' + txtOrderProvisionFilterKeyword + '/' + txtOrderProvisionalCustomerFilterKeyword);
    });
    /* loc theo ten don hang*/
    $('body').on('keyup', '.txtOrderProvisionFilterKeyword', function () {
        $('#qc_order_provisional_filter_customer_name_suggestions_wrap').hide();
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $('#qc_order_provisional_filter_order_name_suggestions_wrap').show();
                        $('#qc_order_provisional_filter_order_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var contentAddress = content[i]['constructionAddress'];
                            $('#qc_order_provisional_filter_order_name_suggestions_content').append(
                                "<a class='qc_order_provisional_filter_order_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + contentAddress + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_order_provisional_filter_order_name_suggestions_wrap').hide();
                        $('#qc_order_provisional_filter_order_name_suggestions_content').empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    /* click vao ten don hang goi y*/
    $('body').on('click', '.qc_order_provisional_filter_order_name_suggestions_select', function () {
        var txtOrderProvisionFilterKeyword = $(this).data('name');
        if ($('.txtOrderProvisionFilterKeyword').val(txtOrderProvisionFilterKeyword)) {
            $('.btOrderProvisionalFilterKeyword').click();
        }
    });

    $('body').on('click', '.btOrderProvisionalFilterKeyword', function () {
        var txtOrderProvisionalCustomerFilterKeyword = 'null';
        if ($('.txtOrderProvisionFilterKeyword').val().length == 0) {
            alert('Nhận từ khóa tìm kiếm');
            $('.txtOrderProvisionFilterKeyword').focus();
            return false;
        } else {
            var href = $(this).data('href');
            //100 tim tat ca thoi gian
            qc_work_orders.filter($(this).data('href') + '/' + 100 + '/' + 100 + '/' + $('.txtOrderProvisionFilterKeyword').val() + '/' + txtOrderProvisionalCustomerFilterKeyword);
        }
    });

    /* loc theo ten khach hang*/
    $('body').on('keyup', '.txtOrderProvisionalCustomerFilterKeyword', function () {
        $('#qc_order_provisional_filter_order_name_suggestions_wrap').hide();
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $('#qc_order_provisional_filter_customer_name_suggestions_wrap').show();
                        $('#qc_order_provisional_filter_customer_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var phone = content[i]['phone'];
                            $('#qc_order_provision_filter_customer_name_suggestions_content').append(
                                "<a class='qc_order_provisional_filter_customer_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + phone + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_order_provisional_filter_customer_name_suggestions_wrap').hide();
                        $('#qc_order_provision_filter_customer_name_suggestions_content').empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    /* click vao ten khach hang hang goi y*/
    $('body').on('click', '.qc_order_provisional_filter_customer_name_suggestions_select', function () {
        var txtOrderProvisionalCustomerFilterKeyword = $(this).data('name');
        if ($('.txtOrderProvisionalCustomerFilterKeyword').val(txtOrderProvisionalCustomerFilterKeyword)) {
            $('.btOrderProvisionalCustomerFilterKeyword').click();
        }
    });
    $('body').on('click', '.btOrderProvisionalCustomerFilterKeyword', function () {
        var txtOrderProvisionFilterKeyword = 'null';
        if ($('.txtOrderProvisionalCustomerFilterKeyword').val().length == 0) {
            alert('Nhận từ khóa tìm kiếm');
            $('.txtOrderProvisionalCustomerFilterKeyword').focus();
            return false;
        } else {
            var href = $(this).data('href');
            //100 tim tat ca thoi gian
            qc_work_orders.filter($(this).data('href') + '/' + 100 + '/' + 100 + '/' + txtOrderProvisionFilterKeyword + '/' + $('.txtOrderProvisionalCustomerFilterKeyword').val());
        }
    });
});
$(document).ready(function () {

});
/* ---------- XAC NHAN DAT HANG -----------------*/
$(document).ready(function () {
    /*from xac nhan dat hang*/
    $('body').on('click', '.qc_work_orders_provisional_list_content .qc_order_provisional_confirm', function () {
        qc_work_orders.provisional.getConfirm($(this).parents('.qc_work_list_content_object'));
    });
    /*xac nhan*/
    $('body').on('click', '.qc_frm_work_orders_provision_confirm .qc_save', function () {
        qc_work_orders.provisional.saveConfirm('.qc_frm_work_orders_provision_confirm');
    });
});

