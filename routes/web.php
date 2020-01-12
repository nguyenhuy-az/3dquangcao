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

                Route::get('/', ['as' => 'qc.ad3d.store.tool.tool.get', 'uses' => 'Ad3d\Store\Tool\Tool\ToolController@index']);
            });

            //bàn giao dụng cụ
            Route::group(['prefix' => 'allocation'], function () {
                Route::get('view/{allocationId?}', ['as' => 'qc.ad3d.store.tool.allocation.view.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@viewAllocation']);

                //sửa
                //Route::get('edit/{toolId?}', ['as' => 'qc.ad3d.store.tool.tool.edit.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@getEdit']);
                //Route::post('edit/{toolId?}', ['as' => 'qc.ad3d.store.tool.tool.edit.post', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@postEdit']);

                //thêm
                Route::get('add/{selectCompanyId?}', ['as' => 'qc.ad3d.store.tool.allocation.add.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@getAdd']);
                Route::post('add', ['as' => 'qc.ad3d.Store.tool.allocation.add.post', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@postAdd']);

                //Xóa
                //Route::get('del/{toolId?}', ['as' => 'qc.ad3d.store.tool.allocation.del.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@deleteTool']);

                Route::get('/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.store.tool.allocation.get', 'uses' => 'Ad3d\Store\Tool\Allocation\AllocationController@index']);
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
            Route::get('tool/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.store.store.tool.get', 'uses' => 'Ad3d\Store\Store\StoreController@index']);

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
            #xem anh cham cong
            Route::get('image/{imageId?}', ['as' => 'qc.ad3d.work.time-keeping.image.view.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@viewImage']);

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

            Route::get('view/{allocationId?}', ['as' => 'qc.ad3d.work.work_allocation.view.get', 'uses' => 'Ad3d\Work\WorkAllocation\WorkAllocationController@view']);

            Route::get('cancel/{allocationId?}', ['as' => 'qc.ad3d.work.work_allocation.delete', 'uses' => 'Ad3d\Work\WorkAllocation\WorkAllocationController@cancelWorkAllocation']);

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
            #xem anh cham cong
            Route::get('image/{imageId?}', ['as' => 'qc.ad3d.work.time-keeping-provisional.view.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@viewProvisionalImage']);

            //hủy
            Route::get('cancel/{timekeepingProvisionalId?}', ['as' => 'qc.ad3d.work.time-keeping-provisional.cancel.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@cancelTimekeepingProvisional']);
            //xác nhận
            Route::get('confirm/{timekeepingProvisionalId?}', ['as' => 'qc.ad3d.work.time-keeping-provisional.confirm.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@getConfirm']);
            Route::post('confirm/', ['as' => 'qc.ad3d.work.time-keeping-provisional.confirm.post', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@postConfirm']);

            Route::get('old/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.work.old-time-keeping-provisional.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@indexOld']);
            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.work.time-keeping-provisional.get', 'uses' => 'Ad3d\Work\TimeKeepingProvisional\TimeKeepingProvisionalController@index']);
        });
        //hệ thống chấm công - khi chưa cho nv tự chấm
        Route::group(['prefix' => 'timekeeping'], function () {
            //add work
            Route::get('add/{companyLoginId?}/{workId?}', ['as' => 'qc.ad3d.work.time-keeping.add.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.work.time-keeping.add.post', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@postAdd']);

            //confirm
            Route::get('confirm/{timekeepingId?}', ['as' => 'qc.ad3d.work.time-keeping.confirm.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@getConfirm']);
            Route::post('confirm/{timekeepingId?}', ['as' => 'qc.ad3d.work.time-keeping.confirm.post', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@postConfirm']);

            //off work
            Route::get('off/add/{companyLoginId?}/{workId?}', ['as' => 'qc.ad3d.work.time-keeping.off.add.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@getAddOff']);
            Route::post('off/add', ['as' => 'qc.ad3d.work.time-keeping.off.add.post', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@postAddOff']);

            //delete
            Route::get('delete/{timekeepingId?}', ['as' => 'qc.ad3d.work.time-keeping.delete', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@deleteTimekeeping']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{name?}', ['as' => 'qc.ad3d.work.time-keeping.get', 'uses' => 'Ad3d\Work\TimeKeeping\TimeKeepingController@index']);
        });

    });

    //tài chính
    Route::group(['prefix' => 'finance', 'middleware' => 'Ad3dMiddleware'], function () {
        Route::group(['prefix' => 'transfers'], function () {
            Route::get('view/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.view.get', 'uses' => 'Ad3d\Finance\Transfers\TransfersController@view']);

            Route::get('edit/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.edit.get', 'uses' => 'Ad3d\Finance\Transfers\TransfersController@getEdit']);
            Route::post('edit/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.edit.post', 'uses' => 'Ad3d\Finance\Transfers\TransfersController@postEdit']);

            Route::get('add', ['as' => 'qc.ad3d.finance.transfers.add.get', 'uses' => 'Ad3d\Finance\Transfers\TransfersController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.finance.transfers.add.post', 'uses' => 'Ad3d\Finance\Transfers\TransfersController@postAdd']);

            #xác nhận giao tiền
            Route::get('confirm/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.confirm.get', 'uses' => 'Ad3d\Finance\Transfers\TransfersController@getConfirmReceive']);
            Route::post('confirm/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.confirm.post', 'uses' => 'Ad3d\Finance\Transfers\TransfersController@postConfirmReceive']);

            //xóa
            Route::get('delete/{transfersId?}', ['as' => 'qc.ad3d.finance.transfers.delete', 'uses' => 'Ad3d\Finance\Transfers\TransfersController@deleteTransfers']);

            Route::get('/{transfersId?}/{day?}/{month?}/{year?}/{typeId?}', ['as' => 'qc.ad3d.finance.transfers.get', 'uses' => 'Ad3d\Finance\Transfers\TransfersController@index']);
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

        Route::group(['prefix' => 'minus-money'], function () {
            Route::get('view/{payId?}', ['as' => 'qc.ad3d.finance.minus-money.view.get', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@view']);
            //Add
            Route::get('add/{companyLoginId?}/{workId?}/{punishId?}', ['as' => 'qc.ad3d.finance.minus-money.add.get', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.finance.minus-money.add.post', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@postAdd']);

            //edit
            Route::get('edit/{payId?}', ['as' => 'qc.ad3d.finance.minus-money.edit.get', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@getEdit']);
            Route::post('edit/{payId?}', ['as' => 'qc.ad3d.finance.minus-money.edit.post', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@postEdit']);

            //delete
            Route::get('delete/{payId?}', ['as' => 'qc.ad3d.finance.minus-money.delete', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@deleteMinusMoney']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{punishContentId?}/{name?}', ['as' => 'qc.ad3d.finance.minus-money.get', 'uses' => 'Ad3d\Finance\MinusMoney\MinusMoneyController@index']);
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
                //Add
                Route::get('add/{companyLoginId?}/{workId?}', ['as' => 'qc.ad3d.finance.salary.pay-before.add.get', 'uses' => 'Ad3d\Finance\Salary\PayBefore\PayBeforeController@getAdd']);
                Route::post('add', ['as' => 'qc.ad3d.finance.salary.pay-before.add.post', 'uses' => 'Ad3d\Finance\Salary\PayBefore\PayBeforeController@postAdd']);

                //edit
                Route::get('edit/{payId?}', ['as' => 'qc.ad3d.finance.salary.pay-before.edit.get', 'uses' => 'Ad3d\Finance\Salary\PayBefore\PayBeforeController@getEdit']);
                Route::post('edit/{payId?}', ['as' => 'qc.ad3d.finance.salary.pay-before.edit.post', 'uses' => 'Ad3d\Finance\Salary\PayBefore\PayBeforeController@postEdit']);

                //delete
                Route::get('delete/{payId?}', ['as' => 'qc.ad3d.finance.salary.pay-before.delete', 'uses' => 'Ad3d\Finance\Salary\PayBefore\PayBeforeController@deleteSalaryBeforePay']);

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
        //rule
        Route::group(['prefix' => 'rules'], function () {
            Route::get('view/{rulesId?}', ['as' => 'qc.ad3d.system.rules.view.get', 'uses' => 'Ad3d\System\Rules\RulesController@view']);

            //edit
            Route::get('edit/{rulesId?}', ['as' => 'qc.ad3d.system.rules.edit.get', 'uses' => 'Ad3d\System\Rules\RulesController@getEdit']);
            Route::post('edit/{rulesId?}', ['as' => 'qc.ad3d.system.rules.edit.post', 'uses' => 'Ad3d\System\Rules\RulesController@postEdit']);

            //off work
            Route::get('add', ['as' => 'qc.ad3d.system.rules.add.get', 'uses' => 'Ad3d\System\Rules\RulesController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.rules.add.post', 'uses' => 'Ad3d\System\Rules\RulesController@postAdd']);

            Route::get('del/{rulesId?}', ['as' => 'qc.ad3d.system.rules.del', 'uses' => 'Ad3d\System\Rules\RulesController@deleteDelete']);

            Route::get('/', ['as' => 'qc.ad3d.system.rules.get', 'uses' => 'Ad3d\System\Rules\RulesController@index']);
        });

        //company
        Route::group(['prefix' => 'company'], function () {
            Route::get('view/{companyId?}', ['as' => 'qc.ad3d.system.company.view.get', 'uses' => 'Ad3d\System\Company\CompanyController@view']);

            //edit
            Route::get('edit/{companyId?}', ['as' => 'qc.ad3d.system.company.edit.get', 'uses' => 'Ad3d\System\Company\CompanyController@getEdit']);
            Route::post('edit/{companyId?}', ['as' => 'qc.ad3d.system.company.post.get', 'uses' => 'Ad3d\System\Company\CompanyController@postEdit']);

            //off work
            Route::get('add', ['as' => 'qc.ad3d.system.company.add.get', 'uses' => 'Ad3d\System\Company\CompanyController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.company.add.post', 'uses' => 'Ad3d\System\Company\CompanyController@postAdd']);
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

        //bộ phận
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
        //ngay nghi cua he thong
        Route::group(['prefix' => 'system-date-off'], function () {
            Route::get('view/{payListId?}', ['as' => 'qc.ad3d.system.system_date_off.view.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@view']);
            //thêm mới
            Route::get('add/date', ['as' => 'qc.ad3d.system.system_date_off.add.date.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@getAddDate']);
            Route::get('add', ['as' => 'qc.ad3d.system.system_date_off.add.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.system_date_off.add.post', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@postAdd']);
            //xoa danh muc
            Route::get('delete/{dateOffId?}', ['as' => 'qc.ad3d.system.system_date_off.delete', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@deleteDateOff']);
            //danh muc chi
            Route::get('/{companyFilterId?}/{monthFilter?}/{yearFilter?}', ['as' => 'qc.ad3d.system.system_date_off.get', 'uses' => 'Ad3d\System\SystemDateOff\SystemDateOffController@index']);
        });

        //nhân viên
        Route::group(['prefix' => 'staff'], function () {
            Route::get('view/{staffId?}', ['as' => 'qc.ad3d.system.staff.view.get', 'uses' => 'Ad3d\System\Staff\StaffController@view']);

            //them moi
            Route::get('add', ['as' => 'qc.ad3d.system.staff.add.get', 'uses' => 'Ad3d\System\Staff\StaffController@getAdd']);
            Route::get('add/department', ['as' => 'qc.ad3d.system.staff.department.add', 'uses' => 'Ad3d\System\Staff\StaffController@getAddDepartment']);
            Route::post('add', ['as' => 'qc.ad3d.system.staff.add.post', 'uses' => 'Ad3d\System\Staff\StaffController@postAdd']);

            //mo cham com
            Route::get('open-work/{companyStaffWorkId?}', ['as' => 'qc.ad3d.system.staff.open_work.get', 'uses' => 'Ad3d\System\Staff\StaffController@openWork']);

            //sửa
            Route::get('edit/{staffId?}', ['as' => 'qc.ad3d.system.staff.edit.get', 'uses' => 'Ad3d\System\Staff\StaffController@getEdit']);
            #thong tin co ban
            Route::post('edit/info/{staffId?}', ['as' => 'qc.ad3d.system.staff.info.edit.post', 'uses' => 'Ad3d\System\Staff\StaffController@postInfoEdit']);
            #thong tin lam viec
            Route::post('edit/work/{staffId?}', ['as' => 'qc.ad3d.system.staff.work.edit.post', 'uses' => 'Ad3d\System\Staff\StaffController@postCompanyWorkEdit']);
            #luong
            Route::post('edit/salary/{staffId?}', ['as' => 'qc.ad3d.system.staff.salary.edit.post', 'uses' => 'Ad3d\System\Staff\StaffController@postCompanySalaryEdit']);

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

            Route::get('list/{companyId?}', ['as' => 'qc.ad3d.system.staff.get', 'uses' => 'Ad3d\System\Staff\StaffController@index']);

        });
        //lương cơ bản NV
        Route::group(['prefix' => 'salary'], function () {
            Route::get('view/{staffId?}', ['as' => 'qc.ad3d.system.salary.view.get', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@view']);

            //edit
            Route::get('edit/{salaryBasicId?}', ['as' => 'qc.ad3d.system.salary.edit.get', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@getEdit']);
            Route::post('edit/{salaryBasicId?}', ['as' => 'qc.ad3d.system.salary.edit.post', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@postEdit']);

            Route::get('list-old/{companyId?}', ['as' => 'qc.ad3d.system.salary_old.get', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@indexOld']);
            Route::get('list/{companyId?}', ['as' => 'qc.ad3d.system.salary.get', 'uses' => 'Ad3d\System\Salary\StaffSalaryController@index']);

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
            Route::get('add', ['as' => 'qc.ad3d.system.punish-content.add.get', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@getAdd']);
            Route::post('add', ['as' => 'qc.ad3d.system.punish-content.add.post', 'uses' => 'Ad3d\System\PunishContent\PunishContentController@postAdd']);

            //xóa
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
            # xac nhan don hang duoc dat
            Route::get('confirm/{orderId?}', ['as' => 'qc.ad3d.order.order.confirm.get', 'uses' => 'Ad3d\Order\Order\OrderController@getConfirm']);
            Route::post('confirm/{orderId?}', ['as' => 'qc.ad3d.order.order.confirm.post', 'uses' => 'Ad3d\Order\Order\OrderController@postConfirm']);

            # thong tin don hang
            Route::get('view/{orderId?}', ['as' => 'qc.ad3d.order.order.view.get', 'uses' => 'Ad3d\Order\Order\OrderController@view']);
            #xem anh thiet ke
            Route::get('view-product-design/{productId?}', ['as' => 'qc.ad3d.order.order.product.design.view', 'uses' => 'Ad3d\Order\Order\OrderController@viewProductDesign']);
            # thong tin khach hang
            Route::get('view-customer/{customerId?}', ['as' => 'qc.ad3d.order.order.view_customer.get', 'uses' => 'Ad3d\Order\Order\OrderController@viewCustomer']);

            # in don hang
            Route::get('print/{orderId?}', ['as' => 'qc.ad3d.order.order.print.get', 'uses' => 'Ad3d\Order\Order\OrderController@printOrder']);

            # in nghiem thu
            Route::get('print/confirm/{orderId?}', ['as' => 'qc.ad3d.order.order.confirm.print.get', 'uses' => 'Ad3d\Order\Order\OrderController@printConfirmOrder']);

            # kiem tra khach hang theo so dien thoai
            Route::get('customer/{phone?}', ['as' => 'qc.ad3d.order.order.customer.check.phone', 'uses' => 'Ad3d\Order\Order\OrderController@checkPhoneCustomer']);

            #them san pham vao don hang
           // Route::get('add/product', ['as' => 'qc.ad3d.order.order.product.get', 'uses' => 'Ad3d\Order\Order\OrderController@addProduct']);
            //Route::get('add/{customerId?}/{orderId?}', ['as' => 'qc.ad3d.order.order.add.get', 'uses' => 'Ad3d\Order\Order\OrderController@getAdd']);
            //Route::post('add', ['as' => 'qc.ad3d.order.order.add.post', 'uses' => 'Ad3d\Order\Order\OrderController@postAdd']);
            # sua don hang
            //Route::post('edit/{orderId}', ['as' => 'qc.ad3d.order.order.edit.addProduct.post', 'uses' => 'Ad3d\Order\Order\OrderController@postEditAddProduct']);

            #thanh toan don hang
            Route::get('payment/{orderId?}', ['as' => 'qc.ad3d.order.order.payment.get', 'uses' => 'Ad3d\Order\Order\OrderController@getPayment']);
            Route::post('payment/{orderId?}', ['as' => 'qc.ad3d.order.order.payment.post', 'uses' => 'Ad3d\Order\Order\OrderController@postPayment']);

            #--------- ban giao don hang - cong trinh ---------
            # xem chi tiet thi cong
            Route::get('view-work-allocation/{allocationId?}', ['as' => 'qc.ad3d.order.order.work_allocation.get', 'uses' => 'Ad3d\Order\Order\OrderController@viewWorkAllocation']);
            # xem chi tiết ảnh báo cáo
            Route::get('view-report-image/{imageId?}', ['as' => 'qc.ad3d.order.order.allocation.report_image.get', 'uses' => 'Ad3d\Order\Order\OrderController@viewReportImage']);

            Route::get('construction/{orderId?}', ['as' => 'qc.ad3d.order.order.construction.get', 'uses' => 'Ad3d\Order\Order\OrderController@getOrderConstruction']);
            Route::post('construction/add/{orderId?}', ['as' => 'qc.ad3d.order.order.construction.add.post', 'uses' => 'Ad3d\Order\Order\OrderController@postOrderConstruction']);
            # huy ban giao
            Route::get('delete-construction/{allocationId?}', ['as' => 'qc.ad3d.order.order.construction.delete', 'uses' => 'Ad3d\Order\Order\OrderController@deleteOrderConstruction']);

            Route::get('delete/{orderId?}', ['as' => 'qc.ad3d.order.order.delete', 'uses' => 'Ad3d\Order\Order\OrderController@deleteOrder']);

            Route::get('/{companyId?}/{day?}/{month?}/{year?}/{paymentStatus?}/{orderFilterName?}/{orderCustomerFilterName?}/{staffFilterName?}', ['as' => 'qc.ad3d.order.order.get', 'uses' => 'Ad3d\Order\Order\OrderController@index']);

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

            //xoa
            Route::get('delete/{typeId?}', ['as' => 'qc.ad3d.order.product_type.delete', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@deleteType']);

            Route::get('/', ['as' => 'qc.ad3d.order.product-type.get', 'uses' => 'Ad3d\Order\ProductType\ProductTypeController@index']);
        });
        //bang gia loai SP
        Route::group(['prefix' => 'product-type-price'], function () {
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
Route::group(['prefix' => 'work'], function () {
    //đăng nhập
    Route::get('login/', ['as' => 'qc.work.login.get', 'uses' => 'Work\WorkController@getLogin']);
    Route::post('login/', ['as' => 'qc.work.login.post', 'uses' => 'Work\WorkController@login']);

    //thoát
    Route::get('logout', ['as' => 'qc.work.logout.get', 'uses' => 'Work\WorkController@logout']);

    //quản lý đơn hàng
    Route::group(['prefix' => 'orders'], function () {
        Route::group(['prefix' => 'info'], function () {
            #thay doi thong tin khach hang
            Route::post('customer-edit/{customerId?}', ['as' => 'qc.work.orders.info.customer.edit.post', 'uses' => 'Work\Orders\OrdersController@postEditInfoCustomer']);
            #thay doi thong tin don hang
            Route::post('order-edit/{orderId?}', ['as' => 'qc.work.orders.info.order.edit.post', 'uses' => 'Work\Orders\OrdersController@postEditInfoOrder']);
            #thay doi thong tin thanh toan
            Route::get('pay-edit/{payId?}', ['as' => 'qc.work.orders.info.pay.edit.post', 'uses' => 'Work\Orders\OrdersController@getEditInfoPay']);
            Route::post('pay-edit/{payId?}', ['as' => 'qc.work.orders.info.pay.edit.post', 'uses' => 'Work\Orders\OrdersController@postEditInfoPay']);

            Route::get('list/{orderId?}', ['as' => 'qc.work.orders.info.get', 'uses' => 'Work\Orders\OrdersController@ordersInfo']);
        });
        Route::group(['prefix' => 'product'], function () {
            # thiet ke
            Route::group(['prefix' => 'design'], function () {
                # du lieu moi
                Route::get('view/{designId?}', ['as' => 'qc.work.orders.product_design.view.get', 'uses' => 'Work\Orders\OrdersController@viewProductDesign']);

                #them thiet ke
                Route::get('design/{productId?}', ['as' => 'qc.work.orders.product.design.add.get', 'uses' => 'Work\Orders\OrdersController@getProductDesign']);
                Route::post('design/{productId?}', ['as' => 'qc.work.orders.product.design.add.post', 'uses' => 'Work\Orders\OrdersController@postProductDesign']);

                #xac nhan su dung thiet ke
                Route::get('confirm-apply/{designId?}/{applyStatus?}', ['as' => 'qc.work.orders.product.design.apply.get', 'uses' => 'Work\Orders\OrdersController@getConfirmApplyDesign']);
                Route::post('confirm-apply/{designId?}/{applyStatus?}', ['as' => 'qc.work.orders.product.design.apply.post', 'uses' => 'Work\Orders\OrdersController@postConfirmApplyDesign']);

                #thiet ke
                Route::get('/{productId?}', ['as' => 'qc.work.orders.product.design.get', 'uses' => 'Work\Orders\OrdersController@getDesign']);
            });

            #thay do thong tin san phan
            Route::group(['prefix'=>'info'], function(){
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

            #them san pham
            Route::get('/product', ['as' => 'qc.work.orders.product.get', 'uses' => 'Work\Orders\OrdersController@addProduct']);
            Route::get('/{type?}/{customerId?}/{orderId?}', ['as' => 'qc.work.orders.add.get', 'uses' => 'Work\Orders\OrdersController@getAdd']);
            // them don hang thuc
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
            Route::get('/{orderId?}', ['as' => 'qc.work.orders.payment.get', 'uses' => 'Work\Orders\OrdersController@getPayment']);
            Route::post('/{orderId?}', ['as' => 'qc.work.orders.payment.post', 'uses' => 'Work\Orders\OrdersController@postPayment']);
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
            Route::get('delete/{orderId?}', ['as' => 'qc.work.orders.provisional.cancel.get', 'uses' => 'Work\Orders\OrdersController@cancelOrders']);

            Route::get('/{loginMonth?}/{loginYear?}/{orderFilterName?}/{orderCustomerFilterName?}', ['as' => 'qc.work.orders.provisional.get', 'uses' => 'Work\Orders\OrdersProvisionalController@index']);
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

        Route::get('/{loginMonth?}/{loginYear?}/{paymentStatus?}/{orderFilterName?}/{customerName?}', ['as' => 'qc.work.orders.get', 'uses' => 'Work\Orders\OrdersController@index']);
    });

    //quản lý thu chi
    Route::group(['prefix' => 'money'], function () {
        # tien thu chua ban giao
        Route::group(['prefix' => 'receive'], function () {
            Route::post('transfer', ['as' => 'qc.work.money.receive.transfer.post', 'uses' => 'Work\Money\Receive\MoneyReceiveController@postTransfer']);

            Route::get('/{loginDay?}/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.money.receive.get', 'uses' => 'Work\Money\Receive\MoneyReceiveController@index']);
        });

        # thong tin chi
        Route::group(['prefix' => 'pay'], function () {
            //Route::post('transfer', ['as' => 'qc.work.money.receive.transfer.post', 'uses' => 'Work\Money\Receive\MoneyReceiveController@postTransfer']);
            Route::get('import/{loginDay?}/{loginMonth?}/{loginYear?}/{payStatus?}', ['as' => 'qc.work.money.pay.import.get', 'uses' => 'Work\Money\Pay\Import\MoneyPayImportController@index']);
        });

        # tien thu chua ban giao
        Route::group(['prefix' => 'history'], function () {
            Route::get('receive/{loginDay?}/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.money.history.receive.get', 'uses' => 'Work\Money\History\MoneyHistoryController@historyReceive']);
        });

        # giao tien
        Route::group(['prefix' => 'transfer'], function () {
            # thong tin giao tien
            Route::get('transfer/{loginDay?}/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.money.transfer.transfer.get', 'uses' => 'Work\Money\Transfer\MoneyTransferController@transferIndex']);
            # thong tin nhan tien
            Route::get('receive-confirm/{transferId?}', ['as' => 'qc.work.money.transfer.receive.confirm.get', 'uses' => 'Work\Money\Transfer\MoneyTransferController@receiveConfirm']);
            Route::get('receive/{loginDay?}/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.money.transfer.receive.get', 'uses' => 'Work\Money\Transfer\MoneyTransferController@receiveIndex']);
        });

        # thong ke
        Route::group(['prefix' => 'statistical'], function () {
            Route::get('/{loginDay?}/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.money.statistical.get', 'uses' => 'Work\Money\Statistical\MoneyStatisticalController@index']);
        });
    });

    //phân công làm việc
    Route::group(['prefix' => 'work-allocation'], function () {
        //cong viec dang lam
        Route::group(['prefix' => 'activity'], function () {
            #bao cao cong viec trong ngay
            Route::get('report/{allocationId?}', ['as' => 'qc.work.work_allocation.activity.report.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@getAllocationReport']);
            Route::post('report/{allocationId?}', ['as' => 'qc.work.work_allocation.activity.report.post', 'uses' => 'Work\WorkAllocation\WorkAllocationController@postAllocationReport']);

            #anh bao cao
            Route::get('report/image-delete/{imageId?}', ['as' => 'qc.work.work_allocation.activity.report_image.delete', 'uses' => 'Work\WorkAllocation\WorkAllocationController@deleteReportImage']);

            #Hủy báo cáo
            Route::get('report/cancel/{reportId?}', ['as' => 'qc.work.work_allocation.activity.report.cancel.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@cancelReport']);


            Route::get('/', ['as' => 'qc.work.work_allocation.activity.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@activityIndex']);
        });

        // cong viec da ket thuc
        Route::group(['prefix' => 'finish'], function () {
            Route::get('/', ['as' => 'qc.work.work_allocation.finish.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@finishIndex']);
        });

        #anh thiet ke
        Route::get('design-image/{productId?}', ['as' => 'qc.work.work_allocation.product_design_image.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@viewDesignImage']);
        #xem anh bao cao truc tiep
        Route::get('report-image/{imageId?}', ['as' => 'qc.work.work_allocation.report_image.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@viewReportImage']);
        #xem anh bao cao qua cham con
        Route::get('report-timekeeping-provisional-image/{imageId?}', ['as' => 'qc.work.work_allocation.timekeeping_provisional_image.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@viewTimekeepingProvisionalImage']);

        // san pham da lam
        Route::group(['prefix' => 'construction'], function () {
            Route::group(['prefix' => 'product'], function () {
                # xac nhan hoan thanh sp
                Route::get('confirm/{productId?}', ['as' => 'qc.work.work_allocation.construction.product.confirm.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@getConstructionProductConfirm']);
                Route::post('confirm/{productId?}', ['as' => 'qc.work.work_allocation.construction.product.confirm.post', 'uses' => 'Work\WorkAllocation\WorkAllocationController@postConstructionProductConfirm']);

                # sản phẩm
                Route::get('/{allocationId?}', ['as' => 'qc.work.work_allocation.construction.product.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@constructionProduct']);
            });
            # xac nhoan thanh sp
            Route::get('confirm/{allocationId?}', ['as' => 'qc.work.work_allocation.construction.confirm.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@getConstructionConfirm']);
            Route::post('confirm/{allocationId?}', ['as' => 'qc.work.work_allocation.construction.confirm.post', 'uses' => 'Work\WorkAllocation\WorkAllocationController@postConstructionConfirm']);

            Route::get('/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.work_allocation.construction.get', 'uses' => 'Work\WorkAllocation\WorkAllocationController@constructionIndex']);
        });

    });

    //chấm công
    Route::group(['prefix' => 'timekeeping'], function () {
        //báo giờ vào
        Route::get('timeBegin/{workId?}', ['as' => 'qc.work.timekeeping.timeBegin.get', 'uses' => 'Work\Timekeeping\TimekeepingController@getTimeBegin']);
        Route::post('timeBegin/', ['as' => 'qc.work.timekeeping.timeBegin.post', 'uses' => 'Work\Timekeeping\TimekeepingController@postTimeBegin']);

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

        //Hủy chấm công
        Route::get('cancel/{timekeepingId?}', ['as' => 'qc.work.timekeeping.cancel.get', 'uses' => 'Work\Timekeeping\TimekeepingController@cancelTimekeeping']);


        Route::get('/', ['as' => 'qc.work.timekeeping.get', 'uses' => 'Work\Timekeeping\TimekeepingController@index']);
    });

    //đổi mật khẩu
    Route::get('change-account', ['as' => 'qc.work.change-account.get', 'uses' => 'Work\WorkController@getChangeAccount']);
    Route::post('change-account', ['as' => 'qc.work.change-account.post', 'uses' => 'Work\WorkController@postChangeAccount']);
    // lương
    Route::group(['prefix' => 'salary'], function () {
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


    //--------------------- CHI ---------------------
    #mua vat tu
    Route::group(['prefix' => 'pay-import'], function () {
        // xem chi tiết
        Route::get('view/{importId?}', ['as' => 'qc.work.import.view.get', 'uses' => 'Work\Pay\Import\ImportController@viewImport']);
        //nhập hóa đơn
        Route::get('add/supplies', ['as' => 'qc.work.import.supplies.get', 'uses' => 'Work\Pay\Import\ImportController@addProduct']);

        Route::get('add', ['as' => 'qc.work.import.add.get', 'uses' => 'Work\Pay\Import\ImportController@getAdd']);
        Route::get('add/image', ['as' => 'qc.work.import.add.image.get', 'uses' => 'Work\Pay\Import\ImportController@getAddImage']);
        Route::get('add/supplies', ['as' => 'qc.work.import.add.supplies.get', 'uses' => 'Work\Pay\Import\ImportController@getAddSupplies']);
        Route::get('add/tool', ['as' => 'qc.work.import.add.tool.get', 'uses' => 'Work\Pay\Import\ImportController@getAddTool']);
        Route::get('add/supplies-tool', ['as' => 'qc.work.import.add.supplies_tool.get', 'uses' => 'Work\Pay\Import\ImportController@getAddSuppliesTool']);
        Route::post('add', ['as' => 'qc.work.import.add.post', 'uses' => 'Work\Pay\Import\ImportController@postAdd']);

        // xác nhận thanh toán
        Route::get('confirm-pay/{importId?}', ['as' => 'qc.work.import.confirm_pay.get', 'uses' => 'Work\Pay\Import\ImportController@getConfirmPay']);
        // xóa
        Route::get('delete/{importId?}', ['as' => 'qc.work.import.delete.get', 'uses' => 'Work\Pay\Import\ImportController@deleteImport']);

        Route::get('/{loginDay?}/{loginMonth?}/{loginYear?}/{loginPayStatus?}', ['as' => 'qc.work.import.get', 'uses' => 'Work\Pay\Import\ImportController@index']);
    });

    #chi hoat dong
    Route::group(['prefix' => 'pay-activity'], function () {
        Route::get('add', ['as' => 'qc.work.pay.pay_activity.add.get', 'uses' => 'Work\Pay\PayActivity\PayActivityController@getAdd']);
        Route::post('add', ['as' => 'qc.work.pay.pay_activity.add.post', 'uses' => 'Work\Pay\PayActivity\PayActivityController@postAdd']);
        // xóa
        Route::get('delete/{payId?}', ['as' => 'qc.work.pay.pay_activity.delete.get', 'uses' => 'Work\Pay\PayActivity\PayActivityController@deletePayActivity']);

        Route::get('/{loginDay?}/{loginMonth?}/{loginYear?}/{loginPayStatus?}', ['as' => 'qc.work.pay.pay_activity.get', 'uses' => 'Work\Pay\PayActivity\PayActivityController@index']);
    });

    # than toan luong
    Route::group(['prefix' => 'pay-salary'], function () {
        Route::get('detail/{payId?}', ['as' => 'qc.work.pay.pay_salary.detail.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@detailPay']);

        Route::get('add/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.add.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@getAdd']);
        Route::post('add/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.add.post', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@postAdd']);

        Route::get('pay/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.pay.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@getPay']);
        Route::post('pay/{salaryId?}', ['as' => 'qc.work.pay.pay_salary.pay.post', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@postPay']);
        // xóa
        //Route::get('delete/{payId?}', ['as' => 'qc.work.pay.pay_salary.delete.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@deletePayActivity']);

        Route::get('/{loginMonth?}/{loginYear?}/{payStatus?}', ['as' => 'qc.work.pay.pay_salary.get', 'uses' => 'Work\Pay\PaySalary\PaySalaryController@index']);
    });

    # chi
    Route::group(['prefix' => 'pay'], function () {
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

    //nhận đồ nghề
    Route::group(['prefix' => 'tool'], function () {
        // xem chi tiết
        Route::get('view/{toolId?}', ['as' => 'qc.work.tool.view.get', 'uses' => 'Work\Tool\ToolController@viewTool']);

        //Route::get('add', ['as' => 'qc.work.import.add.get', 'uses' => 'Work\Import\ImportController@getAdd']);
        //Route::post('add', ['as' => 'qc.work.import.add.post', 'uses' => 'Work\Import\ImportController@postAdd']);

        //xác nhận đồ nghề
        Route::get('confirm-receive/{allocationId?}', ['as' => 'qc.work.tool.confirm_receive.get', 'uses' => 'Work\Tool\ToolController@getConfirmReceive']);

        Route::get('/', ['as' => 'qc.work.tool.get', 'uses' => 'Work\Tool\ToolController@index']);
    });

    //nhận đồ nghề
    Route::group(['prefix' => 'product-type-price'], function () {
        Route::get('/{nameFilter?}', ['as' => 'qc.work.product_type_price.get', 'uses' => 'Work\ProductTypePrice\ProductTypePriceController@index']);
    });

    //phạt
    Route::group(['prefix' => 'minus-money'], function () {
        Route::get('/{monthFilter?}/{yearFilter?}', ['as' => 'qc.work.minus_money.get', 'uses' => 'Work\MinusMoney\MinusMoneyController@index']);
    });

    //nội quy
    Route::get('rules/{code?}', ['as' => 'qc.work.rules', 'uses' => 'Work\WorkController@rules']);

    //làm việc
    Route::get('work/{loginMonth?}/{loginYear?}', ['as' => 'qc.work.work.get', 'uses' => 'Work\WorkController@work']);

    Route::get('/', ['as' => 'qc.work.home', 'uses' => 'Work\WorkController@index']);
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