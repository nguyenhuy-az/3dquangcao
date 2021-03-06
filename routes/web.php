<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//========== ======== Back-end =========== ========
Route::group(['prefix' => 'ad3d'], function () {
    #========== ========== ========== LOGIN ========== ========== ==========
    //login
    Route::group(['prefix' => 'login'], function () {
        Route::get('exit/', ['as' => 'qc.ad3d.login.exit', 'uses' => 'Ad3d\Login\LoginController@getExit']);

        Route::get('/', ['as' => 'qc.ad3d.login.get', 'uses' => 'Ad3d\Login\LoginController@getLogin']);
        Route::post('/', ['as' => 'qc.ad3d.login.post', 'uses' => 'Ad3d\Login\LoginController@postLogin']);
    });

    //Tool
    Route::group(['prefix' => 'store', 'middleware' => 'Ad3dMiddleware'], function () {
        Route::group(['prefix' => 'tool'], function () {
            //loại dụng cụ
            Route::group(['prefix' => 'type'], function () {

                Route::get('view', ['as' => 'qc.ad3d.store.tool.type.view.get', 'uses' => 'Ad3d\Store\Tool\Type\TypeController@view']);
                //edit
                Route::get('edt', ['as' => 'qc.ad3d.store.tool.type.edit.get', 'uses' => 'Ad3d\Store\Tool\Type\TypeController@getEdit']);
                //off work
                Route::get('add', ['as' => 'qc.ad3d.store.store.type.add.get', 'uses' => 'Ad3d\Store\Tool\Type\TypeController@getAdd']);
                Route::post('add', ['as' => 'qc.ad3d.store.tool.type.add.post', 'uses' => 'Ad3d\Store\Tool\Type\TypeController@postAdd']);
                Route::get('/', ['as' => 'qc.ad3d.store.tool.type.get', 'uses' => 'Ad3d\Store\Tool\Type\TypeController@index']);

            });
            //dụng cụ
            Route::group(['prefix' => 'tool'], function () {
                Route::get('view/{toolId?}', ['as' => 'qc.ad3d.store.tool.tool.view.get', 'uses' => 'Ad3d\Store\Tool\Tool\ToolController@view']);

                //edit
                Route::get('edit/{toolId?}', ['as' => 'qc.ad3d.store.tool.tool.edit.get', 'uses' => 'Ad3d\Store\Tool\Tool\ToolController@getEdit']);
                Route::post('edit/{toolId?}', ['as' => 'qc.ad3d.store.tool.tool.edit.post', 'uses' => 'Ad3d\Store\Tool\Tool\ToolController@postEdit']);

                //off work
                Route::get('add', ['as' => 'qc.ad3d.store.tool.tool.add.get', 'uses' => 'Ad3d\Store\Tool\Tool\ToolController@getAdd']);
                Route::post('add', ['as' => 'qc.ad3d.Store.tool.tool.add.post', 'uses' => 'Ad3d\Store\Tool\Tool\ToolController@postAdd']);

                //Xóa
                Route::get('del/{toolId?}', ['as' => 'qc.ad3d.store.tool.tool.del.get', 'uses' => 'Ad3d\Store\Tool\Tool\ToolController@deleteTool']);

                Route::get('/{typeFilter?}', ['as' => 'qc.ad3d.store.tool.tool.get', 'uses' => 'Ad3d\Store\Tool\Tool\ToolController@index']);
            });

            //bàn giao dụng cụ
            Route::group(['prefix' => 'allocation'], function () {
                Route::get('view/{allocationId?}', ['as' => 'qc.ad3d.store.tool.allocation.view.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@viewAllocation']);

                //sửa
                //Route::get('edit/{toolId?}', ['as' => 'qc.ad3d.store.tool.tool.edit.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@getEdit']);
                //Route::post('edit/{toolId?}', ['as' => 'qc.ad3d.store.tool.tool.edit.post', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@postEdit']);

                //thêm
                Route::get('add/{selectCompanyId?}', ['as' => 'qc.ad3d.store.tool.allocation.add.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@getAdd']);
                Route::post('add', ['as' => 'qc.ad3d.store.tool.allocation.add.post', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@postAdd']);

                //Xóa
                //Route::get('del/{toolId?}', ['as' => 'qc.ad3d.store.tool.allocation.del.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@deleteTool']);

                Route::get('/{companyId?}/{name?}', ['as' => 'qc.ad3d.store.tool.allocation.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@index']);
            });

        });

        Route::group(['prefix' => 'supplies'], function () {
            //vật tư
            Route::group(['prefix' => 'supplies'], function () {
                Route::get('view/{suppliesId?}', ['as' => 'qc.ad3d.store.supplies.supplies.view.get', 'uses' => 'Ad3d\Store\Supplies\Supplies\SuppliesController@view']);

                //sửa
                Route::get('edit/{suppliesId?}', ['as' => 'qc.ad3d.store.supplies.supplies.edit.get', 'uses' => 'Ad3d\Store\Supplies\Supplies\SuppliesController@getEdit']);
                Route::post('edit/{suppliesId?}', ['as' => 'qc.ad3d.store.supplies.supplies.edit.post', 'uses' => 'Ad3d\Store\Supplies\Supplies\SuppliesController@postEdit']);

                //thêm mới
                Route::get('add', ['as' => 'qc.ad3d.store.supplies.supplies.add.get', 'uses' => 'Ad3d\Store\Supplies\Supplies\SuppliesController@getAdd']);
                Route::post('add', ['as' => 'qc.ad3d.Store.supplies.supplies.add.post', 'uses' => 'Ad3d\Store\Supplies\Supplies\SuppliesController@postAdd']);

                //Xóa
                Route::get('del/{suppliesId?}', ['as' => 'qc.ad3d.store.supplies.supplies.del.get', 'uses' => 'Ad3d\Store\Supplies\Supplies\SuppliesController@deleteSupplies']);

                Route::get('/{companyFilterId?}', ['as' => 'qc.ad3d.store.supplies.supplies.get', 'uses' => 'Ad3d\Store\Supplies\Supplies\SuppliesController@index']);
            });

        });

        #nhập kho
        Route::group(['prefix' => 'import'], function () {
            //xem chi tiết
            Route::get('view/{importId?}', ['as' => 'qc.ad3d.store.import.view.get', 'uses' => 'Ad3d\Store\Import\ImportController@viewImport']);

            //thêm mới
            //Route::get('add', ['as' => 'qc.ad3d.store.import.add.get', 'uses' => 'Ad3d\Store\Import\ImportController@getAdd']);
            //Route::post('add', ['as' => 'qc.ad3d.Store.import.add.post', 'uses' => 'Ad3d\Store\Import\ImportController@postAdd']);

            //Xóa
            //Route::get('del/{importId?}', ['as' => 'qc.ad3d.store.import.del.get', 'uses' => 'Ad3d\Store\Import\ImportController@deleteSupplies']);

            //tanh toan hoa don
            Route::get('pay/{importId?}', ['as' => 'qc.ad3d.store.import.pay.get', 'uses' => 'Ad3d\Store\Import\ImportController@getPay']);
            Route::post('pay/{importId?}', ['as' => 'qc.ad3d.store.import.pay.post', 'uses' => 'Ad3d\Store\Import\ImportController@postPay']);

            //Duyệt
            Route::get('confirm/{importId?}', ['as' => 'qc.ad3d.store.import.confirm.get', 'uses' => 'Ad3d\Store\Import\ImportController@getConfirm']);
            Route::post('confirm/{importId?}', ['as' => 'qc.ad3d.store.import.confirm.post', 'uses' => 'Ad3d\Store\Import\ImportController@postConfirm']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{payStatus?}/{staffId?}', ['as' => 'qc.ad3d.store.import.get', 'uses' => 'Ad3d\Store\Import\ImportController@index']);
        });

        # thong tin kho
        Route::group(['prefix' => 'store'], function () {
            #thong tin dung cu
            Route::get('tool/{companyId?}/{name?}/{type?}', ['as' => 'qc.ad3d.store.store.tool.get', 'uses' => 'Ad3d\Store\Store\StoreController@index']);

        });

    });

    //statistic
    Route::group(['prefix' => 'statistic', 'middleware' => 'Ad3dMiddleware'], function () {
        Route::group(['prefix' => 'revenue-system'], function () {
            Route::get('/{day?}/{month?}/{year?}', ['as' => 'qc.ad3d.statistic.revenue.system.get', 'uses' => 'Ad3d\Statistic\Revenue\System\RevenueSystemController@index']);
        });

        Route::group(['prefix' => 'revenue-company'], function () {
            Route::group(['prefix' => 'staff'], function () {
                // thu
                Route::get('view-order-pay/{staffId?}/{date?}', ['as' => 'qc.ad3d.statistic.revenue.company.staff.order_pay.view', 'uses' => 'Ad3d\Statistic\Revenue\Company\RevenueCompanyController@detailOrderPay']);
                Route::get('view-transfer-money/{staffId?}/{date?}', ['as' => 'qc.ad3d.statistic.revenue.company.staff.transfer_money.view', 'uses' => 'Ad3d\Statistic\Revenue\Company\RevenueCompanyController@detailTransferMoney']);
                Route::get('view-receive-money/{staffId?}/{date?}', ['as' => 'qc.ad3d.statistic.revenue.company.staff.receive_money.view', 'uses' => 'Ad3d\Statistic\Revenue\Company\RevenueCompanyController@detailReceiveMoney']);
                //chi - xem chi tiet
                Route::get('view-import/{staffId?}/{date?}', ['as' => 'qc.ad3d.statistic.revenue.company.staff.import.view', 'uses' => 'Ad3d\Statistic\Revenue\Company\RevenueCompanyController@detailImport']);
                Route::get('view-salary-before-pay/{staffId?}/{date?}', ['as' => 'qc.ad3d.statistic.revenue.company.staff.salary_before_pay.view', 'uses' => 'Ad3d\Statistic\Revenue\Company\RevenueCompanyController@detailSalaryBeforePay']);
                Route::get('view-salary-pay/{staffId?}/{date?}', ['as' => 'qc.ad3d.statistic.revenue.company.staff.salary_pay.view', 'uses' => 'Ad3d\Statistic\Revenue\Company\RevenueCompanyController@detailSalaryPay']);
                Route::get('view-pay-activity/{staffId?}/{date?}', ['as' => 'qc.ad3d.statistic.revenue.company.staff.pay_activity.view', 'uses' => 'Ad3d\Statistic\Revenue\Company\RevenueCompanyController@detailPayActivity']);

                Route::get('/{staffId?}/{date?}', ['as' => 'qc.ad3d.statistic.revenue.company.staff.get', 'uses' => 'Ad3d\Statistic\Revenue\Company\RevenueCompanyController@staffStatistic']);
            });
            Route::get('/{companyId?}/{day?}/{month?}/{year?}', ['as' => 'qc.ad3d.statistic.revenue.company.get', 'uses' => 'Ad3d\Statistic\Revenue\Company\RevenueCompanyController@index']);
        });

        Route::group(['prefix' => 'timekeeping'], function () {

            #xem chi tiet cham cong
            Route::get('view', ['as' => 'qc.ad3d.work.work.time-keeping.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@view']);

            //off work
            Route::get('add', ['as' => 'qc.ad3d.work.time-keeping.off.add.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.work.time-keeping.off.add.post', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@postAdd']);
            Route::get('/', ['as' => 'qc.ad3d.work.time-keeping.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@index']);
        });

    });

    //làm việc
    Route::group(['prefix' => 'work', 'middleware' => 'Ad3dMiddleware'], function () {
        //phân viêc cho nhân viên
        Route::group(['prefix' => 'work-allocation'], function () {
            # phat boi thuong vat tu
            Route::group(['prefix' => 'minus-money'], function () {
                Route::get('add/{workAllocationId?}', ['as' => 'qc.ad3d.work.work_allocation.minus_money.add.get', 'uses' => 'Ad3d\Work\WorkAllocation\WorkAllocationController@getMinusMoney']);
                Route::post('add/{workAllocationId?}', ['as' => 'qc.ad3d.work.work_allocation.minus_money.add.post', 'uses' => 'Ad3d\Work\WorkAllocation\WorkAllocationController@postMinusMoney']);
            });
            Route::get('view/{allocationId?}', ['as' => 'qc.ad3d.work.work_allocation.view.get', 'uses' => 'Ad3d\Work\WorkAllocation\WorkAllocationController@view']);
            # huy phan viec
            //Route::get('cancel/{allocationId?}', ['as' => 'qc.ad3d.work.work_allocation.delete', 'uses' => 'Ad3d\Work\WorkAllocation\WorkAllocationController@cancelWorkAllocation']);

            Route::get('/{day?}/{month?}/{year?}/{finishStatus?}/{name?}', ['as' => 'qc.ad3d.work.work_allocation.get', 'uses' => 'Ad3d\Work\WorkAllocation\WorkAllocationController@index']);
        });

        #bao cao cong viec
        Route::group(['prefix' => 'work-allocation-report'], function () {
            Route::get('view-image/{imageId?}', ['as' => 'qc.ad3d.work.work_allocation_report.image.view', 'uses' => 'Ad3d\Work\WorkAllocationReport\WorkAllocationReportController@viewImage']);
            Route::get('/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.work.work_allocation_report.get', 'uses' => 'Ad3d\Work\WorkAllocationReport\WorkAllocationReportController@index']);
        });

        // thông tin làm việc
        Route::group(['prefix' => 'work'], function () {
            Route::get('view/{workId?}', ['as' => 'qc.ad3d.work.work.view.get', 'uses' => 'Ad3d\Work\Work\WorkController@view']);
            Route::get('view-old/{workId?}', ['as' => 'qc.ad3d.work.work.view_old.get', 'uses' => 'Ad3d\Work\Work\WorkController@viewOld']); # du lieu phien bang cu

            Route::get('salary/{workId?}', ['as' => 'qc.ad3d.work.work.make_salary.get', 'uses' => 'Ad3d\Work\Work\WorkController@getMakeSalaryWork']);
            Route::post('salary/{workId?}', ['as' => 'qc.ad3d.work.work.make_salary.post', 'uses' => 'Ad3d\Work\Work\WorkController@postMakeSalaryWork']);

            Route::get('old/{companyId?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.work.work.old.get', 'uses' => 'Ad3d\Work\Work\WorkController@indexOld']);
            Route::get('/{companyId?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.work.work.get', 'uses' => 'Ad3d\Work\Work\WorkController@index']);
        });
        // duyệt xin nghĩ
        Route::group(['prefix' => 'off-work'], function () {
            Route::get('confirm/{timekeepingProvisionalId?}', ['as' => 'qc.ad3d.work.off-work.confirm.get', 'uses' => 'Ad3d\Work\LicenseOffWork\LicenseOffWorkController@getConfirm']);
            Route::post('confirm/', ['as' => 'qc.ad3d.work.off-work.confirm.post', 'uses' => 'Ad3d\Work\LicenseOffWork\LicenseOffWorkController@postConfirm']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.work.off-work.get', 'uses' => 'Ad3d\Work\LicenseOffWork\LicenseOffWorkController@index']);
        });

        // duyệt xin đi trễ
        Route::group(['prefix' => 'late-work'], function () {
            Route::get('confirm/{timekeepingProvisionalId?}', ['as' => 'qc.ad3d.work.late-work.confirm.get', 'uses' => 'Ad3d\Work\LicenseLateWork\LicenseLateWorkController@getConfirm']);
            Route::post('confirm/', ['as' => 'qc.ad3d.work.late-work.confirm.post', 'uses' => 'Ad3d\Work\LicenseLateWork\LicenseLateWorkController@postConfirm']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.work.late-work.get', 'uses' => 'Ad3d\Work\LicenseLateWork\LicenseLateWorkController@index']);
        });

        // duyệt chấm công - nv tự chấm
        Route::group(['prefix' => 'timekeeping-provisional'], function () {
            # canh bao gio vao
            Route::get('warning-begin/{timekeepingProvisionalId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.warning_begin.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@getWarningBegin']);
            Route::post('warning-begin/{timekeepingProvisionalId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.warning_begin.post', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@postWarningBegin']);
            # canh bao gio ra
            Route::get('warning-end/{timekeepingProvisionalId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.warning_end.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@getWarningEnd']);
            Route::post('warning-end/{timekeepingProvisionalId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.warning_end.post', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@postWarningEnd']);

            #huy canh bao
            Route::get('warning-timekeeping-cancel/{warningId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.warning_timekeeping.cancel', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@cancelWarningTimekeeping']);

            #xem anh cham cong
            Route::get('image/{imageId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.view.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@viewProvisionalImage']);

            #xác nhận
            Route::get('confirm/{timekeepingProvisionalId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.confirm.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@getConfirm']);
            Route::post('confirm/', ['as' => 'qc.ad3d.work.time_keeping_provisional.confirm.post', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@postConfirm']);

            #yeu cau tang ca
            Route::get('over-time/{companyStaffWorkId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.over_time.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@getOverTime']);
            Route::post('over-time/{companyStaffWorkId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.over_time.post', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@postOverTime']);
            # huy
            Route::get('over-time-cancel/{requestId?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.over_time.cancel', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@cancelOverTime']);

            Route::get('/{companyId?}/{name?}', ['as' => 'qc.ad3d.work.time_keeping_provisional.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@index']);
        });
        //hệ thống chấm công - khi chưa cho nv tự chấm
        Route::group(['prefix' => 'timekeeping'], function () {
            #xem anh cham cong
            Route::get('image/{imageId?}', ['as' => 'qc.ad3d.work.time-keeping.image.view.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@viewImage']);
            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.work.time-keeping.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@index']);
        });

    });

    //tài chính
    Route::group(['prefix' => 'finance', 'middleware' => 'Ad3dMiddleware'], function () {
        # giu tien NV
        Route::group(['prefix' => 'keep-money'], function () {
            Route::get('/{companyFilterId?}/{day?}/{month?}/{year?}/{staffId?}/{payStatus?}', ['as' => 'qc.ad3d.finance.keep_money.get', 'uses' => 'Ad3d\Finance\KeepMoney\KeepMoneyController@index']);
        });
        # giao tien
        Route::group(['prefix' => 'transfers'], function () {
            Route::group(['prefix' => 'transfers'], function () {
                //Route::get('view/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.transfers.view.get', 'uses' => 'Ad3d\Finance\Transfers\Transfers\TransfersController@view']);

                Route::get('edit/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.transfers.edit.get', 'uses' => 'Ad3d\Finance\Transfers\Transfers\TransfersController@getEdit']);
                Route::post('edit/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.transfers.edit.post', 'uses' => 'Ad3d\Finance\Transfers\Transfers\TransfersController@postEdit']);

                Route::get('add', ['as' => 'qc.ad3d.finance.transfers.transfers.add.get', 'uses' => 'Ad3d\Finance\Transfers\Transfers\TransfersController@getAdd']);
                Route::post('add', ['as' => 'qc.ad3d.finance.transfers.transfers.add.post', 'uses' => 'Ad3d\Finance\Transfers\Transfers\TransfersController@postAdd']);

                //huy chuyen tien
                Route::get('delete/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.transfers.delete', 'uses' => 'Ad3d\Finance\Transfers\Transfers\TransfersController@deleteTransfers']);
                Route::get('/{companyId?}/{day?}/{month?}/{year?}/{typeId?}/{staffId?}', ['as' => 'qc.ad3d.finance.transfers.transfers.get', 'uses' => 'Ad3d\Finance\Transfers\Transfers\TransfersController@index']);
            });
            Route::group(['prefix' => 'receive'], function () {
                # xac nhan da nhan tien
                Route::get('confirm/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.receive.confirm.get', 'uses' => 'Ad3d\Finance\Transfers\Receive\ReceiveController@getConfirmReceive']);
                Route::post('confirm/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.receive.confirm.post', 'uses' => 'Ad3d\Finance\Transfers\Receive\ReceiveController@postConfirmReceive']);

                Route::get('/{companyId?}/{day?}/{month?}/{year?}/{typeId?}/{staffId?}', ['as' => 'qc.ad3d.finance.transfers.receive.get', 'uses' => 'Ad3d\Finance\Transfers\Receive\ReceiveController@index']);
            });
        });

        // chi hoat dong cty
        Route::group(['prefix' => 'pay-activity'], function () {

            Route::get('view/{payId?}', ['as' => 'qc.ad3d.finance.pay_activity.view.get', 'uses' => 'Ad3d\Finance\Pay\PayActivity\PayActivityController@view']);

            Route::get('confirm/{payId?}', ['as' => 'qc.ad3d.finance.pay_activity.confirm.get', 'uses' => 'Ad3d\Finance\Pay\PayActivity\PayActivityController@getConfirm']);
            Route::post('confirm/{payId?}', ['as' => 'qc.ad3d.finance.pay_activity.confirm.post', 'uses' => 'Ad3d\Finance\Pay\PayActivity\PayActivityController@postConfirm']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{confirmStatus?}/{staffFilterId?}', ['as' => 'qc.ad3d.finance.pay_activity.get', 'uses' => 'Ad3d\Finance\Pay\PayActivity\PayActivityController@index']);
        });

        Route::group(['prefix' => 'payment'], function () {

            Route::get('view/{paymentId?}', ['as' => 'qc.ad3d.finance.payment.view.get', 'uses' => 'Ad3d\Finance\Payment\PaymentController@view']);

            Route::get('edit/{paymentId?}', ['as' => 'qc.ad3d.finance.payment.edit.get', 'uses' => 'Ad3d\Finance\Payment\PaymentController@getEdit']);
            Route::post('edit/{paymentId?}', ['as' => 'qc.ad3d.finance.payment.edit.post', 'uses' => 'Ad3d\Finance\Payment\PaymentController@postEdit']);

            Route::get('add', ['as' => 'qc.ad3d.finance.payment.add.get', 'uses' => 'Ad3d\Finance\Payment\PaymentController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.finance.payment.add.post', 'uses' => 'Ad3d\Finance\Payment\PaymentController@postAdd']);

            //delete
            Route::get('delete/{paymentId?}', ['as' => 'qc.ad3d.finance.payment.delete', 'uses' => 'Ad3d\Finance\Payment\PaymentController@deletePayment']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{typeId?}', ['as' => 'qc.ad3d.finance.payment.get', 'uses' => 'Ad3d\Finance\Payment\PaymentController@index']);
        });

        Route::group(['prefix' => 'order-payment'], function () {
            Route::get('view/{orderId?}', ['as' => 'qc.ad3d.finance.order-payment.view.get', 'uses' => 'Ad3d\Finance\OrderPayment\OrderPaymentController@view']);

            //add
            Route::get('add/{orderId?}', ['as' => 'qc.ad3d.finance.order-payment.add.get', 'uses' => 'Ad3d\Finance\OrderPayment\OrderPaymentController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.finance.order-payment.add.post', 'uses' => 'Ad3d\Finance\OrderPayment\OrderPaymentController@postAdd']);

            Route::get('delete/{payId?}', ['as' => 'qc.ad3d.finance.order-payment.delete', 'uses' => 'Ad3d\Finance\OrderPayment\OrderPaymentController@cancelOrderPay']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{orderFilterName?}/{staffFilterId?}/{transferStatus?}', ['as' => 'qc.ad3d.finance.order-payment.get', 'uses' => 'Ad3d\Finance\OrderPayment\OrderPaymentController@index']);
        });

        #thong tin phat
        Route::group(['prefix' => 'minus-money'], function () {
            # xac nhan phan hoi
            Route::group(['prefix' => 'feedback'], function () {
                Route::get('confirm/{feedbackId?}', ['as' => 'qc.ad3d.finance.minus-money.feedback.confirm.get', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@getConfirmFeedback']);
                Route::post('confirm/{feedbackId?}', ['as' => 'qc.ad3d.finance.minus-money.feedback.confirm.post', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@postConfirmFeedback']);
                # xem anh phan hoi
                Route::get('view-feedback-image/{feedbackId?}', ['as' => 'qc.ad3d.finance.minus_money.feedback.image.view', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@viewFeedbackImage']);
            });
            # xem anh phat
            Route::get('view-image/{minusId?}', ['as' => 'qc.ad3d.finance.minus_money.image.view', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@viewImage']);
            #them - cu
            Route::get('add/{companyLoginId?}/{workId?}/{punishId?}', ['as' => 'qc.ad3d.finance.minus_money.add.get', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.finance.minus_money.add.post', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@postAdd']);
            #sua
            //Route::get('edit/{minusId?}', ['as' => 'qc.ad3d.finance.minus-money.edit.get', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@getEdit']);
            //Route::post('edit/{minusId?}', ['as' => 'qc.ad3d.finance.minus-money.edit.post', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@postEdit']);

            //huy
            Route::get('cancel/{minusId?}', ['as' => 'qc.ad3d.finance.minus-money.cancel', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@cancelMinusMoney']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{punishContentId?}/{staffFilterId?}', ['as' => 'qc.ad3d.finance.minus-money.get', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@index']);
        });

        # thong tin thuong
        Route::group(['prefix' => 'bonus'], function () {
            //huy
            Route::get('cancel/{bonusId?}', ['as' => 'qc.ad3d.finance.bonus.cancel.get', 'uses' => 'Ad3d\Finance\Bonus\BonusController@getCancelBonus']);
            Route::post('cancel/{bonusId?}', ['as' => 'qc.ad3d.finance.bonus.cancel.post', 'uses' => 'Ad3d\Finance\Bonus\BonusController@postCancelBonus']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.finance.bonus.get', 'uses' => 'Ad3d\Finance\Bonus\BonusController@index']);
        });

        Route::group(['prefix' => 'salary'], function () {
            // duyệt xin ứng lương
            Route::group(['prefix' => 'before-pay-request'], function () {
                Route::get('confirm/{requestId?}', ['as' => 'qc.ad3d.salary.before_pay_request.confirm.get', 'uses' => 'Ad3d\Finance\Salary\BeforePayRequest\BeforePayRequestController@getConfirm']);
                Route::post('confirm/', ['as' => 'qc.ad3d.salary.before_pay_request.confirm.post', 'uses' => 'Ad3d\Finance\Salary\BeforePayRequest\BeforePayRequestController@postConfirm']);

                //xác nhận chuyển tiền
                Route::get('transfer/{requestId?}', ['as' => 'qc.ad3d.salary.before_pay_request.transfer.get', 'uses' => 'Ad3d\Finance\Salary\BeforePayRequest\BeforePayRequestController@getTransfer']);
                Route::post('transfer/', ['as' => 'qc.ad3d.salary.before_pay_request.transfer.post', 'uses' => 'Ad3d\Finance\Salary\BeforePayRequest\BeforePayRequestController@postTransfer']);

                Route::get('/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.salary.before_pay_request.get', 'uses' => 'Ad3d\Finance\Salary\BeforePayRequest\BeforePayRequestController@index']);
            });

            // danh sách ứng lương
            Route::group(['prefix' => 'pay-before'], function () {
                Route::get('view/{payId?}', ['as' => 'qc.ad3d.finance.salary.pay-before.view.get', 'uses' => 'Ad3d\Finance\Salary\PayBefore\PayBeforeController@view']);

                Route::get('/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.finance.salary.pay-before.get', 'uses' => 'Ad3d\Finance\Salary\PayBefore\PayBeforeController@index']);
            });
            Route::group(['prefix' => 'payment'], function () {
                Route::get('view/{salaryId?}', ['as' => 'qc.ad3d.finance.salary.payment.view.get', 'uses' => 'Ad3d\Finance\Salary\Payment\PaymentController@view']);

                Route::get('add/{salaryId?}', ['as' => 'qc.ad3d.finance.salary.payment.add.get', 'uses' => 'Ad3d\Finance\Salary\Payment\PaymentController@getAdd']);
                Route::post('add/{salaryId?}', ['as' => 'qc.ad3d.finance.salary.payment.add.post', 'uses' => 'Ad3d\Finance\Salary\Payment\PaymentController@postAdd']);

                Route::get('/{companyId?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.finance.salary.payment.get', 'uses' => 'Ad3d\Finance\Salary\Payment\PaymentController@index']);
            });
        });

    });

    Route::group(['prefix' => 'system', 'middleware' => 'Ad3dMiddleware'], function () {
        # rule
        Route::group(['prefix' => 'rules'], function () {
            // Route::get('view/{rulesId?}', ['as' => 'qc.ad3d.system.rules.view.get', 'uses' => 'Ad3d\System\Rules\RulesController@view']);

            # edit
            Route::get('edit/{rulesId?}', ['as' => 'qc.ad3d.system.rules.edit.get', 'uses' => 'Ad3d\System\Rules\RulesController@getEdit']);
            Route::post('edit/{rulesId?}', ['as' => 'qc.ad3d.system.rules.edit.post', 'uses' => 'Ad3d\System\Rules\RulesController@postEdit']);

            # off work
            Route::get('add/{parentId?}', ['as' => 'qc.ad3d.system.rules.add.get', 'uses' => 'Ad3d\System\Rules\RulesController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.rules.add.post', 'uses' => 'Ad3d\System\Rules\RulesController@postAdd']);

            Route::get('del/{rulesId?}', ['as' => 'qc.ad3d.system.rules.del', 'uses' => 'Ad3d\System\Rules\RulesController@deleteDelete']);

            Route::get('/', ['as' => 'qc.ad3d.system.rules.get', 'uses' => 'Ad3d\System\Rules\RulesController@index']);
        });

        # cong ty
        Route::group(['prefix' => 'company'], function () {
            Route::group(['prefix' => 'partner'], function () {
                # trang chinh
                Route::get('/', ['as' => 'qc.ad3d.system.company.partner.get', 'uses' => 'Ad3d\System\Company\CompanyController@indexPartner']);
            });

            # xem chi tiet
            Route::get('view/{companyId?}', ['as' => 'qc.ad3d.system.company.view.get', 'uses' => 'Ad3d\System\Company\CompanyController@view']);
            # lay link tuyen dung
            Route::get('link/{companyId?}', ['as' => 'qc.ad3d.system.company.recruitment_link.get', 'uses' => 'Ad3d\System\Company\CompanyController@getRecruitmentLink']);
            # sua thong tin
            Route::get('edit/{companyId?}', ['as' => 'qc.ad3d.system.company.edit.get', 'uses' => 'Ad3d\System\Company\CompanyController@getEdit']);
            Route::post('edit/{companyId?}', ['as' => 'qc.ad3d.system.company.post.get', 'uses' => 'Ad3d\System\Company\CompanyController@postEdit']);

            # them cty
            Route::get('add', ['as' => 'qc.ad3d.system.company.add.get', 'uses' => 'Ad3d\System\Company\CompanyController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.company.add.post', 'uses' => 'Ad3d\System\Company\CompanyController@postAdd']);

            # cap nhat nguoi quan ly
            Route::get('update-manager/{companyId?}/{selectObject?}', ['as' => 'qc.ad3d.system.company.update_manager.get', 'uses' => 'Ad3d\System\Company\CompanyController@getUpdateManager']);
            Route::post('update-manager/{companyId?}', ['as' => 'qc.ad3d.system.company.update_manager.post', 'uses' => 'Ad3d\System\Company\CompanyController@postUpdateManager']);

            # trang chinh
            Route::get('/', ['as' => 'qc.ad3d.system.company.get', 'uses' => 'Ad3d\System\Company\CompanyController@index']);
        });


        //rank
        Route::group(['prefix' => 'rank'], function () {
            Route::get('view/{rankId?}', ['as' => 'qc.ad3d.system.rank.view.get', 'uses' => 'Ad3d\System\Rank\RankController@view']);

            //edit
            Route::get('edit/{rankId?}', ['as' => 'qc.ad3d.system.rank.edit.get', 'uses' => 'Ad3d\System\Rank\RankController@getEdit']);
            Route::post('edit/{rankId?}', ['as' => 'qc.ad3d.system.rank.post.get', 'uses' => 'Ad3d\System\Rank\RankController@postEdit']);

            Route::get('add', ['as' => 'qc.ad3d.system.rank.add.get', 'uses' => 'Ad3d\System\Rank\RankController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.rank.add.post', 'uses' => 'Ad3d\System\Rank\RankController@postAdd']);
            Route::get('/', ['as' => 'qc.ad3d.system.rank.get', 'uses' => 'Ad3d\System\Rank\RankController@index']);
        });

        //bo phan
        Route::group(['prefix' => 'department'], function () {
            Route::get('view/{departmentId?}', ['as' => 'qc.ad3d.system.department.view.get', 'uses' => 'Ad3d\System\Department\DepartmentController@view']);

            //activity
            Route::get('status/{departmentId?}', ['as' => 'qc.ad3d.system.department.status.update', 'uses' => 'Ad3d\System\Department\DepartmentController@updateStatus']);

            //edit
            Route::get('edit/{departmentId?}', ['as' => 'qc.ad3d.system.department.edit.get', 'uses' => 'Ad3d\System\Department\DepartmentController@getEdit']);
            Route::post('edit/{departmentId?}', ['as' => 'qc.ad3d.system.department.post.get', 'uses' => 'Ad3d\System\Department\DepartmentController@postEdit']);

            //off work
            Route::get('add', ['as' => 'qc.ad3d.system.department.add.get', 'uses' => 'Ad3d\System\Department\DepartmentController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.department.add.post', 'uses' => 'Ad3d\System\Department\DepartmentController@postAdd']);
            Route::get('/', ['as' => 'qc.ad3d.system.department.get', 'uses' => 'Ad3d\System\Department\DepartmentController@index']);
        });

        # cong viec cua bo phan
        Route::group(['prefix' => 'department-work'], function () {
            #sua thong tin
            Route::get('edit/{workId?}', ['as' => 'qc.ad3d.system.department_work.edit.get', 'uses' => 'Ad3d\System\DepartmentWork\DepartmentWorkController@getEdit']);
            Route::post('edit/{workId?}', ['as' => 'qc.ad3d.system.department_work.post.get', 'uses' => 'Ad3d\System\DepartmentWork\DepartmentWorkController@postEdit']);
            //them moi
            Route::post('add', ['as' => 'qc.ad3d.system.department_work.add.post', 'uses' => 'Ad3d\System\DepartmentWork\DepartmentWorkController@postAdd']);
            # xoa
            Route::get('delete/{workId?}', ['as' => 'qc.ad3d.system.department_work.delete', 'uses' => 'Ad3d\System\DepartmentWork\DepartmentWorkController@deleteInfo']);
            # danh sach CV
            Route::get('/{departmentId?}', ['as' => 'qc.ad3d.system.department_work.get', 'uses' => 'Ad3d\System\DepartmentWork\DepartmentWorkController@index']);
        });
        //danh muc chi hoat dong
        Route::group(['prefix' => 'pay-activity-list'], function () {
            Route::get('view/{payListId?}', ['as' => 'qc.ad3d.system.pay_activity_list.view.get', 'uses' => 'Ad3d\System\PayActivityList\PayActivityListController@view']);

            //sửa
            Route::get('edit/{payListId?}', ['as' => 'qc.ad3d.system.pay_activity_list.edit.get', 'uses' => 'Ad3d\System\PayActivityList\PayActivityListController@getEdit']);
            Route::post('edit/{payListId?}', ['as' => 'qc.ad3d.system.pay_activity_list.post.get', 'uses' => 'Ad3d\System\PayActivityList\PayActivityListController@postEdit']);

            //thêm mới
            Route::get('add', ['as' => 'qc.ad3d.system.pay_activity_list.add.get', 'uses' => 'Ad3d\System\PayActivityList\PayActivityListController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.pay_activity_list.add.post', 'uses' => 'Ad3d\System\PayActivityList\PayActivityListController@postAdd']);
            //xoa danh muc
            Route::get('delete/{payListId?}', ['as' => 'qc.ad3d.system.pay_activity_list.delete', 'uses' => 'Ad3d\System\PayActivityList\PayActivityListController@deletePayList']);
            //danh muc chi
            Route::get('/', ['as' => 'qc.ad3d.system.pay_activity_list.get', 'uses' => 'Ad3d\System\PayActivityList\PayActivityListController@index']);
        });

        //loại hình thanh toán
        Route::group(['prefix' => 'payment-type'], function () {
            Route::get('view/{typeId?}', ['as' => 'qc.ad3d.system.payment-type.view.get', 'uses' => 'Ad3d\System\PaymentType\PaymentTypeController@view']);

            //sửa
            Route::get('edit/{typeId?}', ['as' => 'qc.ad3d.system.payment-type.edit.get', 'uses' => 'Ad3d\System\PaymentType\PaymentTypeController@getEdit']);
            Route::post('edit/{typeId?}', ['as' => 'qc.ad3d.system.payment-type.post.get', 'uses' => 'Ad3d\System\PaymentType\PaymentTypeController@postEdit']);

            //thêm mới
            Route::get('add', ['as' => 'qc.ad3d.system.payment-type.add.get', 'uses' => 'Ad3d\System\PaymentType\PaymentTypeController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.payment-type.add.post', 'uses' => 'Ad3d\System\PaymentType\PaymentTypeController@postAdd']);
            Route::get('/', ['as' => 'qc.ad3d.system.payment-type.get', 'uses' => 'Ad3d\System\PaymentType\PaymentTypeController@index']);
        });

        // danh muc KPI
        Route::group(['prefix' => 'kpi'], function () {
            //Route::get('view/{typeId?}', ['as' => 'qc.ad3d.system.payment-type.view.get', 'uses' => 'Ad3d\System\PaymentType\PaymentTypeController@view']);

            //sửa
            Route::get('edit/{kpiId?}', ['as' => 'qc.ad3d.system.kpi.edit.get', 'uses' => 'Ad3d\System\Kpi\KpiController@getEdit']);
            Route::post('edit/{kpiId?}', ['as' => 'qc.ad3d.system.kpi.edit.post', 'uses' => 'Ad3d\System\Kpi\KpiController@postEdit']);

            //thêm mới
            Route::get('add/{departmentId?}', ['as' => 'qc.ad3d.system.kpi.add.get', 'uses' => 'Ad3d\System\Kpi\KpiController@getAdd']);
            Route::post('add/', ['as' => 'qc.ad3d.system.kpi.add.post', 'uses' => 'Ad3d\System\Kpi\KpiController@postAdd']);

            Route::get('/', ['as' => 'qc.ad3d.system.kpi.get', 'uses' => 'Ad3d\System\Kpi\KpiController@index']);
        });
        // danh thi cong
        Route::group(['prefix' => 'construction-work'], function () {
            Route::get('view/{constructionId?}', ['as' => 'qc.ad3d.system.construction_work.view', 'uses' => 'Ad3d\System\ConstructionWork\ConstructionWorkController@view']);

            //cap nhat thong in
            Route::get('edit/{constructionId?}', ['as' => 'qc.ad3d.system.construction_work.edit.get', 'uses' => 'Ad3d\System\ConstructionWork\ConstructionWorkController@getEdit']);
            Route::post('edit/{constructionId?}', ['as' => 'qc.ad3d.system.construction_work.edit.post', 'uses' => 'Ad3d\System\ConstructionWork\ConstructionWorkController@postEdit']);

            //them moi
            Route::get('add/', ['as' => 'qc.ad3d.system.construction_work.add.get', 'uses' => 'Ad3d\System\ConstructionWork\ConstructionWorkController@getAdd']);
            Route::post('add/', ['as' => 'qc.ad3d.system.construction_work.add.post', 'uses' => 'Ad3d\System\ConstructionWork\ConstructionWorkController@postAdd']);

            // xoa
            Route::get('delete/{constructionId?}', ['as' => 'qc.ad3d.system.construction_work.delete', 'uses' => 'Ad3d\System\ConstructionWork\ConstructionWorkController@delete']);

            Route::get('/', ['as' => 'qc.ad3d.system.construction_work.get', 'uses' => 'Ad3d\System\ConstructionWork\ConstructionWorkController@index']);
        });
        //ngay nghi cua he thong
        Route::group(['prefix' => 'system-date-off'], function () {
            Route::get('view/{payListId?}', ['as' => 'qc.ad3d.system.system_date_off.view.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@view']);
            //thêm mới
            Route::get('add/date', ['as' => 'qc.ad3d.system.system_date_off.add.date.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@getAddDate']);
            Route::get('add', ['as' => 'qc.ad3d.system.system_date_off.add.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.system_date_off.add.post', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@postAdd']);
            # sua
            Route::get('edit/{dateOffId?}', ['as' => 'qc.ad3d.system.system_date_off.edit.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@getEdit']);
            Route::post('edit/{dateOffId?}', ['as' => 'qc.ad3d.system.system_date_off.edit.post', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@postEdit']);

            # sao chep ngay nghi
            Route::get('copy/{companyId?}/{year?}', ['as' => 'qc.ad3d.system.system_date_off.copy.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@getCopyDateOff']);
            Route::post('copy', ['as' => 'qc.ad3d.system.system_date_off.copy.post', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@postCopyDateOff']);

            //xoa ngay nghi
            Route::get('delete/{dateOffId?}', ['as' => 'qc.ad3d.system.system_date_off.delete', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@deleteDateOff']);
            //danh muc chi
            Route::get('/{companyFilterId?}/{monthFilter?}/{yearFilter?}', ['as' => 'qc.ad3d.system.system_date_off.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@index']);
        });

        //nhan vien
        Route::group(['prefix' => 'staff'], function () {
            #---------- --------- thong ke --------- ---------
            Route::group(['prefix' => 'statistical'], function () {
                # cham cong
                Route::get('work/{workId?}', ['as' => 'qc.ad3d.system.staff.statistical.work.get', 'uses' => 'Ad3d\System\Staff\StaffController@getStatisticalWork']);
                # thong ke thuong
                Route::get('bonus/{workId?}', ['as' => 'qc.ad3d.system.staff.statistical.bonus.get', 'uses' => 'Ad3d\System\Staff\StaffController@getStatisticalBonus']);
                # thong ke phat
                Route::get('minus-money/{workId?}', ['as' => 'qc.ad3d.system.staff.statistical.minus_money.get', 'uses' => 'Ad3d\System\Staff\StaffController@getStatisticalMinus']);
                # thong ke - bo phan thi cong
                Route::get('construction/{workId?}/{constructionStatus?}', ['as' => 'qc.ad3d.system.staff.statistical.construction.get', 'uses' => 'Ad3d\System\Staff\StaffController@getStatisticalConstruction']);
                # thong ke - bo phan kinh doanh
                Route::get('business/{workId?}/{constructionStatus?}', ['as' => 'qc.ad3d.system.staff.statistical.business.get', 'uses' => 'Ad3d\System\Staff\StaffController@getStatisticalBusiness']);
                # thong ke
                Route::get('/{companyStaffWorkId?}/{monthFilter?}/{yearMonth?}', ['as' => 'qc.ad3d.system.staff.statistical.get', 'uses' => 'Ad3d\System\Staff\StaffController@getStatistical']);
            });
            #---------- --------- dang lam --------- ---------
            #them moi
            Route::get('add', ['as' => 'qc.ad3d.system.staff.add.get', 'uses' => 'Ad3d\System\Staff\StaffController@getAdd']);
            Route::get('add/department', ['as' => 'qc.ad3d.system.staff.department.add', 'uses' => 'Ad3d\System\Staff\StaffController@getAddDepartment']);
            Route::post('add', ['as' => 'qc.ad3d.system.staff.add.post', 'uses' => 'Ad3d\System\Staff\StaffController@postAdd']);

            #mo cham cong
            Route::get('open-work/{companyStaffWorkId?}', ['as' => 'qc.ad3d.system.staff.open_work.get', 'uses' => 'Ad3d\System\Staff\StaffController@openWork']);

            #phuc hoi lại vi tri làm viec
            Route::get('restore-work/{companyStaffWorkId?}', ['as' => 'qc.ad3d.system.staff.restore_work.get', 'uses' => 'Ad3d\System\Staff\StaffController@restoreWork']);
            # thong tin chi tiet
            Route::get('info/{staffId?}', ['as' => 'qc.ad3d.system.staff.info.get', 'uses' => 'Ad3d\System\Staff\StaffController@getInfo']);
            #thong tin co ban
            Route::get('edit/basic/{staffId?}', ['as' => 'qc.ad3d.system.staff.info_basic.edit.get', 'uses' => 'Ad3d\System\Staff\StaffController@getInfoBasicEdit']);
            Route::post('edit/basic/{staffId?}', ['as' => 'qc.ad3d.system.staff.info_basic.edit.post', 'uses' => 'Ad3d\System\Staff\StaffController@postInfoBasicEdit']);

            #thong tin lam viec
            Route::get('edit/work/{staffId?}', ['as' => 'qc.ad3d.system.staff_info.work.edit.get', 'uses' => 'Ad3d\System\Staff\StaffController@getCompanyWorkEdit']);
            Route::post('edit/work/{staffId?}', ['as' => 'qc.ad3d.system.staff_info.work.edit.post', 'uses' => 'Ad3d\System\Staff\StaffController@postCompanyWorkEdit']);
            #luong
            Route::get('edit/salary/{staffId?}', ['as' => 'qc.ad3d.system.staff_info.salary.edit.get', 'uses' => 'Ad3d\System\Staff\StaffController@getCompanySalaryEdit']);
            Route::post('edit/salary/{staffId?}', ['as' => 'qc.ad3d.system.staff_info.salary.edit.post', 'uses' => 'Ad3d\System\Staff\StaffController@postCompanySalaryEdit']);

            //them hinh anh
            Route::get('image/add/{staffId?}', ['as' => 'qc.ad3d.system.staff.image.add.get', 'uses' => 'Ad3d\System\Staff\StaffController@getAddImage']);
            Route::post('image/add/{staffId?}', ['as' => 'qc.ad3d.system.staff.image.add.post', 'uses' => 'Ad3d\System\Staff\StaffController@postAddImage']);
            //xoa hinh anh
            Route::get('image/del/{staffId?}/{type?}', ['as' => 'qc.ad3d.system.staff.image.delete.get', 'uses' => 'Ad3d\System\Staff\StaffController@deleteImage']);

            //đồi mật khẩu
            Route::get('change-pass', ['as' => 'qc.ad3d.system.staff.change-pass.get', 'uses' => 'Ad3d\System\Staff\StaffController@getChangePass']);
            Route::post('change-pass', ['as' => 'qc.ad3d.system.staff.change-pass.post', 'uses' => 'Ad3d\System\Staff\StaffController@postChangePass']);

            //lấy lại mật khẩu mật định
            Route::get('reset-pass/{staffId?}', ['as' => 'qc.ad3d.system.staff.reset_pass', 'uses' => 'Ad3d\System\Staff\StaffController@resetPassWord']);
            Route::post('reset-pass/{staffId?}', ['as' => 'qc.ad3d.system.staff.reset_pass.post', 'uses' => 'Ad3d\System\Staff\StaffController@postResetPassWord']);

            //đổi tài khoản
            Route::get('change-account', ['as' => 'qc.ad3d.system.staff.change-account.get', 'uses' => 'Ad3d\System\Staff\StaffController@getChangeAccount']);
            Route::post('change-account', ['as' => 'qc.ad3d.system.staff.change-account.post', 'uses' => 'Ad3d\System\Staff\StaffController@postChangeAccount']);

            //xóa NV
            Route::get('delete/{staffId?}', ['as' => 'qc.ad3d.system.staff.delete', 'uses' => 'Ad3d\System\Staff\StaffController@deleteStaff']);

            Route::get('list/{companyId?}/{workStatus?}', ['as' => 'qc.ad3d.system.staff.get', 'uses' => 'Ad3d\System\Staff\StaffController@index']);

        });
        # ho so tuyen dung
        Route::group(['prefix' => 'job-application'], function () {
            # chi tiet ho so
            Route::get('info/{jobApplicationId?}', ['as' => 'qc.ad3d.system.job-application.info.get', 'uses' => 'Ad3d\System\JobApplication\JobApplicationController@getInfo']);
            #xac nhan ho so
            Route::post('confirm/{jobApplicationId?}', ['as' => 'qc.ad3d.system.job-application.confirm.post', 'uses' => 'Ad3d\System\JobApplication\JobApplicationController@postConfirm']);
            # trang chinh
            Route::get('list/{companyId?}/{confirmStatus?}', ['as' => 'qc.ad3d.system.job-application.get', 'uses' => 'Ad3d\System\JobApplication\JobApplicationController@index']);
        });
        # ho so phong van
        Route::group(['prefix' => 'job-application-interview'], function () {
            # chi tiet ho so
            Route::get('info/{interviewId?}', ['as' => 'qc.ad3d.system.job-application-interview.info.get', 'uses' => 'Ad3d\System\JobApplicationInterview\JobApplicationInterviewController@getInfo']);
            #xac nhan ho so
            Route::post('confirm/{interviewId?}', ['as' => 'qc.ad3d.system.job-application-interview.confirm.post', 'uses' => 'Ad3d\System\JobApplicationInterview\JobApplicationInterviewController@postConfirm']);
            # trang chinh
            Route::get('list/{companyId?}/{interviewStatus?}', ['as' => 'qc.ad3d.system.job-application-interview.get', 'uses' => 'Ad3d\System\JobApplicationInterview\JobApplicationInterviewController@index']);
        });
        # luong co ban
        Route::group(['prefix' => 'salary'], function () {
            Route::get('view/{staffId?}', ['as' => 'qc.ad3d.system.salary.view.get', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@view']);

            //edit
            Route::get('edit/{salaryBasicId?}', ['as' => 'qc.ad3d.system.salary.edit.get', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@getEdit']);
            Route::post('edit/{salaryBasicId?}', ['as' => 'qc.ad3d.system.salary.edit.post', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@postEdit']);

            Route::get('list-old/{companyId?}', ['as' => 'qc.ad3d.system.salary_old.get', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@indexOld']);
            Route::get('list/{companyId?}', ['as' => 'qc.ad3d.system.salary.get', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@index']);

        });
        # ho so tuyen dung
        Route::group(['prefix' => 'salary'], function () {
            Route::get('/', ['as' => 'qc.ad3d.system.recruitment.list', 'uses' => 'Ad3d\System\Recruitment\RecruitmentController@index']);
        });
        # thuong theo bo phan
        Route::group(['prefix' => 'bonus-department'], function () {
            //Route::get('view/{typeId?}', ['as' => 'qc.ad3d.system.bonus_department.view.get', 'uses' => 'Ad3d\System\BonusDepartment\BonusDepartmentController@view']);

            //sửa
            //Route::get('edit/{typeId?}', ['as' => 'qc.ad3d.system.bonus_department.edit.get', 'uses' => 'Ad3d\System\PunishType\PunishTypeController@getEdit']);
            //Route::post('edit/{typeId?}', ['as' => 'qc.ad3d.system.bonus_department.post.get', 'uses' => 'Ad3d\System\PunishType\PunishTypeController@postEdit']);

            //thêm mới
            Route::get('add/{departmentId?}/{rankId?}', ['as' => 'qc.ad3d.system.bonus_department.add.get', 'uses' => 'Ad3d\System\BonusDepartment\BonusDepartmentController@getAdd']);
            Route::post('add/{departmentId?}/{rankId?}', ['as' => 'qc.ad3d.system.bonus_department.add.post', 'uses' => 'Ad3d\System\BonusDepartment\BonusDepartmentController@postAdd']);
            # cap nhat tran thai ap dung thuong
            Route::get('update-apply-bonus/{bonusId?}/{applyBonusStatus?}', ['as' => 'qc.ad3d.system.bonus_department.apply_bonus.update', 'uses' => 'Ad3d\System\BonusDepartment\BonusDepartmentController@updateApplyBonus']);
            # cap nhat tran thai ap dung phat
            Route::get('update-apply-minus/{bonusId?}/{applyMinusStatus?}', ['as' => 'qc.ad3d.system.bonus_department.apply_minus.update', 'uses' => 'Ad3d\System\BonusDepartment\BonusDepartmentController@updateApplyMinus']);
            //xóa
            Route::get('delete/{bonusId?}', ['as' => 'qc.ad3d.system.bonus_department.delete', 'uses' => 'Ad3d\System\BonusDepartment\BonusDepartmentController@deleteInfo']);

            Route::get('/', ['as' => 'qc.ad3d.system.bonus_department.get', 'uses' => 'Ad3d\System\BonusDepartment\BonusDepartmentController@index']);
        });
        // phat
        Route::group(['prefix' => 'punish-type'], function () {
            Route::get('view/{typeId?}', ['as' => 'qc.ad3d.system.punish-type.view.get', 'uses' => 'Ad3d\System\PunishType\PunishTypeController@view']);

            //sửa
            Route::get('edit/{typeId?}', ['as' => 'qc.ad3d.system.punish-type.edit.get', 'uses' => 'Ad3d\System\PunishType\PunishTypeController@getEdit']);
            Route::post('edit/{typeId?}', ['as' => 'qc.ad3d.system.punish-type.post.get', 'uses' => 'Ad3d\System\PunishType\PunishTypeController@postEdit']);

            //thêm mới
            Route::get('add', ['as' => 'qc.ad3d.system.punish-type.add.get', 'uses' => 'Ad3d\System\PunishType\PunishTypeController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.punish-type.add.post', 'uses' => 'Ad3d\System\PunishType\PunishTypeController@postAdd']);

            //xóa
            Route::get('delete/{typeId?}', ['as' => 'qc.ad3d.system.punish-type.delete', 'uses' => 'Ad3d\System\PunishType\PunishTypeController@deleteInfo']);

            Route::get('/', ['as' => 'qc.ad3d.system.punish-type.get', 'uses' => 'Ad3d\System\PunishType\PunishTypeController@index']);
        });
        Route::group(['prefix' => 'punish-content'], function () {
            Route::get('view/{typeId?}', ['as' => 'qc.ad3d.system.punish-content.view.get', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@view']);

            //sửa
            Route::get('edit/{typeId?}', ['as' => 'qc.ad3d.system.punish-content.edit.get', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@getEdit']);
            Route::post('edit/{typeId?}', ['as' => 'qc.ad3d.system.punish-content.post.get', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@postEdit']);

            //thêm mới
            Route::get('add', ['as' => 'qc.ad3d.system.punish_content.add.get', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.punish_content.add.post', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@postAdd']);

            # cap nhat trang thai ap dụng
            Route::get('update-apply-status/{punishId?}/{applyStatus?}', ['as' => 'qc.ad3d.system.punish_content.apply_status.update', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@updateApplyStatus']);

            //xao
            Route::get('delete/{typeId?}', ['as' => 'qc.ad3d.system.punish-content.delete', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@deleteInfo']);

            Route::get('/{punishTypeId?}', ['as' => 'qc.ad3d.system.punish-content.get', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@index']);
        });
    });
    //don hang
    Route::group(['prefix' => 'order', 'middleware' => 'Ad3dMiddleware'], function () {
        //quan ly don hang
        Route::group(['prefix' => 'order'], function () {
            #loc don hang
            Route::group(['prefix' => 'filter'], function () {
                // loc ten don hang
                Route::get('order-name/{name?}', ['as' => 'qc.ad3d.work.orders.filter.order.check.name', 'uses' => 'Ad3d\Order\Order\OrderController@filterCheckOrderName']);
                // loc theo ten khach hang
                Route::get('customer-name/{name?}', ['as' => 'qc.ad3d.work.orders.filter.customer.check.name', 'uses' => 'Ad3d\Order\Order\OrderController@filterCheckCustomerName']);
            });
            # thong tin don hang
            Route::get('detail/{orderId?}', ['as' => 'qc.ad3d.order.order.detail.get', 'uses' => 'Ad3d\Order\Order\OrderController@detail']);

            #xem anh thiet ke
            Route::get('view-product-design/{designId?}', ['as' => 'qc.ad3d.order.order.product.design.view', 'uses' => 'Ad3d\Order\Order\OrderController@viewProductDesign']);
            #xem anh bao cao
            Route::get('view-work-allocation-report-image/{imageId?}', ['as' => 'qc.ad3d.order.order.work_allocation_report_image.view', 'uses' => 'Ad3d\Order\Order\OrderController@viewWorkAllocationReportImage']);
            #xem anh bao cao
            Route::get('view-work-allocation-report-timekeeping-image/{imageId?}', ['as' => 'qc.ad3d.order.order.work_allocation_report_timekeeping.image.view', 'uses' => 'Ad3d\Order\Order\OrderController@viewWorkAllocationReportTimekeepingImage']);

            # thong tin khach hang
            Route::get('view-customer/{customerId?}', ['as' => 'qc.ad3d.order.order.view_customer.get', 'uses' => 'Ad3d\Order\Order\OrderController@viewCustomer']);

            # in don hang
            Route::get('print/{orderId?}', ['as' => 'qc.ad3d.order.order.print.get', 'uses' => 'Ad3d\Order\Order\OrderController@printOrder']);

            # in nghiem thu
            Route::get('print/confirm/{orderId?}', ['as' => 'qc.ad3d.order.order.confirm.print.get', 'uses' => 'Ad3d\Order\Order\OrderController@printConfirmOrder']);

            # kiem tra khach hang theo so dien thoai
            Route::get('customer/{phone?}', ['as' => 'qc.ad3d.order.order.customer.check.phone', 'uses' => 'Ad3d\Order\Order\OrderController@checkPhoneCustomer']);

            #--------- ban giao don hang - cong trinh ---------
            # xem chi tiet thi cong
            Route::get('view-work-allocation/{allocationId?}', ['as' => 'qc.ad3d.order.order.work_allocation.get', 'uses' => 'Ad3d\Order\Order\OrderController@viewWorkAllocation']);
            # xem chi tiết ảnh báo cáo
            Route::get('view-report-image/{imageId?}', ['as' => 'qc.ad3d.order.order.allocation.report_image.get', 'uses' => 'Ad3d\Order\Order\OrderController@viewReportImage']);

            Route::get('construction/{orderId?}', ['as' => 'qc.ad3d.order.order.construction.get', 'uses' => 'Ad3d\Order\Order\OrderController@getOrderConstruction']);
            Route::post('construction/add/{orderId?}', ['as' => 'qc.ad3d.order.order.construction.add.post', 'uses' => 'Ad3d\Order\Order\OrderController@postOrderConstruction']);
            # huy ban giao
            Route::get('delete-construction/{allocationId?}', ['as' => 'qc.ad3d.order.order.construction.delete', 'uses' => 'Ad3d\Order\Order\OrderController@deleteOrderConstruction']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{paymentStatus?}/{orderFilterName?}/{orderCustomerFilterName?}/{staffFilterName?}/{orderSelectedId?}', ['as' => 'qc.ad3d.order.order.get', 'uses' => 'Ad3d\Order\Order\OrderController@index']);

        });
        Route::group(['prefix' => 'allocation'], function () {

            # xac nhan hoan thanh CT ban giao
            Route::get('confirm/{allocationId?}', ['as' => 'qc.ad3d.order.allocation.confirm.get', 'uses' => 'Ad3d\Order\Allocation\OrderAllocationController@getConfirm']);
            Route::post('confirm/{allocationId?}', ['as' => 'qc.ad3d.order.allocation.confirm.post', 'uses' => 'Ad3d\Order\Allocation\OrderAllocationController@postConfirm']);
            # huy ban giao
            Route::get('cancel/{allocationId?}', ['as' => 'qc.ad3d.order.allocation.cancel.get', 'uses' => 'Ad3d\Order\Allocation\OrderAllocationController@cancel']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{finishStatus?}/{nameFiler?}', ['as' => 'qc.ad3d.order.allocation.get', 'uses' => 'Ad3d\Order\Allocation\OrderAllocationController@index']);

        });

        //san pham
        Route::group(['prefix' => 'product'], function () {
            Route::get('view/{productId?}', ['as' => 'qc.ad3d.order.product.view.get', 'uses' => 'Ad3d\Order\Product\ProductController@view']);

            // phân việc
            Route::get('allocation/add/staff/{productId?}', ['as' => 'qc.ad3d.order.product.work-allocation.staff.get', 'uses' => 'Ad3d\Order\Product\ProductController@getAddStaff']);
            Route::get('allocation/add/{productId?}', ['as' => 'qc.ad3d.order.product.work-allocation.add.get', 'uses' => 'Ad3d\Order\Product\ProductController@getAddWorkAllocation']);
            Route::post('allocation/add/{productId?}', ['as' => 'qc.ad3d.order.product.work-allocation.add.post', 'uses' => 'Ad3d\Order\Product\ProductController@postAddWorkAllocation']);

            //xac nhan hoan thanh san
            Route::get('confirm/{productId?}', ['as' => 'qc.ad3d.order.product.confirm.get', 'uses' => 'Ad3d\Order\Product\ProductController@getConfirm']);
            Route::post('confirm/{productId?}', ['as' => 'qc.ad3d.order.product.confirm.post', 'uses' => 'Ad3d\Order\Product\ProductController@postConfirm']);

            Route::get('add', ['as' => 'qc.ad3d.order.product.add.get', 'uses' => 'Ad3d\Order\Product\ProductController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.order.product.add.post', 'uses' => 'Ad3d\Order\Product\ProductController@postAdd']);
            //huy bo san pham
            Route::get('delete/{productId?}', ['as' => 'qc.ad3d.order.product.delete', 'uses' => 'Ad3d\Order\Product\ProductController@cancelProduct']);
            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{paymentStatus?}/{keywordFilter?}', ['as' => 'qc.ad3d.order.product.get', 'uses' => 'Ad3d\Order\Product\ProductController@index']);

        });
        //loai san pham
        Route::group(['prefix' => 'product-type'], function () {
            Route::get('view/{typeId?}', ['as' => 'qc.ad3d.order.product_type.view.get', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@view']);

            //sua
            Route::get('edit/{typeId?}', ['as' => 'qc.ad3d.order.product_type.edit.get', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@getEdit']);
            Route::post('edit/{typeId?}', ['as' => 'qc.ad3d.order.product_type.edit.post', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@postEdit']);

            #them moi
            Route::get('add', ['as' => 'qc.ad3d.order.product_type.add.get', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.order.product_type.add.post', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@postAdd']);

            # them anh mau
            Route::get('add/img/{typeId?}', ['as' => 'qc.ad3d.order.product_type_img.add.get', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@getAddImage']);
            Route::post('add/img/{typeId?}', ['as' => 'qc.ad3d.order.product_type_img.add.post', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@postAddImage']);
            //xem anh
            Route::get('view/img/{imageId?}', ['as' => 'qc.ad3d.order.product_type_img.view', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@viewImage']);
            //xoa anh
            Route::get('delete/img/{imageId?}', ['as' => 'qc.ad3d.order.product_type_img.delete', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@deleteImage']);

            # duyet loai san pham
            Route::get('confirm/{typeId?}', ['as' => 'qc.ad3d.order.product_type.confirm.get', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@getConfirm']);
            Route::post('confirm/{typeId?}', ['as' => 'qc.ad3d.order.product_type.confirm.post', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@postConfirm']);

            //xoa
            Route::get('delete/{typeId?}', ['as' => 'qc.ad3d.order.product_type.delete', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@deleteType']);

            Route::get('/', ['as' => 'qc.ad3d.order.product-type.get', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@index']);
        });
        //bang gia loai SP
        Route::group(['prefix' => 'product-type-price'], function () {
            # sao chep bang gia
            Route::get('copy/{companySelectedId?}', ['as' => 'qc.ad3d.order.product_type_price.copy.get', 'uses' => 'Ad3d\Order\ProductTypePrice\ProductTypePriceController@getCopyPrice']);
            Route::post('copy', ['as' => 'qc.ad3d.order.product_type_price.copy.post', 'uses' => 'Ad3d\Order\ProductTypePrice\ProductTypePriceController@postCopyPrice']);

            # them bang gia
            Route::get('add', ['as' => 'qc.ad3d.order.product_type_price.add.get', 'uses' => 'Ad3d\Order\ProductTypePrice\ProductTypePriceController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.order.product_type_price.add.post', 'uses' => 'Ad3d\Order\ProductTypePrice\ProductTypePriceController@postAdd']);

            //cap nhat gia
            Route::get('edit/{priceId?}', ['as' => 'qc.ad3d.order.product_type_price.edit.get', 'uses' => 'Ad3d\Order\ProductTypePrice\ProductTypePriceController@getEdit']);
            Route::post('edit/{priceId?}', ['as' => 'qc.ad3d.order.product_type_price.edit.post', 'uses' => 'Ad3d\Order\ProductTypePrice\ProductTypePriceController@postEdit']);

            # xoa bang gia
            Route::get('delete/{priceId?}', ['as' => 'qc.ad3d.order.product_type_price.delete', 'uses' => 'Ad3d\Order\ProductTypePrice\ProductTypePriceController@deletePrice']);

            Route::get('/{companyId?}/{name?}', ['as' => 'qc.ad3d.order.product_type_price.get', 'uses' => 'Ad3d\Order\ProductTypePrice\ProductTypePriceController@index']);
        });
    });

    Route::get('/', ['as' => 'qc.ad3d', 'uses' => 'Ad3d\Home\HomeController@index'])->middleware('Ad3dMiddleware');
});

//========== ======== Front-end =========== ========
# đang ky thanh vien
Route::group(['prefix' => 'tuyendung'], function () { //recruitment
    Route::group(['prefix' => 'dang-ky'], function () { // register
        Route::get('add/{companyId?}/{phone?}/{departmentSelectedId?}', ['as' => 'qc.work.recruitment.register.add.get', 'uses' => 'Work\Recruitment\Register\RegisterController@getAdd']);
        Route::post('add/{companyId?}', ['as' => 'qc.work.recruitment.register.add.post', 'uses' => 'Work\Recruitment\Register\RegisterController@postAdd']);
        //Route::get('/', ['as' => 'qc.work.recruitment.register.get', 'uses' => 'Work\Recruitment\Register\RegisterController@index']);
    });
    # dang nhap / dang ky
    Route::get('dang-nhap/{companyId?}', ['as' => 'qc.work.recruitment.login.get', 'uses' => 'Work\Recruitment\Recruitment\RecruitmentController@getLogin']);
    Route::post('dang-nhap/{companyId?}', ['as' => 'qc.work.recruitment.login.post', 'uses' => 'Work\Recruitment\Recruitment\RecruitmentController@postLogin']);
    # chi tiet ho so
    Route::get('/{jobApplicationId?}', ['as' => 'qc.work.recruitment.info.get', 'uses' => 'Work\Recruitment\Recruitment\RecruitmentController@index']);
    //Route::get('/', ['as' => 'qc.work.recruitment.register.get', 'uses' => 'Work\Recruitment\Register\RegisterController@index']);
});

//đăng nhập
Route::get('work-login/', ['as' => 'qc.work.login.get', 'uses' => 'Work\WorkController@getLogin']);
Route::post('work-login/', ['as' => 'qc.work.login.post', 'uses' => 'Work\WorkController@login']);
# noi quy lam viec
Route::get('work-rules/{companyId?}', ['as' => 'qc.work.rules', 'uses' => 'Work\WorkController@rules']);
# trang chinh
Route::group(['prefix' => 'work', 'middleware' => 'CheckWorkLogin'], function () {
    //thoát
    Route::get('logout', ['as' => 'qc.work.logout.get', 'uses' => 'Work\WorkController@logout']);

    # nha mua vat tu
    Route::group(['prefix' => 'import'], function () {
        // xem chi tiết
        Route::get('view/{importId?}', ['as' => 'qc.work.import.view.get', 'uses' => 'Work\Import\ImportController@viewImport']);
        # lay form nhan vat tu
        Route::get('add', ['as' => 'qc.work.import.add.get', 'uses' => 'Work\Import\ImportController@getAdd']);
        # them hinh anh hoa don
        ////Route::get('add/image', ['as' => 'qc.work.import.add.image.get', 'uses' => 'Work\Import\ImportController@getAddImage']);
        # them vat tu / dung cu
        Route::get('add/object', ['as' => 'qc.work.import.add.object.get', 'uses' => 'Work\Import\ImportController@getAddObject']);
        # kiem tra goi y nhap
        Route::get('check-import/{name?}', ['as' => 'qc.work.import.check_name.get', 'uses' => 'Work\Import\ImportController@checkImportName']);
        # them
        Route::post('add', ['as' => 'qc.work.import.add.post', 'uses' => 'Work\Import\ImportController@postAdd']);

        // xác nhận thanh toán
        Route::get('confirm-pay/{importId?}', ['as' => 'qc.work.import.confirm_pay.get', 'uses' => 'Work\Import\ImportController@getConfirmPay']);
        # cap nhat anh hoa don
        Route::get('update-image/{importId?}', ['as' => 'qc.work.import.image.update.get', 'uses' => 'Work\Import\ImportController@getUpdateImage']);
        Route::post('update-image/{importId?}', ['as' => 'qc.work.import.image.update.post', 'uses' => 'Work\Import\ImportController@postUpdateImage']);
        # Huy hoa don
        Route::get('delete/{importId?}', ['as' => 'qc.work.import.delete.get', 'uses' => 'Work\Import\ImportController@deleteImport']);

        Route::get('/{dayFilter?}/{monthFilter?}/{yearFilter?}/{payStatusFilter?}', ['as' => 'qc.work.import.get', 'uses' => 'Work\Import\ImportController@index']);
    });
    //quản lý đơn hàng
    Route::group(['prefix' => 'orders'], function () {
        # chi tiet thi cong don hang
        Route::group(['prefix' => 'construction-detail'], function () {
            #chi tiet thi cong
            Route::get('detail/{allocationId?}', ['as' => 'qc.work.orders.construction.detail.report.view', 'uses' => 'Work\Orders\OrdersController@getDetailAllocation']);

            #xem anh bao cao truc tiep
            Route::get('report-image-direct/{imageId?}', ['as' => 'qc.work.orders.construction.detail.report_image_direct.view', 'uses' => 'Work\Orders\OrdersController@viewReportImageDirect']);
            #xem anh bao cao qua cham cong
            Route::get('report-image-timekeeping/{imageId?}', ['as' => 'qc.work.orders.construction.detail.report_image_timekeeping.view', 'uses' => 'Work\Orders\OrdersController@viewReportImageTimekeeping']);

            # bao sua chua san pham
            Route::get('repair/{productId?}', ['as' => 'qc.work.orders.construction.detail.repair.get', 'uses' => 'Work\Orders\OrdersController@getRepairProduct']);
            Route::post('repair/{productId?}', ['as' => 'qc.work.orders.construction.detail.repair.post', 'uses' => 'Work\Orders\OrdersController@postRepairProduct']);

            Route::get('/{orderId?}', ['as' => 'qc.work.orders.construction.detail.get', 'uses' => 'Work\Orders\OrdersController@getOrderConstruction']);
        });
        Route::group(['prefix' => 'info'], function () {
            #thay doi thong tin khach hang
            Route::post('customer-edit/{customerId?}', ['as' => 'qc.work.orders.info.customer.edit.post', 'uses' => 'Work\Orders\OrdersController@postEditInfoCustomer']);
            #thay doi thong tin don hang
            Route::post('order-edit/{orderId?}', ['as' => 'qc.work.orders.info.order.edit.post', 'uses' => 'Work\Orders\OrdersController@postEditInfoOrder']);
            #thay doi thong tin thanh toan
            Route::get('pay-edit/{payId?}', ['as' => 'qc.work.orders.info.pay.edit.post', 'uses' => 'Work\Orders\OrdersController@getEditInfoPay']);
            Route::post('pay-edit/{payId?}', ['as' => 'qc.work.orders.info.pay.edit.post', 'uses' => 'Work\Orders\OrdersController@postEditInfoPay']);
            Route::get('list/{orderId?}/{notifyId?}', ['as' => 'qc.work.orders.info.get', 'uses' => 'Work\Orders\OrdersController@ordersInfo']);
        });
        #them thiet ke
        Route::group(['prefix' => 'design'], function () {
            //them thiet ke
            Route::get('/{orderId?}', ['as' => 'qc.work.orders.info.design.add.get', 'uses' => 'Work\Orders\OrdersController@getAddDesign']);
            Route::post('/{orderId?}', ['as' => 'qc.work.orders.info.design.add.post', 'uses' => 'Work\Orders\OrdersController@postAddDesign']);
            // huy thiet ke
            Route::get('delete/{orderId?}', ['as' => 'qc.work.orders.info.design.delete', 'uses' => 'Work\Orders\OrdersController@deleteDesign']);
        });
        Route::group(['prefix' => 'product'], function () {
            # thiet ke
            Route::group(['prefix' => 'design'], function () {
                # du lieu moi
                Route::get('view/{designId?}', ['as' => 'qc.work.orders.product_design.view.get', 'uses' => 'Work\Orders\OrdersController@viewProductDesign']);

                #them thiet ke san pham
                Route::get('add/{productId?}', ['as' => 'qc.work.orders.product.design.add.get', 'uses' => 'Work\Orders\OrdersController@getAddProductDesign']);
                # them thiet ke thi cong
                Route::get('add/construction/{productId?}', ['as' => 'qc.work.orders.product.design_construction.add.get', 'uses' => 'Work\Orders\OrdersController@getAddProductDesignConstruction']);
                Route::post('add/{productId?}', ['as' => 'qc.work.orders.product.design.add.post', 'uses' => 'Work\Orders\OrdersController@postAddProductDesign']);

                #xac nhan su dung thiet ke
                Route::get('confirm-apply/{designId?}/{applyStatus?}', ['as' => 'qc.work.orders.product.design.apply.get', 'uses' => 'Work\Orders\OrdersController@getConfirmApplyDesign']);
                Route::post('confirm-apply/{designId?}/{applyStatus?}', ['as' => 'qc.work.orders.product.design.apply.post', 'uses' => 'Work\Orders\OrdersController@postConfirmApplyDesign']);

                # huy thiet ke
                Route::get('cancel/{designId?}', ['as' => 'qc.work.orders.product.design.cancel', 'uses' => 'Work\Orders\OrdersController@cancelProductDesign']);
                #thiet ke thi cong
                Route::get('construction/{productId?}', ['as' => 'qc.work.orders.product.design_construction.get', 'uses' => 'Work\Orders\OrdersController@getDesignConstruction']);

                #thiet ke san pham
                Route::get('/{productId?}', ['as' => 'qc.work.orders.product.design.get', 'uses' => 'Work\Orders\OrdersController@getDesign']);
            });

            #thay do thong tin san phan
            Route::group(['prefix' => 'info'], function () {
                Route::get('edit/{productId?}', ['as' => 'qc.work.orders.orders.product.info.edit.get', 'uses' => 'Work\Orders\OrdersController@getProductInfoEdit']);
                Route::post('edit/{productId?}', ['as' => 'qc.work.orders.orders.product.info.edit.post', 'uses' => 'Work\Orders\OrdersController@postProductInfoEdit']);
            });

            #xac nhan don hang
            Route::get('confirm/{productId?}', ['as' => 'qc.work.orders.product.confirm.get', 'uses' => 'Work\Orders\OrdersController@getProductConfirm']);
            Route::post('confirm/{productId?}', ['as' => 'qc.work.orders.product.confirm.post', 'uses' => 'Work\Orders\OrdersController@postProductConfirm']);

            # huy san pham
            Route::group(['prefix' => 'cancel'], function () {
                Route::get('/{orderId?}', ['as' => 'qc.work.orders.orders.product_cancel.get', 'uses' => 'Work\Orders\OrdersController@getProductCancel']);
                Route::post('/{orderId}', ['as' => 'qc.work.orders.orders.product_cancel.post', 'uses' => 'Work\Orders\OrdersController@postProductCancel']);
            });

            Route::get('list/{orderId?}', ['as' => 'qc.work.orders.product.list.get', 'uses' => 'Work\Orders\OrdersController@productList']);
        });

        # xem chi tiết
        Route::get('view/{orderId?}', ['as' => 'qc.work.orders.view.get', 'uses' => 'Work\Orders\OrdersController@viewOrders']);

        # xem chi tiết khach hang
        Route::get('view-customer/{customerId?}', ['as' => 'qc.work.orders.view.customer.get', 'uses' => 'Work\Orders\OrdersController@viewCustomer']);

        # xem chi tiet thanh toan
        Route::get('view-order-pay/{orderId?}', ['as' => 'qc.work.orders.order_pay.view.get', 'uses' => 'Work\Orders\OrdersController@viewOrderPay']);

        # in don hang
        Route::get('print/{orderId?}', ['as' => 'qc.work.orders.print.get', 'uses' => 'Work\Orders\OrdersController@printOrders']);

        # in nghiem thu
        Route::get('print-confirm/{orderId?}', ['as' => 'qc.work.orders.print_confirm.get', 'uses' => 'Work\Orders\OrdersController@printOrderConfirm']);


        # them don hang moi va them san pham cho don hang
        Route::group(['prefix' => 'add'], function () {
            # kiem tra khach hang theo so dien thoai
            Route::get('customer-phone/{phone?}', ['as' => 'qc.work.orders.add.customer.check.phone', 'uses' => 'Work\Orders\OrdersController@checkCustomerPhone']);

            # kiem tra khach hang theo so dien thoai
            Route::get('customer-name/{name?}', ['as' => 'qc.work.orders.add.customer.check.name', 'uses' => 'Work\Orders\OrdersController@checkCustomerName']);

            # kiem tra goi y thong tin don hang
            Route::get('order-name/{name?}', ['as' => 'qc.work.orders.add.order.check.name', 'uses' => 'Work\Orders\OrdersController@checkOrderName']);

            # kiem tra goi y thong loai san pham
            Route::get('product-type/{name?}', ['as' => 'qc.work.orders.add.product_type.check.name', 'uses' => 'Work\Orders\OrdersController@checkProductType']);
            # huy san pham
            Route::get('cancel/product/{key?}', ['as' => 'qc.work.orders.add.product.cancel.get', 'uses' => 'Work\Orders\OrdersController@cancelAddProduct']);

            #them san pham
            Route::get('/product', ['as' => 'qc.work.orders.add.product.get', 'uses' => 'Work\Orders\OrdersController@addProduct']);
            Route::get('/{type?}/{customerId?}/{orderId?}', ['as' => 'qc.work.orders.add.get', 'uses' => 'Work\Orders\OrdersController@getAdd']);

            // them don hang chinh thuc
            Route::post('add', ['as' => 'work.orders.add.post', 'uses' => 'Work\Orders\OrdersController@postAdd']);

            // them don hang tam
            Route::post('add/provisional', ['as' => 'work.orders.add.provisional.post', 'uses' => 'Work\Orders\OrdersController@postAddProvisional']);
        });

        #cap nhan san pham
        Route::group(['prefix' => 'edit'], function () {
            # sua don hang
            Route::get('/product', ['as' => 'qc.work.orders.edit.product.get', 'uses' => 'Work\Orders\OrdersController@editAddProduct']);
            Route::get('/{orderId}', ['as' => 'work.orders.edit.addProduct.get', 'uses' => 'Work\Orders\OrdersController@getEditAddProduct']);
            Route::post('/{orderId}', ['as' => 'work.orders.edit.addProduct.post', 'uses' => 'Work\Orders\OrdersController@postEditAddProduct']);
        });

        #thanh toan don hang
        Route::group(['prefix' => 'payment'], function () {
            Route::get('add/{orderId?}', ['as' => 'qc.work.orders.payment.get', 'uses' => 'Work\Orders\OrdersController@getPayment']);
            Route::post('add/{orderId?}', ['as' => 'qc.work.orders.payment.post', 'uses' => 'Work\Orders\OrdersController@postPayment']);
        });

        # huy don hang
        Route::group(['prefix' => 'delete'], function () {
            Route::get('/{orderId?}', ['as' => 'qc.work.orders.order.delete.get', 'uses' => 'Work\Orders\OrdersController@getOrderCancel']);
            Route::post('/{orderId}', ['as' => 'qc.work.orders.order.delete.post', 'uses' => 'Work\Orders\OrdersController@postOrderCancel']);
        });

        #bao gia
        Route::group(['prefix' => 'provisional'], function () {
            #loc don hang
            Route::group(['prefix' => 'filter'], function () {
                // loc ten don hang
                Route::get('order-name/{name?}', ['as' => 'qc.work.provisional.orders.filter.order.check.name', 'uses' => 'Work\Orders\OrdersProvisionalController@filterCheckOrderName']);
                // loc theo ten khach hang
                Route::get('customer-name/{name?}', ['as' => 'qc.work.provisional.orders.filter.customer.check.name', 'uses' => 'Work\Orders\OrdersProvisionalController@filterCheckCustomerName']);
            });
            # xem chi tiết
            Route::get('view/{orderId?}', ['as' => 'qc.work.orders.provisional.view.get', 'uses' => 'Work\Orders\OrdersProvisionalController@viewOrders']);
            # in bao gia
            Route::get('print/{orderId?}', ['as' => 'qc.work.orders.provisional.print.get', 'uses' => 'Work\Orders\OrdersProvisionalController@printOrders']);
            # xem chi tiết khach hang
            Route::get('view-customer/{customerId?}', ['as' => 'qc.work.orders.provisional.view.customer.get', 'uses' => 'Work\Orders\OrdersProvisionalController@viewCustomer']);

            # xac nhan dat hang
            Route::get('confirm/{orderId?}', ['as' => 'qc.work.orders.provisional.confirm.get', 'uses' => 'Work\Orders\OrdersProvisionalController@getConfirm']);
            Route::post('confirm/{orderId?}', ['as' => 'qc.work.orders.provisional.confirm.post', 'uses' => 'Work\Orders\OrdersProvisionalController@postConfirm']);

            # huy dn hang
            Route::get('delete/{orderId?}', ['as' => 'qc.work.orders.provisional.cancel.get', 'uses' => 'Work\Orders\OrdersProvisionalController@cancelOrders']);

            Route::get('/{loginMonth?}/{loginYear?}/{orderFilterName?}/{orderCustomerFilterName?}', ['as' => 'qc.work.orders.provisional.get', 'uses' => 'Work\Orders\OrdersProvisionalController@index']);
        });
        # quan ly don hang toan hen
        Route::group(['prefix' => 'manage'], function () {
            Route::get('/{finishStatus?}/{monthFilter?}/{yearFilter?}/{paymentStatus?}/{orderFilterName?}/{orderCustomerFilterName?}', ['as' => 'qc.work.orders.manage.get', 'uses' => 'Work\Orders\OrdersManageController@index']);
        });

        #loc don hang
        Route::group(['prefix' => 'filter'], function () {
            // loc ten don hang
            Route::get('order-name/{name?}', ['as' => 'qc.work.orders.filter.order.check.name', 'uses' => 'Work\Orders\OrdersController@filterCheckOrderName']);
            // loc theo ten khach hang
            Route::get('customer-name/{name?}', ['as' => 'qc.work.orders.filter.customer.check.name', 'uses' => 'Work\Orders\OrdersController@filterCheckCustomerName']);
        });

        #------ -----Bao hoan thanh ------- -------
        Route::group(['prefix' => 'report'], function () {
            # sua don hang
            Route::get('finish/{orderId}', ['as' => 'work.orders.order.report.finish.get', 'uses' => 'Work\Orders\OrdersController@getReportFinish']);
            Route::post('finish/{orderId}', ['as' => 'work.orders.order.report.finish.post', 'uses' => 'Work\Orders\OrdersController@postReportFinish']);
        });

        Route::get('/{finishStatus?}/{loginMonth?}/{loginYear?}/{paymentStatus?}/{orderFilterName?}/{customerName?}/{staffFilterId?}', ['as' => 'qc.work.orders.get', 'uses' => 'Work\Orders\OrdersController@index']);
    });

    //quản lý thu chi
    Route::group(['prefix' => 'money'], function () {
        # tien thu chua ban giao
        Route::group(['prefix' => 'receive'], function () {
            Route::get('delete/{payId?}', ['as' => 'qc.work.money.receive.delete.get', 'uses' => 'Work\Money\Receive\MoneyReceiveController@deleteOrderPay']);
            # giao tien
            Route::post('transfer', ['as' => 'qc.work.money.receive.transfer.post', 'uses' => 'Work\Money\Receive\MoneyReceiveController@postTransfer']);
            Route::get('/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.money.receive.get', 'uses' => 'Work\Money\Receive\MoneyReceiveController@index']);
        });

        # thong tin chi
        Route::group(['prefix' => 'payment'], function () {
            Route::get('/{object?}/{dateFilter?}/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.money.payment.get', 'uses' => 'Work\Money\Payment\PaymentController@index']);
        });

        # tien thu chua ban giao
        Route::group(['prefix' => 'history'], function () {
            Route::get('receive/{dateFilter?}/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.money.history.receive.get', 'uses' => 'Work\Money\History\MoneyHistoryController@historyReceive']);
        });

        # giao tien
        Route::group(['prefix' => 'transfer'], function () {
            Route::group(['prefix' => 'transfer'], function () {
                # thong tin giao tien
                Route::get('view/{transferId?}', ['as' => 'qc.work.money.transfer.transfer.view', 'uses' => 'Work\Money\Transfer\MoneyTransferController@transferView']);
                # thong tin chuyen
                Route::get('info/{transferId?}', ['as' => 'qc.work.money.transfer.transfer.info.get', 'uses' => 'Work\Money\Transfer\MoneyTransferController@transferInfo']);

                # huy chuyen 1 chi tiet giao dich
                Route::get('info-delete/{detailId?}', ['as' => 'qc.work.money.transfer.transfer.info.delete', 'uses' => 'Work\Money\Transfer\MoneyTransferController@transferDetailDelete']);

                # huy 1 giao dich
                Route::get('cancel/{transferId?}', ['as' => 'qc.work.money.transfer.transfer.cancel.get', 'uses' => 'Work\Money\Transfer\MoneyTransferController@transferCancel']);

                Route::get('/{loginDay?}/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.money.transfer.transfer.get', 'uses' => 'Work\Money\Transfer\MoneyTransferController@transferIndex']);
            });

            # thong tin nhan tien
            Route::group(['prefix' => 'receive'], function () {
                Route::get('confirm/{transferId?}', ['as' => 'qc.work.money.transfer.receive.confirm.get', 'uses' => 'Work\Money\Transfer\MoneyTransferController@receiveConfirm']);
                Route::get('view/{transferId?}', ['as' => 'qc.work.money.transfer.receive.view', 'uses' => 'Work\Money\Transfer\MoneyTransferController@receiveView']);
                Route::get('/{dateFilter?}/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.money.transfer.receive.get', 'uses' => 'Work\Money\Transfer\MoneyTransferController@receiveIndex']);
            });
        });

        # thong ke
        Route::group(['prefix' => 'statistical'], function () {
            # nop tien cho thu quy cap quan ly
            Route::get('transfers/', ['as' => 'qc.work.money.statistical.transfers.get', 'uses' => 'Work\Money\Statistical\MoneyStatisticalController@getTransfers']);
            Route::post('transfers/', ['as' => 'qc.work.money.statistical.transfers.post', 'uses' => 'Work\Money\Statistical\MoneyStatisticalController@postTransfers']);

            Route::get('/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.money.statistical.get', 'uses' => 'Work\Money\Statistical\MoneyStatisticalController@index']);
        });
    });

    //phân công làm việc
    Route::group(['prefix' => 'work-allocation'], function () {
        // danh sach thi cong don hang cua he thong
        Route::group(['prefix' => 'orders'], function () {
            # phan viec tren san pham
            Route::group(['prefix' => 'product'], function () {
                # xem anh thiet ke
                Route::get('view-design-image/{imageId?}', ['as' => 'qc.work.work_allocation.order.allocation.product.design_image.view', 'uses' => 'Work\WorkAllocation\Orders\OrderController@viewProductDesignImage']);
                # phan viec
                Route::get('allocation/add/staff/{productId?}', ['as' => 'qc.work.work_allocation.order.product.work-allocation.staff.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@getAddStaff']);
                Route::get('allocation/add/{productId?}', ['as' => 'qc.work.work_allocation.order.product.work-allocation.add.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@getAddWorkAllocation']);
                Route::post('allocation/add/{productId?}', ['as' => 'qc.work.work_allocation.order.product.work-allocation.add.post', 'uses' => 'Work\WorkAllocation\Orders\OrderController@postAddWorkAllocation']);

                #huy ban giao cong viec
                Route::get('allocation/cancel/{allocationId?}', ['as' => 'qc.work.work_allocation.order.product.work-allocation.cancel.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@cancelWorkAllocationProduct']);

                # xem chi tiet thi cong
                Route::get('view-work-allocation/{allocationId?}', ['as' => 'qc.work.work_allocation.order.work_allocation.report.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@viewWorkAllocation']);

                #xem anh bao cao truc tiep
                Route::get('report-image-direct/{imageId?}', ['as' => 'qc.work.work_allocation.order.allocation.product.report_image_direct.view', 'uses' => 'Work\WorkAllocation\Orders\OrderController@viewReportImageDirect']);
                #xem anh bao cao qua cham cong
                Route::get('report-image-timekeeping/{imageId?}', ['as' => 'qc.work.work_allocation.order.allocation.product.report_image_timekeeping.view', 'uses' => 'Work\WorkAllocation\Orders\OrderController@viewReportImage']);
            });

            #loc don hang
            Route::group(['prefix' => 'filter'], function () {
                // loc ten don hang
                Route::get('order-name/{name?}', ['as' => 'qc.work.work_allocation.manage.filter.order.check.name', 'uses' => 'Work\WorkAllocation\WorkAllocationManageController@filterCheckOrderName']);
                // loc theo ten khach hang
                Route::get('customer-name/{name?}', ['as' => 'qc.work.work_allocation.manage.filter.customer.check.name', 'uses' => 'Work\WorkAllocation\WorkAllocationManageController@filterCheckCustomerName']);
            });
            # xem thong tin dơn hang
            Route::get('view/{orderId?}/{notifyId?}', ['as' => 'qc.work.work_allocation.order.view', 'uses' => 'Work\WorkAllocation\Orders\OrderController@viewOrder']);

            # xem chi tiet thi cong
            Route::get('view-report-work-allocation/{allocationId?}', ['as' => 'qc.work.work_allocation.order.report.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@viewWorkAllocation']);
            # in don hang
            Route::get('print/{orderId?}', ['as' => 'qc.work.work_allocation.order.print.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@printOrder']);

            # in xac nhan
            Route::get('print-confirm/{orderId?}', ['as' => 'qc.work.work_allocation.order.print_confirm.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@printConfirmOrder']);

            # quan ly - trien khai thi cong don hang
            Route::get('construction/{orderId?}/{notifyId?}', ['as' => 'qc.work.work_allocation.order.construction.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@getOrderConstruction']);
            Route::post('construction/add/{orderId?}', ['as' => 'qc.work.work_allocation.order.construction.add.post', 'uses' => 'Work\WorkAllocation\Orders\OrderController@postOrderConstruction']);

            # xac nhan hoan thanh thi cong don hang
            Route::get('confirm-finish/{allocationId?}', ['as' => 'qc.work.work_allocation.order.construction_confirm_finish.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@getConfirmFinishConstruction']);
            Route::post('confirm-finish/{allocationId?}', ['as' => 'qc.work.work_allocation.order.construction_confirm_finish.post', 'uses' => 'Work\WorkAllocation\Orders\OrderController@postConfirmFinishConstruction']);

            # huy ban giao
            Route::get('delete-construction/{allocationId?}', ['as' => 'qc.work.work_allocation.order.construction.delete', 'uses' => 'Work\WorkAllocation\Orders\OrderController@deleteOrderConstruction']);

            Route::get('/{day?}/{month?}/{year?}/{paymentStatus?}/{orderFilterName?}/{orderCustomerFilterName?}/{finishStatus?}', ['as' => 'qc.work.work_allocation.orders.index', 'uses' => 'Work\WorkAllocation\Orders\OrderController@index']);
        });

        # Phu trach don hang thi cong
        Route::group(['prefix' => 'order-allocation'], function () {
            Route::group(['prefix' => 'product'], function () {
                // xem thiet ke
                Route::get('design-view/{designId?}', ['as' => 'qc.work.work_allocation.order_allocation.product.design.view', 'uses' => 'Work\WorkAllocation\OrderAllocation\OrderAllocationController@viewProductDesign']);
                #xem anh bao cao truc tiep
                Route::get('report-image-direct/{imageId?}', ['as' => 'qc.work.work_allocation.order_allocation.product.report_image_direct.view', 'uses' => 'Work\WorkAllocation\OrderAllocation\OrderAllocationController@viewReportImageDirect']);
                #xem anh bao cao qua cham cong
                Route::get('report-image-timekeeping/{imageId?}', ['as' => 'qc.work.work_allocation.order_allocation.product.report_image_timekeeping.view', 'uses' => 'Work\WorkAllocation\OrderAllocation\OrderAllocationController@viewReportImageTimekeeping']);

                # xac nhan hoan thanh sp
                Route::get('confirm/{productId?}', ['as' => 'qc.work.work_allocation.order_allocation.product.confirm.get', 'uses' => 'Work\WorkAllocation\OrderAllocation\OrderAllocationController@getConstructionProductConfirm']);
                Route::post('confirm/{productId?}', ['as' => 'qc.work.work_allocation.order_allocation.product.confirm.post', 'uses' => 'Work\WorkAllocation\OrderAllocation\OrderAllocationController@postConstructionProductConfirm']);

                # sản phẩm
                Route::get('/{allocationId?}', ['as' => 'qc.work.work_allocation.order_allocation.product.get', 'uses' => 'Work\WorkAllocation\OrderAllocation\OrderAllocationController@constructionProduct']);
            });
            # bao cao hoan thanh thi cong
            Route::get('report-finish/{allocationId?}', ['as' => 'qc.work.work_allocation.order_allocation.report_finish.get', 'uses' => 'Work\WorkAllocation\OrderAllocation\OrderAllocationController@getConstructionReportFinish']);
            Route::post('report-finish/{allocationId?}', ['as' => 'qc.work.work_allocation.order_allocation.report_finish.post', 'uses' => 'Work\WorkAllocation\OrderAllocation\OrderAllocationController@postConstructionReportFinish']);

            Route::get('/{finishStatus?}/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.work_allocation.order_allocation.index', 'uses' => 'Work\WorkAllocation\OrderAllocation\OrderAllocationController@index']);
        });

        # danh sach san pham can sua chưa
        Route::group(['prefix' => 'product-repair'], function () {
            #loc don hang
            /* Route::group(['prefix' => 'filter'], function () {
                 // loc ten don hang
                 Route::get('order-name/{name?}', ['as' => 'qc.work.work_allocation.manage.filter.order.check.name', 'uses' => 'Work\WorkAllocation\WorkAllocationManageController@filterCheckOrderName']);
                 // loc theo ten khach hang
                 Route::get('customer-name/{name?}', ['as' => 'qc.work.work_allocation.manage.filter.customer.check.name', 'uses' => 'Work\WorkAllocation\WorkAllocationManageController@filterCheckCustomerName']);
             });*/
            # xem thong tin dơn hang
            //Route::get('view/{orderId?}/{notifyId?}', ['as' => 'qc.work.work_allocation.order.view', 'uses' => 'Work\WorkAllocation\Orders\OrderController@viewOrder']);

            # in don hang
            //Route::get('print/{orderId?}', ['as' => 'qc.work.work_allocation.order.print.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@printOrder']);

            # in xac nhan
            //Route::get('print-confirm/{orderId?}', ['as' => 'qc.work.work_allocation.order.print_confirm.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@printConfirmOrder']);

            # quan ly - trien khai thi cong don hang
            //Route::get('construction/{orderId?}/{notifyId?}', ['as' => 'qc.work.work_allocation.order.construction.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@getOrderConstruction']);
            //Route::post('construction/add/{orderId?}', ['as' => 'qc.work.work_allocation.order.construction.add.post', 'uses' => 'Work\WorkAllocation\Orders\OrderController@postOrderConstruction']);

            # xac nhan hoan thanh thi cong don hang
            //Route::get('confirm-finish/{allocationId?}', ['as' => 'qc.work.work_allocation.order.construction_confirm_finish.get', 'uses' => 'Work\WorkAllocation\Orders\OrderController@getConfirmFinishConstruction']);
            //Route::post('confirm-finish/{allocationId?}', ['as' => 'qc.work.work_allocation.order.construction_confirm_finish.post', 'uses' => 'Work\WorkAllocation\Orders\OrderController@postConfirmFinishConstruction']);

            # huy ban giao
            //Route::get('delete-construction/{allocationId?}', ['as' => 'qc.work.work_allocation.order.construction.delete', 'uses' => 'Work\WorkAllocation\Orders\OrderController@deleteOrderConstruction']);

            Route::get('/{day?}/{month?}/{year?}/{orderFilterName?}/{finishStatus?}', ['as' => 'qc.work.work_allocation.product_repair.index', 'uses' => 'Work\WorkAllocation\ProductRepair\ProductRepairController@index']);
        });
        # phan viec thi cong san pham
        Route::group(['prefix' => 'work-allocation'], function () {
            #chi tiet
            Route::get('detail/{allocationId?}', ['as' => 'qc.work.work_allocation.work_allocation.detail.get', 'uses' => 'Work\WorkAllocation\WorkAllocation\WorkAllocationController@getDetailAllocation']);
            #xem anh thiet ke
            Route::get('design-image/{imageId?}', ['as' => 'qc.work.work_allocation.work_allocation.design_image.view', 'uses' => 'Work\WorkAllocation\WorkAllocation\WorkAllocationController@viewDesignImage']);

            #xem anh bao cao truc tiep
            Route::get('report-image-direct/{imageId?}', ['as' => 'qc.work.work_allocation.work_allocation.report_image_direct.view', 'uses' => 'Work\WorkAllocation\WorkAllocation\WorkAllocationController@viewReportImageDirect']);
            #xem anh bao cao qua cham cong
            Route::get('report-image-timekeeping/{imageId?}', ['as' => 'qc.work.work_allocation.work_allocation.report_image_timekeeping.view', 'uses' => 'Work\WorkAllocation\WorkAllocation\WorkAllocationController@viewReportImage']);
            # xoa anh bap cao
            Route::get('report-image-delete/{imageId?}', ['as' => 'qc.work.work_allocation.work_allocation.report.image.delete', 'uses' => 'Work\WorkAllocation\WorkAllocation\WorkAllocationController@deleteReportImage']);
            #Hủy báo cáo
            Route::get('report/cancel/{reportId?}', ['as' => 'qc.work.work_allocation.work_allocation.report.cancel.get', 'uses' => 'Work\WorkAllocation\WorkAllocation\WorkAllocationController@cancelReport']);
            #bao cao cong viec trong ngay
            Route::get('report/{allocationId?}', ['as' => 'qc.work.work_allocation.work_allocation.report.get', 'uses' => 'Work\WorkAllocation\WorkAllocation\WorkAllocationController@getAllocationReport']);
            Route::post('report/{allocationId?}', ['as' => 'qc.work.work_allocation.work_allocation.report.post', 'uses' => 'Work\WorkAllocation\WorkAllocation\WorkAllocationController@postAllocationReport']);
            # trang chinh
            Route::get('/{finishStatus?}/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.work_allocation.work_allocation.index', 'uses' => 'Work\WorkAllocation\WorkAllocation\WorkAllocationController@index']);
        });


    });

    //chấm công
    Route::group(['prefix' => 'timekeeping'], function () {
        //thong ke trong thang hien tai
        Route::group(['prefix' => 'work'], function () {
            Route::get('/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.timekeeping.work.get', 'uses' => 'Work\Timekeeping\WorkController@index']);
        });

        //báo giờ vào
        Route::get('timeBegin/{workId?}', ['as' => 'qc.work.timekeeping.timeBegin.get', 'uses' => 'Work\Timekeeping\TimekeepingController@getTimeBegin']);
        Route::post('timeBegin/', ['as' => 'qc.work.timekeeping.timeBegin.post', 'uses' => 'Work\Timekeeping\TimekeepingController@postTimeBegin']);
        //sua gio vao
        Route::get('timeBegin-edit/{timekeepingId?}', ['as' => 'qc.work.timekeeping.timeBegin.edit.get', 'uses' => 'Work\Timekeeping\TimekeepingController@getEditTimeBegin']);
        Route::post('timeBegin-edit/{timekeepingId?}', ['as' => 'qc.work.timekeeping.timeBegin.edit.post', 'uses' => 'Work\Timekeeping\TimekeepingController@postEditTimeBegin']);

        //báo giờ ra
        Route::get('timeEnd/{timekeepingId?}', ['as' => 'qc.work.timekeeping.timeEnd.get', 'uses' => 'Work\Timekeeping\TimekeepingController@getTimeEnd']);
        Route::post('timeEnd/', ['as' => 'qc.work.timekeeping.timeEnd.post', 'uses' => 'Work\Timekeeping\TimekeepingController@postTimeEnd']);
        //sua gio ra
        Route::get('timeEnd-edit/{timekeepingId?}', ['as' => 'qc.work.timekeeping.timeEnd.edit.get', 'uses' => 'Work\Timekeeping\TimekeepingController@getEditTimeEnd']);
        Route::post('timeEnd-edit/{timekeepingId?}', ['as' => 'qc.work.timekeeping.timeEnd.edit.post', 'uses' => 'Work\Timekeeping\TimekeepingController@postEditTimeEnd']);

        //xóa ảnh xác nhận
        Route::get('image-add/{timekeepingProvisionalId?}', ['as' => 'qc.work.timekeeping.timekeeping_provisional_image.add.get', 'uses' => 'Work\Timekeeping\TimekeepingController@getTimekeepingProvisionalImage']);
        Route::post('image-add/{timekeepingProvisionalId?}', ['as' => 'qc.work.timekeeping.timekeeping_provisional_image.add.post', 'uses' => 'Work\Timekeeping\TimekeepingController@postTimekeepingProvisionalImage']);
        Route::get('image-delete/{timekeepingProvisionalImageId?}', ['as' => 'qc.work.timekeeping.timekeeping_provisional_image.delete', 'uses' => 'Work\Timekeeping\TimekeepingController@deleteTimekeepingProvisionalImage']);

        //Xin nghỉ
        Route::get('offWork/{workId?}', ['as' => 'qc.work.timekeeping.offWork.get', 'uses' => 'Work\Timekeeping\TimekeepingController@getOffWork']);
        Route::post('offWork/', ['as' => 'qc.work.timekeeping.offWork.post', 'uses' => 'Work\Timekeeping\TimekeepingController@postOffWork']);
        Route::get('offWork/cancel/{licenseId?}', ['as' => 'qc.work.timekeeping.offWork.cancel.get', 'uses' => 'Work\Timekeeping\TimekeepingController@cancelOffWork']);

        //Xin Trễ
        Route::get('lateWork/{workId?}', ['as' => 'qc.work.timekeeping.lateWork.get', 'uses' => 'Work\Timekeeping\TimekeepingController@getLateWork']);
        Route::post('lateWork/', ['as' => 'qc.work.timekeeping.lateWork.post', 'uses' => 'Work\Timekeeping\TimekeepingController@postLateWork']);
        Route::get('lateWork/cancel/{licenseId?}', ['as' => 'qc.work.timekeeping.lateWork.cancel.get', 'uses' => 'Work\Timekeeping\TimekeepingController@cancelLateWork']);

        //xem chi tiết ảnh báo cáo
        Route::get('image/{imageId?}', ['as' => 'qc.work.timekeeping_provisional.image.get', 'uses' => 'Work\Timekeeping\TimekeepingController@viewTimekeepingProvisionalImage']);

        //xem chi tiết ảnh cảnh báo
        Route::get('warning-view/{waringId?}', ['as' => 'qc.work.timekeeping_provisional.warning.view', 'uses' => 'Work\Timekeeping\TimekeepingController@viewTimekeepingProvisionalWarning']);

        //Hủy chấm công
        Route::get('cancel/{timekeepingId?}', ['as' => 'qc.work.timekeeping.cancel.get', 'uses' => 'Work\Timekeeping\TimekeepingController@cancelTimekeeping']);


        Route::get('/', ['as' => 'qc.work.timekeeping.get', 'uses' => 'Work\Timekeeping\TimekeepingController@index']);
    });
    # thong tin ca nhan ca nhan
    Route::group(['prefix' => 'staff'], function () {
        #thong ke
        Route::group(['prefix' => 'statistical'], function () {
            # cham cong
            Route::get('work/{workId?}', ['as' => 'qc.work.staff.statistical.work.get', 'uses' => 'Work\Staff\StaffController@getStatisticalWork']);
            # thong ke thuong
            Route::get('bonus/{workId?}', ['as' => 'qc.work.staff.statistical.bonus.get', 'uses' => 'Work\Staff\StaffController@getStatisticalBonus']);
            # thong ke phat
            Route::get('minus-money/{workId?}', ['as' => 'qc.work.staff.statistical.minus_money.get', 'uses' => 'Work\Staff\StaffController@getStatisticalMinus']);
            # thong ke - bo phan thi cong
            Route::get('construction/{workId?}/{constructionStatus?}', ['as' => 'qc.work.staff.statistical.construction.get', 'uses' => 'Work\Staff\StaffController@getStatisticalConstruction']);
            # thong ke - bo phan kinh doanh
            Route::get('business/{workId?}/{constructionStatus?}', ['as' => 'qc.work.staff.statistical.business.get', 'uses' => 'Work\Staff\StaffController@getStatisticalBusiness']);
            # thong ke
            Route::get('/{monthFilter?}/{yearMonth?}', ['as' => 'qc.work.staff.statistical.get', 'uses' => 'Work\Staff\StaffController@getStatistical']);
        });
        # tai khoan
        Route::group(['prefix' => 'account'], function () {
            //đổi mật khẩu
            Route::get('update', ['as' => 'qc.work.staff.account.update.get', 'uses' => 'Work\Staff\StaffController@getUpdateAccount']);
            Route::post('update', ['as' => 'qc.work.staff.account.update.post', 'uses' => 'Work\Staff\StaffController@postUpdateAccount']);
        });
        # cap nhat ky nang
        Route::get('skill-update/{companyStaffWorkId?}/{departmentWorkId?}', ['as' => 'qc.work.staff.skill.update.get', 'uses' => 'Work\Staff\StaffController@getUpdateSkill']);
        Route::post('skill-update/{companyStaffWorkId?}/{departmentWorkId?}', ['as' => 'qc.work.staff.skill.update.post', 'uses' => 'Work\Staff\StaffController@postUpdateSkill']);

        Route::get('/{staffId}', ['as' => 'qc.work.staff.index.get', 'uses' => 'Work\Staff\StaffController@index']);
    });
    // lương
    Route::group(['prefix' => 'salary'], function () {
        //giu tien tu lương
        Route::group(['prefix' => 'keep-money'], function () {
            Route::get('/{monthFilter?}/{yearFilter?}/{payStatus?}', ['as' => 'qc.work.salary.keep_money.get', 'uses' => 'Work\Salary\KeepMoney\KeepMoneyController@index']);
        });
        //luong
        Route::group(['prefix' => 'salary'], function () {
            # xac nhan da nhan luong
            Route::get('confirm/{salaryId?}', ['as' => 'qc.work.salary.salary.confirm.get', 'uses' => 'Work\Salary\Salary\SalaryController@getConfirm']);
            Route::post('confirm/{salaryId?}', ['as' => 'qc.work.salary.salary.confirm.post', 'uses' => 'Work\Salary\Salary\SalaryController@postConfirm']);

            Route::get('salary-detail/{salaryId?}', ['as' => 'qc.work.salary.salary.detail', 'uses' => 'Work\Salary\Salary\SalaryController@salaryDetail']);
            Route::get('salary/{code?}', ['as' => 'qc.work.salary.salary.get', 'uses' => 'Work\Salary\Salary\SalaryController@index']);
        });
        //ung luong
        Route::group(['prefix' => 'before-pay'], function () {
            #gui y/c ung luong
            Route::get('request', ['as' => 'qc.work.salary.before_pay.request.get', 'uses' => 'Work\Salary\BeforePay\SalaryBeforePayController@getBeforePayRequest']);
            Route::post('request/', ['as' => 'qc.work.salary.before_pay.request.post', 'uses' => 'Work\Salary\BeforePay\SalaryBeforePayController@postBeforePayRequest']);
            #huy y/c ung
            Route::get('request/delete/{requestId?}', ['as' => 'qc.work.salary.before_pay.request.delete', 'uses' => 'Work\Salary\BeforePay\SalaryBeforePayController@deleteBeforePayRequest']);
            #xac nhan da nhan tien ung luong
            Route::get('confirm/{payId}', ['as' => 'qc.work.salary.before_pay.confirm.get', 'uses' => 'Work\Salary\BeforePay\SalaryBeforePayController@confirmReceiveBeforePay']);

            Route::get('/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.salary.before_pay.get', 'uses' => 'Work\Salary\BeforePay\SalaryBeforePayController@index']);
        });
    });

    //dang ky kpi
    Route::group(['prefix' => 'kpi'], function () {
        Route::get('add-register/{kpiId?}', ['as' => 'qc.work.kpi.add_register.get', 'uses' => 'Work\Kpi\KpiController@getRegister']);
        Route::post('add-register/', ['as' => 'qc.work.kpi.add_register.post', 'uses' => 'Work\Kpi\KpiController@postRegister']);
        #huy ung
        //Route::get('request/delete/{requestId?}', ['as' => 'qc.work.kpi.delete', 'uses' => 'Work\Salary\BeforePay\SalaryBeforePayController@deleteBeforePayRequest']);

        //Route::get('/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.salary.before_pay.get', 'uses' => 'Work\Salary\BeforePay\SalaryBeforePayController@index']);
    });


    //--------------------- CHI --------------------
    # chi
    Route::group(['prefix' => 'pay'], function () {
        #thanh toan mua vat tu
        Route::group(['prefix' => 'pay-import'], function () {
            //xem chi tiết
            Route::get('view/{importId?}', ['as' => 'qc.work.pay.import.get.view.get', 'uses' => 'Work\Pay\Import\ImportController@viewImport']);

            //thêm mới
            //Route::get('add', ['as' => 'qc.ad3d.store.import.add.get', 'uses' => 'Ad3d\Store\Import\ImportController@getAdd']);
            //Route::post('add', ['as' => 'qc.ad3d.Store.import.add.post', 'uses' => 'Ad3d\Store\Import\ImportController@postAdd']);

            //Xóa
            //Route::get('del/{importId?}', ['as' => 'qc.ad3d.store.import.del.get', 'uses' => 'Ad3d\Store\Import\ImportController@deleteSupplies']);

            //tanh toan hoa don
            Route::get('pay/{importId?}', ['as' => 'qc.work.pay.import.pay.get', 'uses' => 'Work\Pay\Import\ImportController@getPay']);
            Route::post('pay/{importId?}', ['as' => 'qc.work.pay.import.pay.post', 'uses' => 'Work\Pay\Import\ImportController@postPay']);

            //Duyệt
            Route::get('confirm/{importId?}', ['as' => 'qc.work.pay.import.confirm.get', 'uses' => 'Work\Pay\Import\ImportController@getConfirm']);
            Route::post('confirm/{importId?}', ['as' => 'qc.work.pay.import.confirm.post', 'uses' => 'Work\Pay\Import\ImportController@postConfirm']);

            Route::get('/{monthFilter?}/{yearFilter?}/{payStatusFilter?}/{staffFilterId?}', ['as' => 'qc.work.pay.import.get', 'uses' => 'Work\Pay\Import\ImportController@index']);
        });
        #chi hoat dong
        Route::group(['prefix' => 'pay-activity'], function () {
            Route::get('add', ['as' => 'qc.work.pay.pay_activity.add.get', 'uses' => 'Work\Pay\PayActivity\PayActivityController@getAdd']);
            Route::post('add', ['as' => 'qc.work.pay.pay_activity.add.post', 'uses' => 'Work\Pay\PayActivity\PayActivityController@postAdd']);
            // xóa
            Route::get('delete/{payId?}', ['as' => 'qc.work.pay.pay_activity.delete.get', 'uses' => 'Work\Pay\PayActivity\PayActivityController@deletePayActivity']);

            Route::get('/{loginDay?}/{loginMonth?}/{loginYear?}/{loginPayStatus?}', ['as' => 'qc.work.pay.pay_activity.get', 'uses' => 'Work\Pay\PayActivity\PayActivityController@index']);
        });
        # thanh toan luong
        Route::group(['prefix' => 'pay-salary'], function () {
            Route::get('detail/{payId?}', ['as' => 'qc.work.pay.pay_salary.detail.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@detailPay']);
            # cong tien them
            Route::get('add-benefit/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.benefit.add.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@getAddBenefit']);
            Route::post('add-benefit/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.benefit.add.post', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@postAddBenefit']);

            # giu tien
            Route::get('add-keep-money/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.keep_money.add.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@getAddKeepMoney']);
            Route::post('add-keep-money/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.keep_money.add.post', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@postAddKeepMoney']);

            # thanh toan luong
            Route::get('add-payment/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.payment.add.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@getAddPayment']);
            Route::post('add-payment/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.payment.add.post', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@postAddPayment']);

            # huy thanh toan luong
            Route::get('delete/{payId?}', ['as' => 'qc.work.pay.pay_salary.delete.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@deletePay']);

            Route::get('/{loginMonth?}/{loginYear?}/{payStatus?}', ['as' => 'qc.work.pay.pay_salary.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@index']);
        });

        # thanh toan tien giu
        # giu tien NV
        Route::group(['prefix' => 'keep-money'], function () {
            # thanh toan
            # them ung luong
            Route::get('pay/{staffId?}', ['as' => 'qc.work.pay.keep_money.add.get', 'uses' => 'Work\Pay\PayKeepMoney\KeepMoneyController@getAddPay']);
            Route::post('pay/{staffId?}', ['as' => 'qc.work.pay.keep_money.add.post', 'uses' => 'Work\Pay\PayKeepMoney\KeepMoneyController@postAddPay']);
            # trang chinh
            Route::get('/{month?}/{year?}/{staffId?}/{payStatus?}', ['as' => 'qc.work.pay.keep_money.get', 'uses' => 'Work\Pay\PayKeepMoney\KeepMoneyController@index']);
        });
        # chi ung luong
        Route::group(['prefix' => 'salary-before-pay'], function () {
            # them ung luong
            Route::get('add/{salaryId?}', ['as' => 'qc.work.pay.salary_before_pay.add.get', 'uses' => 'Work\Pay\PaySalaryBeforePay\PaySalaryBeforePayController@getAdd']);
            Route::post('add/{salaryId?}', ['as' => 'qc.work.pay.salary_before_pay.add.post', 'uses' => 'Work\Pay\PaySalaryBeforePay\PaySalaryBeforePayController@postAdd']);

            # sua thong tin ung luong
            Route::get('edit/{payId?}', ['as' => 'qc.work.pay.salary_before_pay.edit.get', 'uses' => 'Work\Pay\PaySalaryBeforePay\PaySalaryBeforePayController@getEdit']);
            Route::post('edit/{payId?}', ['as' => 'qc.work.pay.salary_before_pay.edit.post', 'uses' => 'Work\Pay\PaySalaryBeforePay\PaySalaryBeforePayController@postEdit']);

            # xoa
            Route::get('delete/{payId?}', ['as' => 'qc.work.pay.salary_before_pay.delete', 'uses' => 'Work\Pay\PaySalaryBeforePay\PaySalaryBeforePayController@deletePay']);

            Route::get('/{dayMonth?}/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.pay.salary_before_pay.get', 'uses' => 'Work\Pay\PaySalaryBeforePay\PaySalaryBeforePayController@index']);
        });
    });

    //do nghe duoc giao
    Route::group(['prefix' => 'tool'], function () {
        # kiem tra do nghe dung chung
        Route::group(['prefix' => 'check-store'], function () {
            # anh nhap do nghe
            Route::get('view-import-image/{imageId?}', ['as' => 'qc.work.tool.check_store.import_image.get', 'uses' => 'Work\Tool\CheckStore\CheckStoreController@viewImportImage']);
            # anh bao cao do nghe
            Route::get('view-report-image/{reportId?}', ['as' => 'qc.work.tool.check_store.report_image.get', 'uses' => 'Work\Tool\CheckStore\CheckStoreController@viewReportImage']);

            # xac nhan
            Route::post('confirm', ['as' => 'qc.work.tool.check_store.confirm.post', 'uses' => 'Work\Tool\CheckStore\CheckStoreController@postConfirm']);

            Route::get('/{checkFilter?}', ['as' => 'qc.work.tool.check_store.get', 'uses' => 'Work\Tool\CheckStore\CheckStoreController@index']);
        });

        # do nghe da phat
        Route::group(['prefix' => 'package-allocation'], function () {
            #xem chi tiết
            # anh ban giao do nghe
            Route::get('view-image/{detailId?}', ['as' => 'qc.work.tool.package_allocation.image.view', 'uses' => 'Work\Tool\PackageAllocation\ToolPackageAllocationController@viewImage']);
            # anh tra do nghe
            Route::get('view-return-image/{returnId?}', ['as' => 'qc.work.tool.package_allocation.return_image.view', 'uses' => 'Work\Tool\PackageAllocation\ToolPackageAllocationController@viewReturnImage']);
            # bao cao do nghe
            Route::get('report/{detailId?}', ['as' => 'qc.work.tool.package_allocation.report.get', 'uses' => 'Work\Tool\PackageAllocation\ToolPackageAllocationController@getReport']);
            Route::post('report/{detailId?}', ['as' => 'qc.work.tool.package_allocation.report.post', 'uses' => 'Work\Tool\PackageAllocation\ToolPackageAllocationController@postReport']);
            # tra lai do nghe
            Route::get('return/{allocationId?}/{storeId?}', ['as' => 'qc.work.tool.package_allocation.return.get', 'uses' => 'Work\Tool\PackageAllocation\ToolPackageAllocationController@getReturn']);
            Route::post('return', ['as' => 'qc.work.tool.package_allocation.return.post', 'uses' => 'Work\Tool\PackageAllocation\ToolPackageAllocationController@postReturn']);

            #xác nhận đồ nghề
            //Route::get('confirm-receive/{allocationId?}', ['as' => 'qc.work.tool.allocation.confirm_receive.get', 'uses' => 'Work\Tool\PackageAllocation\ToolPackageAllocationController@getConfirmReceive']);

            Route::get('/', ['as' => 'qc.work.tool.package_allocation.get', 'uses' => 'Work\Tool\PackageAllocation\ToolPackageAllocationController@index']);
        });

    });
    //------ kho do nghe cua he thong -------
    Route::group(['prefix' => 'store'], function () {
        # tui do nghe
        Route::group(['prefix' => 'tool-package'], function () {
            # xem chi tiet
            Route::get('detail/{packageId?}', ['as' => 'qc.work.store.tool_package.detail', 'uses' => 'Work\Store\ToolPackage\ToolPackageController@detailPackage']);
            # xem anh nhap san pham
            Route::get('view-import-image/{imageId?}', ['as' => 'qc.work.store.tool_package.import_image.get', 'uses' => 'Work\Store\ToolPackage\ToolPackageController@viewImportImage']);
            # xem anh ban giao
            Route::get('view-detail-image/{detailId?}', ['as' => 'qc.work.store.tool_package.detail_image.get', 'uses' => 'Work\Store\ToolPackage\ToolPackageController@viewDetailImage']);
            # xem anh bao tra
            Route::get('view-return-image/{returnId?}', ['as' => 'qc.work.store.tool_package.return_image.get', 'uses' => 'Work\Store\ToolPackage\ToolPackageController@viewReturnImage']);
            # giao  do nghe tu dong
            Route::get('auto-allocation', ['as' => 'qc.work.store.tool_package.auto_allocation.add', 'uses' => 'Work\Store\ToolPackage\ToolPackageController@addAutoAllocationPackage']);
            # tui do nghe
            Route::get('/', ['as' => 'qc.work.store.tool_package.get', 'uses' => 'Work\Store\ToolPackage\ToolPackageController@index']);
        });
        # tui do nghe da duoc ban giao
        Route::group(['prefix' => 'tool-package-allocation'], function () {
            # xac nhan tra
            Route::get('check/{allocationId?}', ['as' => 'qc.work.store.tool_package_allocation.check.get', 'uses' => 'Work\Store\ToolPackageAllocation\ToolPackageAllocationController@checkInfo']);
            # xem anh ban giao
            Route::get('view-image/{detailId?}', ['as' => 'qc.work.store.tool_package_allocation.check.image.view', 'uses' => 'Work\Store\ToolPackageAllocation\ToolPackageAllocationController@viewImage']);
            # xem anh tra
            Route::get('view-return-image/{returnId?}', ['as' => 'qc.work.store.tool_package_allocation.check.return_image.view', 'uses' => 'Work\Store\ToolPackageAllocation\ToolPackageAllocationController@viewReturnImage']);
            # ap dung phat
            Route::get('minus-money/{detailId?}', ['as' => 'qc.work.store.tool_package_allocation.check.minus_money.get', 'uses' => 'Work\Store\ToolPackageAllocation\ToolPackageAllocationController@getMinusMoney']);
            Route::post('minus-money/{detailId?}', ['as' => 'qc.work.store.tool_package_allocation.check.minus_money.post', 'uses' => 'Work\Store\ToolPackageAllocation\ToolPackageAllocationController@postMinusMoney']);

            Route::get('/', ['as' => 'qc.work.store.tool_package_allocation.get', 'uses' => 'Work\Store\ToolPackageAllocation\ToolPackageAllocationController@index']);
        });

        # thong tin kho
        Route::group(['prefix' => 'tool'], function () {
            # ban giao do nghe
            Route::get('allocation-add/{staffWorkId?}', ['as' => 'qc.work.store.tool.allocation_list.add.get', 'uses' => 'Work\Store\Tool\CompanyStoreController@getAddAllocationList']);
            Route::post('allocation-add', ['as' => 'qc.work.store.tool.allocation_list.add.post', 'uses' => 'Work\Store\Tool\CompanyStoreController@postAddAllocationList']);

            # xem anh nhap san pham
            Route::get('view-import-image/{imageId?}', ['as' => 'qc.work.store.tool.import_image.get', 'uses' => 'Work\Store\Tool\CompanyStoreController@viewImportImage']);
            # xem anh ban giao
            Route::get('view-detail-image/{detailId?}', ['as' => 'qc.work.store.tool.detail_image.get', 'uses' => 'Work\Store\Tool\CompanyStoreController@viewDetailImage']);
            # xem anh bao tra
            ////Route::get('view-return-image/{returnId?}', ['as' => 'qc.work.store.tool.return_image.get', 'uses' => 'Work\Store\Tool\CompanyStoreController@viewReturnImage']);

            Route::get('/{type?}/{toolId?}', ['as' => 'qc.work.store.tool.get', 'uses' => 'Work\Store\Tool\CompanyStoreController@index']);
        });
        # ban giao lai dung cu
        Route::group(['prefix' => 'too-package-allocation-return'], function () {
            # xem anh nhap san pham
            Route::get('view-detail-image/{detailId?}', ['as' => 'qc.work.store.tool_package_allocation_return.detail_image.get', 'uses' => 'Work\Store\ToolPackageAllocationReturn\ToolPackageAllocationReturnController@viewDetailImage']);
            # xem anh bao tra
            Route::get('view-import-return/{returnId?}', ['as' => 'qc.work.store.tool_package_allocation_return.return_image.get', 'uses' => 'Work\Store\ToolPackageAllocationReturn\ToolPackageAllocationReturnController@viewReturnImage']);

            # xac nhan tra
            Route::get('confirm/{allocationId?}', ['as' => 'qc.work.store.tool_package_allocation_return.confirm.get', 'uses' => 'Work\Store\ToolPackageAllocationReturn\ToolPackageAllocationReturnController@getConfirm']);
            Route::post('confirm/{allocationId?}', ['as' => 'qc.work.store.tool_package_allocation_return.confirm.post', 'uses' => 'Work\Store\ToolPackageAllocationReturn\ToolPackageAllocationReturnController@postConfirm']);

            Route::get('/{confirmStatus?}/{staffFilterId?}', ['as' => 'qc.work.store.tool_package_allocation_return.get', 'uses' => 'Work\Store\ToolPackageAllocationReturn\ToolPackageAllocationReturnController@index']);
        });
    });
    //nhận đồ nghề
    Route::group(['prefix' => 'product-type-price'], function () {
        Route::get('/{nameFilter?}', ['as' => 'qc.work.product_type_price.get', 'uses' => 'Work\ProductTypePrice\ProductTypePriceController@index']);
    });

    //thuong
    Route::group(['prefix' => 'bonus'], function () {
        Route::get('/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.bonus.get', 'uses' => 'Work\Bonus\BonusController@index']);
    });
    //phạt
    Route::group(['prefix' => 'minus-money'], function () {
        # xem anh phan hoi
        Route::get('view-image/{minusId?}', ['as' => 'qc.work.minus_money.feedback.view_image.get', 'uses' => 'Work\MinusMoney\MinusMoneyController@getViewImage']);
        # pha hoi
        Route::get('feedback/{minusId?}', ['as' => 'qc.work.minus_money.feedback.get', 'uses' => 'Work\MinusMoney\MinusMoneyController@getFeedback']);
        Route::post('feedback', ['as' => 'qc.work.minus_money.feedback.post', 'uses' => 'Work\MinusMoney\MinusMoneyController@postFeedback']);

        #huy phan hoi
        Route::get('feedback-cancel/{minusId?}', ['as' => 'qc.work.minus_money.feedback.cancel', 'uses' => 'Work\MinusMoney\MinusMoneyController@cancelFeedback']);

        Route::get('/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.minus_money.get', 'uses' => 'Work\MinusMoney\MinusMoneyController@index']);
    });
    //tin tưc
    Route::group(['prefix' => 'news'], function () {
        # thong bao hoat dong
        Route::get('notify/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.news.notify.get', 'uses' => 'Work\WorkController@notify']);
        #ngay nghi
        Route::get('date-off/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.news.date_off.get', 'uses' => 'Work\WorkController@dateOff']);
    });

    Route::get('/{sysInfoObject?}', ['as' => 'qc.work.home', 'uses' => 'Work\WorkController@index']);
});

#about
Route::get('gioi-thieu', ['as' => 'qc.about', 'uses' => 'About\AboutController@index']);

#about
Route::get('lien-he', ['as' => 'qc.contact', 'uses' => 'Contact\ContactController@index']);

Route::get('san-pham', ['as' => 'qc.product', 'uses' => 'Product\ProductController@product']);
Route::get('san-pham/chi-tiet', ['as' => 'qc.product.detail', 'uses' => 'Product\ProductController@detail']);

Route::get('sua-chua', ['as' => 'qc.service.repair', 'uses' => 'Service\ServiceController@repair']);
Route::get('tu-van', ['as' => 'qc.service.advisory', 'uses' => 'Service\ServiceController@advisory']);

Route::get('gia/ban-hieu', ['as' => 'qc.price.banner', 'uses' => 'Price\PriceController@banner']);
Route::get('gia/chat-lieu', ['as' => 'qc.price.material', 'uses' => 'Price\PriceController@material']);

#about
Route::get('tuyen-dung', ['as' => 'qc.recruitment', 'uses' => 'Recruitment\RecruitmentController@index']);

//article
Route::group(['prefix' => 'article'], function () {
    #register
    Route::get('/{articleAlias?}', ['as' => 'qc.article.get', 'uses' => 'Article\ArticleController@index']);
});

//article
Route::group(['prefix' => 'demo'], function () {
    #register
    Route::get('/', ['as' => 'qc.article.get', 'uses' => 'Demo\DemoController@index']);
});

#home
Route::get('/{alias?}', ['as' => 'qc.home', 'uses' => 'Home\HomeController@index']);