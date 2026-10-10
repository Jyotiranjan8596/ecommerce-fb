<?php

use App\Http\Controllers\Admin\PosController as AdminPosController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DownloadPdfController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaymentSummaryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Shared in-app notification bell endpoints — used by Admin, POS and Customer navbars alike.
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('read');
    Route::post('/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('read-all');
});

// Route::view('/term-and-conditions', 'frontend.dashboard.termcondition')->name('termCondition');

Route::get('/term-and-conditions', [FrontendController::class, 'termCondition'])->name('frontend.termCondition');
Route::view('/faqs', 'ui.faq')->name('frontend.faq');
//frontend

Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
Route::get('/offer', [FrontendController::class, 'offer'])->name('frontend.offer');
Route::get('/category/{id}', [FrontendController::class, 'category'])->name('frontend.category');
Route::get('/product/{id}', [FrontendController::class, 'product'])->name('frontend.product');

// Route::get('/page/{id}', [FrontendController::class, 'page'])->name('frontend.page.index');
Route::get('/page/aboutus', [FrontendController::class, 'page'])->name('page.aboutus');

// Route::get('/category/{id}', [FrontendController::class, 'categoryView'])->name('frontend.categoryView');
Route::get('/subsector/{id}', [FrontendController::class, 'subsector'])->name('frontend.subsector');
Route::get('/viewsubsector/{id}', [FrontendController::class, 'viewSubsector'])->name('subsector.view');
Route::get('/blog/category', [FrontendController::class, 'blogCategory'])->name('frontend.blogcategory');
Route::get('/blog/{id}', [FrontendController::class, 'view'])->name('blog.view');
Route::get('/blog/view/{id}', [FrontendController::class, 'Readmore'])->name('blog.readmore');
Route::get('/highvalue/dataset/{id}', [FrontendController::class, 'dataset'])->name('highvalue.dataset');
Route::get('/most-viewed/highvalue/dataset', [FrontendController::class, 'datasetView'])->name('highvalue.datasetview');
Route::get('/highvalue/recently-added', [FrontendController::class, 'recentlyAdded'])->name('highvalue.recentlyAdded');
Route::get('/event/category', [FrontendController::class, 'eventCategory'])->name('frontend.eventCategory');
Route::get('/event/{id}', [FrontendController::class, 'eventView'])->name('event.view');
Route::get('/event/view/{id}', [FrontendController::class, 'eventReadmore'])->name('event.readmore');
Route::get('/infographics', [FrontendController::class, 'infographics'])->name('frontend.infographics');
// Route::get('/cart', [FrontendController::class, 'cart'])->name('frontend.cart');
// Route::get('/cart/add/{id}', [FrontendController::class, 'addToCart'])->name('cart.add');
// Route::post('/increase/quantity/{id}', [FrontendController::class, 'increaseQuantity'])->name('increase.quantity');
// Route::post('/decrease/quantity/{id}', [FrontendController::class, 'decreaseQuantity'])->name('decrease.quantity');
// Route::delete('/delete/{id}', [FrontendController::class, 'delete'])->name('cart.delete');

//frontend login
// Route::get('/login/user', [LoginController::class, 'login'])->name('auth.login');
// Route::post('/login/store', [LoginController::class, 'store'])->name('login.store');
// Route::get('/register/user', [LoginController::class, 'register'])->name('auth.register');
// Route::post('register/store', [LoginController::class, 'storeRegister'])->name('register.store');
Route::get('/cart', [CartController::class, 'cart'])->name('frontend.cart');
Route::get('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
Route::post('/cart/increase/{id}', [CartController::class, 'increaseQuantity'])->name('increase.quantity');
Route::post('/cart/decrease/{id}', [CartController::class, 'decreaseQuantity'])->name('decrease.quantity');
Route::get('/cart/checkout', [CartController::class, 'checkOut'])->name('cart.checkout');

Route::group(['middleware' => 'user'], function () {
    //carts

    //logout
    // Route::get('/logout/user', [LoginController::class, 'logout'])->name('auth.logout');
});

// //user dashboard
// Route::get('user/dashboard',[UserDashboardController::class,'index'])->name('dashboard.index');
// Route::get('user/editprofile/{id}',[UserDashboardController::class,'editprofile'])->name('edit.profile');
// Route::get('user/profile/{id}',[UserDashboardController::class,'profile'])->name('user.profile');
// Route::post('user/update/profile',[UserDashboardController::class, 'update'])->name('profile.update');
// Route::get('/dashboard/cart', [UserDashboardController::class, 'cart'])->name('dashboard.cart');
// Route::delete('/dashboard/cart/delete/{id}', [UserDashboardController::class, 'delete'])->name('dashboard.deletecart');

Route::get('pos-list', [UserDashboardController::class, 'pos_list_index'])->name('frontend.pos.list');
Route::get('pos-list/data', [UserDashboardController::class, 'posList'])->name('frontend.pos.list.data');

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['user']], function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('index');
    Route::get('prime-dashboard', [UserDashboardController::class, 'primeDashboard'])->name('prime.dashboard');
    Route::get('prime-profile', [UserDashboardController::class, 'primeProfile'])->name('prime.profile');
    Route::get('edit/profile', [UserDashboardController::class, 'editprofile'])->name('edit.profile');
    Route::post('update/profile/', [UserDashboardController::class, 'updateprofile'])->name('update.profile');
    Route::post('profile/image', [UserDashboardController::class, 'updateProfileImage'])->name('profile.image.update');
    Route::get('change/password', [UserDashboardController::class, 'changepassword'])->name('change.password');
    Route::post('new/password', [UserDashboardController::class, 'newpassword'])->name('new.password');
    Route::get('add', [UserDashboardController::class, 'addUser'])->name('add');
    Route::post('add-store', [UserDashboardController::class, 'storeUser'])->name('add.store');
    Route::get('term-condition', [UserDashboardController::class, 'termCondition'])->name('term.condition');
    Route::get('sponsor/list', [UserDashboardController::class, 'sponsorList'])->name('sponsor.list');
    Route::post('pos/list', [UserDashboardController::class, 'posList'])->name('pos.list');
    Route::get('pos/list/index', [UserDashboardController::class, 'pos_list_index'])->name('pos.list.index');
    Route::get('my-order', [CustomerOrderController::class, 'create'])->name('order');
    Route::post('my-order', [CustomerOrderController::class, 'store'])->name('order.store');
    Route::get('my-order/{order}/edit', [CustomerOrderController::class, 'edit'])->name('order.edit');
    Route::put('my-order/{order}', [CustomerOrderController::class, 'update'])->name('order.update');
    Route::get('my-order/{order}/handwritten-list', [CustomerOrderController::class, 'downloadList'])->name('order.list.download');
    Route::post('payment', [UserDashboardController::class, 'payment'])->name('payment');
    // Route::post('payment/verify/$id', [UserDashboardController::class, 'verifyPayment'])->name('verify.payment');
    Route::get('my/wallet', [UserDashboardController::class, 'wallet'])->name('wallet');
});
Route::group(['prefix' => 'pos', 'as' => 'pos.', 'middleware' => ['pos']], function () {
    Route::get('/', [PosController::class, 'index'])->name('index');
    Route::get('customer-orders', [CustomerOrderController::class, 'posIndex'])->name('customer-orders.index');
    Route::patch('customer-orders/{order}/status', [CustomerOrderController::class, 'updateStatus'])->name('customer-orders.status');
    Route::get('customer-orders/{order}/handwritten-list', [CustomerOrderController::class, 'downloadPosList'])->name('customer-orders.list.download');
    Route::get('profile/index', [PosController::class, 'pos_profile_index'])->name('profile.index');
    Route::post('update/profile/', [PosController::class, 'updateprofile'])->name('update.profile');
    Route::get('change/password', [PosController::class, 'changepassword'])->name('change.password');
    Route::post('new/password', [PosController::class, 'newpassword'])->name('new.password');
    Route::get('dsr', [WalletController::class, 'dsr'])->name('dsr');
    Route::get('msr', [WalletController::class, 'msr'])->name('msr');
    Route::get('export-msr', [WalletController::class, 'exportMsr'])->name('msr.export');
    Route::post('dsrlist', [WalletController::class, 'dsrList'])->name('dsr.list');
    Route::get('export-dsr', [WalletController::class, 'export'])->name('dsr.export');
    Route::post('import-dsr', [WalletController::class, 'import'])->name('dsr.import');
    Route::get('journal', [WalletController::class, 'journal'])->name('journal');
    Route::get('unverified/user', [WalletController::class, 'unverified'])->name('unverified.user');
    Route::put('customers/{id}', [WalletController::class, 'update'])->name('customers.update');
    Route::get('wallet/update-status/{id}', [WalletController::class, 'updateStatus'])->name('wallet.updateStatus');
    Route::post('verify-all/user', [PosController::class, 'verifyAllCustomer'])->name('verify.all.user');
    Route::post('dsr/verify', [PosController::class, 'verifyDsr'])->name('dsr.verify.transaction');
    Route::post('initiate-payment', [PosController::class, 'initiate_payment'])->name('initiate.payment');
    Route::get('receipt-journal', [PaymentSummaryController::class, 'sattlement_index'])->name('sattlement');
    Route::post('export-settlement', [PaymentSummaryController::class, 'export_settlement'])->name('export.settlement');
    Route::post('/save-modal-data', [PaymentSummaryController::class, 'saveSummaryData'])->name('save.summary.data');
    Route::get('pos/settlement/invoice/{id}', [PaymentSummaryController::class, 'downloadPosInvoice'])->name('settlement.invoice');
    Route::get('payment/index', [PaymentSummaryController::class, 'payment_index_pos'])->name('payment.index');
    Route::post('get-payment-details', [PosController::class, 'pos_payment_details'])->name('get.payment.details');
    Route::post('create/payment', [PaymentSummaryController::class, 'create_payment'])->name('create.payment');
    Route::get('account/ledger', [PaymentSummaryController::class, 'pos_ledger_index'])->name('ledger.index');
    Route::post('get/ledger/data', [PaymentSummaryController::class, 'getledger'])->name('get.ledger');
    Route::post('ledger/export', [PaymentSummaryController::class, 'ledgerExport'])->name('ledger.export');
    Route::post('get/receipt/journal', [PaymentSummaryController::class, 'getReceipt'])->name('get.receipt');
    Route::post('store-receipt', [PaymentSummaryController::class, 'store_receipt'])->name('store.receipt');
});
Route::get('terms-conditions-pos', [PosController::class, 'terms_conditions'])->name('terms.conditions');
Route::post('verify-pos', [PosController::class, 'verifyAllPos'])->name('verify.all_pos');

Route::get('/admin/pos_system/download/{id}/{name}', [AdminPosController::class, 'download_qr'])->name('admin.pos_system.download');
Route::get('/ledger/download-pdf', [DownloadPdfController::class, 'downloadLedgerPdf'])->name('ledger.download-pdf');
Route::get('reset-password', [ResetPasswordController::class, 'index'])->name('reset.password');
Route::post('update-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset.otp');
