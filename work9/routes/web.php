<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\inventory\CategoryController;

Route::redirect('/', '/login');
Route::get('home', 'HomeController@index')->name('home.index');
Route::get('user/{id}', 'UserController@changePassword')->name('user.changePassword');
Route::post('user/changePasswordSave', 'UserController@changePasswordSave')->name('user.changePasswordSave');
Route::resource('user', 'UserController')->middleware('can:admin-only');
Route::resource('customer', 'CustomerController')->middleware('can:store-keeper-only');


Route::resource('warehouse', 'inventory\WarehouseController')->middleware('can:director-only');
Route::resource('category', 'inventory\CategoryController')->middleware('can:director-only');
Route::resource('product', 'inventory\ProductController')->middleware('auth');
Route::resource('inventory', 'inventory\InventoryController')->middleware('auth');
Route::get('purchase/generatePDF/{id}', 'inventory\PurchaseController@generatePDF')->name('purchase.generatePDF');
Route::resource('purchase', 'inventory\PurchaseController')->middleware('auth');
Route::resource('purchaseDetails', 'inventory\PurchaseDetailsController')->middleware('can:director-only');
Route::get('grn/generatePDF/{id}', 'inventory\GRNController@generatePDF')->name('grn.generatePDF');
Route::resource('grn', 'inventory\GRNController')->middleware('auth');
Route::get('invoice/print/{id}', 'inventory\InvoiceController@print')->name('invoice.print');
Route::get('invoice/printVAT/{id}', 'inventory\InvoiceController@printVAT')->name('invoice.printVAT');
Route::get('invoice/invoiceSummary', 'inventory\InvoiceController@invoiceSummary')->name('invoice.invoiceSummary');
Route::resource('invoice', 'inventory\InvoiceController')->middleware('can:managers-only');
Route::resource('invoiceDetails', 'inventory\InvoiceDetailsController')->middleware('can:managers-only');
Route::get('returnItems/giveInvoiceList/{customer_id}', 'inventory\ReturnItemsController@giveInvoiceList')->name('returnItems.giveInvoiceList');
Route::get('returnItems/fetch/{id}', 'inventory\ReturnItemsController@fetch')->name('returnItems.fetch');
Route::get('returnItems/giveInvoiceDetails/{id}', 'inventory\ReturnItemsController@giveInvoiceDetails')->name('returnItems.giveInvoiceDetails');
Route::resource('returnItems', 'inventory\ReturnItemsController')->middleware('can:director-only');


Route::resource('bank', 'financials\BankController')->middleware('can:director-only');
Route::resource('bankDetails', 'financials\BankDetailsController')->middleware('can:director-only');
Route::resource('cash', 'financials\CashController')->middleware('can:director-only');
Route::get('cheque/returnCheque/{cheque}', 'financials\ChequeController@returnCheque')->name('cheque.returnCheque')->middleware('can:director-only');
Route::post('cheque/passCheque/{cheque}', 'financials\ChequeController@passCheque')->name('cheque.passCheque');
Route::resource('cheque', 'financials\ChequeController')->middleware('can:director-only');
Route::resource('expense', 'financials\ExpenseController')->middleware('can:managers-only');
Route::get('payment/print/{id}', 'financials\PaymentController@print')->name('payment.print');
Route::resource('payment', 'financials\PaymentController')->middleware('can:managers-only');
Route::get('LinkInvoice/link/{id}', 'financials\LinkInvoiceController@link')->name('LinkInvoice.link')->middleware('can:managers-only');
Route::resource('LinkInvoice', 'financials\LinkInvoiceController')->middleware('can:managers-only');
Route::get('LinkJob/link/{id}', 'financials\LinkJobController@link')->name('LinkJob.link')->middleware('can:managers-only');
Route::resource('LinkJob', 'financials\LinkJobController')->middleware('can:managers-only');
Route::resource('paymentReceive', 'financials\PaymentReceiveController')->middleware('can:director-only');
Route::resource('commission', 'financials\CommissionController')->middleware('can:managers-only');
Route::resource('writeoff', 'financials\WriteoffController')->middleware('can:managers-only');


Route::resource('ComponentCategory', 'technical\ComponentCategoryController')->middleware('can:managers-only');
Route::resource('component', 'technical\ComponentController')->middleware('can:managers-only');
Route::resource('machineModel', 'technical\MachineModelController')->middleware('can:managers-only');
Route::resource('stocks', 'technical\StockController')->middleware('can:tech-executive-only');
Route::resource('componentPurchase', 'technical\ComponentPurchaseController')->middleware('can:managers-only');
Route::get('jobs/printDetail/{id}', 'technical\JobController@printDetail')->name('jobs.printDetail');
Route::get('jobs/print/{id}', 'technical\JobController@print')->name('jobs.print');
Route::get('jobs/suspend/{job}', 'technical\JobController@suspend')->name('jobs.suspend');
Route::get('jobs/estimate/{job}', 'technical\JobController@estimate')->name('jobs.estimate');
Route::post('jobs/changeWarranty/{job}', 'technical\JobController@changeWarranty')->name('jobs.changeWarranty');
Route::resource('issues', 'technical\IssueController')->middleware('auth');
Route::get('jobs/jobSummary', 'technical\JobController@jobSummary')->name('jobs.jobSummary');
Route::resource('jobs', 'technical\JobController')->middleware('auth');


Route::resource('CourierCustomer', 'courier\CourierCustomerController')->middleware('can:store-keeper-only');
Route::resource('CourierPickup', 'courier\CourierPickupController')->middleware('can:store-keeper-only');
Route::resource('CourierPacking', 'courier\CourierPackingController')->middleware('can:store-keeper-only');

Route::resource('chequeCustomerReport', 'reports\ChequeCustomerReportController')->middleware('can:managers-only');
Route::resource('InvoiceProductCustomer', 'reports\InvoiceProductCustomerController')->middleware('can:managers-only');
Route::resource('InventoryReport', 'reports\InventoryReportController')->middleware('can:managers-only');
Route::resource('Outstanding', 'reports\OutstandingController')->middleware('can:managers-only');
Route::resource('chequeReport', 'reports\ChequeReportController')->middleware('can:managers-only');
Route::resource('InvoicePayment', 'reports\InvoicePaymentController')->middleware('can:managers-only');
Route::resource('RepairModels', 'reports\RepairModelsController')->middleware('auth');
Route::resource('AllRepairJobs', 'reports\AllJobDetailsController')->middleware('auth');



Route::get('/request/create', 'PagesController@create');
Route::post('/request/{testID}', 'PagesController@baba')->name('request.baba');

Route::get('/dashboard', function () {
    
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
