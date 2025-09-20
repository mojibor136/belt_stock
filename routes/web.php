<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorTrxController;
use App\Http\Controllers\CustomerTrxController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->middleware(['auth'])->group(function(){
    Route::get('/' , 'index')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/setting', 'index')->name('setting.index');
        Route::post('/setting/create', 'create')->name('setting.create');
    });

    Route::controller(SizeController::class)->group(function () {
        Route::get('/sizes', 'index')->name('sizes.index');
        Route::get('/sizes/create' , 'create')->name('sizes.create');
        Route::post('/sizes/store' , 'store')->name('sizes.store');
        Route::get('/sizes/edit/{id}' , 'edit')->name('sizes.edit');
        Route::post('/sizes/update' , 'update')->name('sizes.update');
        Route::delete('/sizes/destroy/{id}' , 'destroy')->name('sizes.destroy');
        Route::get('sizes/show/{id}' , 'show')->name('sizes.show');
    });

    Route::controller(GroupController::class)->group(function () {
        Route::get('/groups', 'index')->name('groups.index');
        Route::get('/groups/create' , 'create')->name('groups.create');
        Route::post('/groups/store' , 'store')->name('groups.store');
        Route::get('/groups/edit/{id}' , 'edit')->name('groups.edit');
        Route::post('/groups/update' , 'update')->name('groups.update');
        Route::delete('/groups/destroy/{id}' , 'destroy')->name('groups.destroy');
        Route::get('groups/show/{id}' , 'show')->name('groups.show');
    });

    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands', 'index')->name('brands.index');
        Route::get('/brands/create' , 'create')->name('brands.create');
        Route::post('/brands/store' , 'store')->name('brands.store');
        Route::get('/brands/edit/{id}' , 'edit')->name('brands.edit');
        Route::post('/brands/update' , 'update')->name('brands.update');
        Route::delete('/brands/destroy/{id}' , 'destroy')->name('brands.destroy');
        Route::get('brands/show/{id}' , 'show')->name('brands.show');
    });

    Route::controller(SalesController::class)->group(function () {
        Route::get('/sales', 'index')->name('sales.index');
    });

    Route::controller(MemoController::class)->group(function () {
        Route::get('/memo/create', 'index')->name('memo.create');
        Route::post('/memo/store', 'store')->name('memo.store');
        Route::get('/memo/show/{id}' , 'show')->name('memo.show');
        Route::get('/memo/edit/{id}' , 'edit')->name('memo.edit');
        Route::post('/memo/update' , 'update')->name('memo.update');
        Route::get('/memo/pending' , 'pending')->name('memo.pending');
        Route::get('/memo/complete' , 'complete')->name('memo.complete');
        Route::get('memo/status/{id}' , 'status')->name('memo.status');
        Route::delete('/memo/destroy/{id}' , 'destroy')->name('memo.destroy');
    });

    Route::controller(VendorController::class)->group(function () {
        Route::get('/vendor', 'index')->name('vendor.index');
        Route::get('/vendor/create' , 'create')->name('vendor.create');
        Route::post('/vendor/store' , 'store')->name('vendor.store');
        Route::get('/vendor/edit/{id}' , 'edit')->name('vendor.edit');
        Route::post('/vendor/update' , 'update')->name('vendor.update');
        Route::get('/vendor/analysis/{name}/{id}' , 'vendorAnalysis')->name('vendor.analysis');
        Route::delete('/vendor/destroy/{id}' , 'destroy')->name('vendor.destroy');
    });

    Route::controller(VendorTrxController::class)->group(function () {
        Route::get('/vendor/payment' , 'payment')->name('vendor.payment');
        Route::get('/vendor/invoice' , 'invoice')->name('vendor.invoice');
        Route::get('/vendor/transaction' , 'transaction')->name('vendor.transaction');
        Route::get('/vendor/transaction/{name}/{id}' , 'vendorTransaction')->name('vendor.all.transaction');
        Route::delete('/vendor/transaction/destroy/{id}' , 'transactionDestroy')->name('vendor.transactions.destroy');
        Route::post('/vendor/payment/store' , 'paymentStore')->name('vendor.payment.store');
        Route::post('/vendor/invoice/store' , 'invoiceStore')->name('vendor.invoice.store');
    });

    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer', 'index')->name('customer.index');
        Route::get('/customer/create' , 'create')->name('customer.create');
        Route::get('/customer/edit/{id}' , 'edit')->name('customer.edit');
        Route::delete('/customer/destroy/{id}' , 'destroy')->name('customer.destroy');
        Route::post('/customer/store' , 'store')->name('customer.store');
        Route::post('/customer/update' , 'update')->name('customer.update');
        Route::get('/customer/sales/mojibor' , 'customerSales')->name('customer.sales');
        Route::get('/customer/analysis/{name}/{id}' , 'customerAnalysis')->name('customer.analysis');
    });

    Route::controller(CustomerTrxController::class)->group(function () {
        Route::get('/customer/payment' , 'payment')->name('customer.payment');
        Route::get('/customer/invoice' , 'invoice')->name('customer.invoice');
        Route::get('/customer/transaction' , 'transaction')->name('customer.transaction');
        Route::get('/customer/transaction/{name}/{id}' , 'customerTransaction')->name('customer.all.transaction');
        Route::delete('/customer/transaction/destroy/{id}' , 'transactionDestroy')->name('customer.transactions.destroy');
        Route::post('/customer/payment/store' , 'paymentStore')->name('customer.payment.store');
        Route::post('/customer/invoice/store' , 'invoiceStore')->name('customer.invoice.store');
    });

    Route::controller(StockController::class)->group(function () {
        Route::get('/stocks', 'index')->name('stocks.index');
        Route::get('/stocks/create' , 'create')->name('stocks.create');
        Route::get('/stocks/history' , 'history')->name('stocks.history');
        Route::get('/stocks/exhausted' , 'exhausted')->name('stocks.exhausted');
        Route::get('/stocks/warnings' , 'warnings')->name('stocks.warnings');
        Route::post('/stocks/store' , 'store')->name('stocks.store');
        Route::delete('/stocks/destroy/{id}' , 'destroy')->name('stocks.destroy');
        Route::get('/stocks/edit/{id}' , 'edit')->name('stocks.edit');
        Route::post('/stocks/update' , 'update')->name('stocks.update');
    });

    Route::controller(SettingController::class)->group(function () {
        Route::get('/account/setting', 'account')->name('account.setting');
        Route::post('/account/store', 'accountStore')->name('account.store');
        Route::get('/general/setting' , 'general')->name('general.setting');
        Route::post('/general/store', 'generalStore')->name('general.store');
        Route::get('/system/setting' , 'system')->name('system.setting');
        Route::post('/system/store', 'systemStore')->name('system.store');
    });

    Route::get('/get-rate-type/{brand}/{group}/{size}', [MemoController::class, 'checkRateType']);
    Route::get('/check-quantity/{brand}/{group}/{size}', [MemoController::class, 'checkQuantity']);
    Route::get('/get-sizes-by-group', [SizeController::class, 'getSizesByGroup']);
    Route::get('/get-groups-by-brand', [SizeController::class, 'getGroupsByBrand'])->name('get.groups.by.brand');
    Route::get('/get-group-rate', [SizeController::class, 'getGroupRate'])->name('get.group.rate');
    Route::get('/get-groups/{brand_id}', [SizeController::class, 'getGroups']);
    Route::get('/get-group-rate/{group_id}', [GroupController::class, 'getGroupRate'])->name('getGroupRate');
    Route::get('/get-group-data/{group}', [SalesController::class, 'getGroupData']);
    Route::get('/get-customer-data/{id}', [CustomerController::class, 'getCustomerData']);
});

require __DIR__.'/auth.php';
