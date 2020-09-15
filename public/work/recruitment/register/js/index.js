/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_recruitment_register = {
    add: {
        save: function (frm) {
            var txtSalary = $(frm).find("input[name='txtTotalSalary']");
            // chon cong viec trong bo phan
            if (!qc_work_recruitment_register.add.checkSelectDepartmentWork()) {
                alert('Phải chọn ít nhất 1 kỹ năng làm việc');
                qc_main.scrollTop();
                return false;
            }
            if (qc_main.check.inputNull(txtSalary, 'Phải nhập mức lương đề xuất')) {
                qc_main.scrollTop();
                return false;
            } else {
                var money = qc_main.getNumberInput(txtSalary.val());
                if (money > 50000000) {
                    alert('Mức lương dề xuất không quá 50.000.000');
                    $(txtSalary).focus();
                    return false;
                }
            }

            var txtFirstName = $(frm).find("input[name='txtFirstName']");
            var txtLastName = $(frm).find("input[name='txtLastName']");
            var txtIdentityCard = $(frm).find("input[name='txtIdentityCard']");
            var cbGender = $(frm).find("select[name='cbGender']");
            var txtBirthday = $(frm).find("input[name='txtBirthday']");
            var txtPhone = $(frm).find("input[name='txtPhone']");
            var txtAddress = $(frm).find("input[name='txtAddress']");
            var txtImage = $(frm).find("input[name='txtImage']");
            var txtIdentityCardFront = $(frm).find("input[name='txtIdentityCardFront']");
            var txtIdentityCardBack = $(frm).find("input[name='txtIdentityCardBack']");

            if (qc_main.check.inputNull(txtFirstName, 'Nhập họ')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtFirstName, 30, 'Họ không dài quá 30 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtLastName, 'Nhập tên')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtLastName, 20, 'Tên dài không quá 30 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtIdentityCard, 'Nhập chứng minh thư')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtIdentityCard, 20, 'Chứng minh thư dài không quá 20 ký tự')) return false;
            }

            if (qc_main.check.inputNull(cbGender, 'Chọn giới tính')) {
                return false;
            }

            if (qc_main.check.inputNull(txtBirthday, 'Bạn phải nhập ngày sinh')) {
                return false;
            }

            if (qc_main.check.inputNull(txtPhone, 'Bạn phải nhập số điện thoại')) {
                return false;
            }

            if (qc_main.check.inputNull(txtAddress, 'Nhập địa chỉ')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtAddress, 50, 'Địa chỉ dài quá 50 ký tự')) return false;
            }

            if (qc_main.check.inputNull(txtImage, 'Bạn phải chọn ảnh đại diện cá nhận')) {
                $(txtImage).focus();
                return false;
            }

            if (qc_main.check.inputNull(txtIdentityCardFront, 'Bạn phải chon ảnh CMND mặt trước')) {
                $(txtIdentityCardFront).focus();
                return false;
            }

            if (qc_main.check.inputNull(txtIdentityCardBack, 'Bạn phải chọn ảnh CMND mặt sau')) {
                $(txtIdentityCardBack).focus();
                return false;
            } else {
                qc_master_submit.normalForm(frm);
            }
        },
        // kiem tra co chon cong viec
        checkSelectDepartmentWork: function () {
            //alert()
            var checkStatus = false;
            $('.frmWorRecruitmentRegisterAdd .qcDepartmentWork').filter(function () {
                if ($(this).is(':checked')) checkStatus = true;
            });
            return checkStatus;
        },
    }
}

//-------------------- HINH ANH ------------
$(document).ready(function () {
    // chon bo phan
    $('.frmWorRecruitmentRegisterAdd').on('change', '.cbDepartment', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    });

    $('.frmWorRecruitmentRegisterAdd').on('click', '.qc_save', function () {
        qc_work_recruitment_register.add.save($(this).parents('.frmWorRecruitmentRegisterAdd'));
    });
});
