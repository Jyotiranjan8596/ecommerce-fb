<?php

use App\Http\Controllers\Admin\PosController as AdminPosController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Pos\AuthController;
use App\Http\Controllers\PosAuthController;
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



//frontend

Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
Route::get('/category/{id}', [FrontendController::class, 'category'])->name('frontend.category');
Route::get('/product/{id}', [FrontendController::class, 'product'])->name('frontend.product');
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

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['user']], function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('index');
    Route::get('edit/profile', [UserDashboardController::class, 'editprofile'])->name('edit.profile');
    Route::post('update/profile/', [UserDashboardController::class, 'updateprofile'])->name('update.profile');
    Route::get('change/password', [UserDashboardController::class, 'changepassword'])->name('change.password');
    Route::post('new/password', [UserDashboardController::class, 'newpassword'])->name('new.password');
    Route::get('add', [UserDashboardController::class, 'addUser'])->name('add');
    Route::post('add-store', [UserDashboardController::class, 'storeUser'])->name('add.store');
    Route::get('term-condition', [UserDashboardController::class, 'termCondition'])->name('term.condition');
    Route::get('sponsor/list', [UserDashboardController::class, 'sponsorList'])->name('sponsor.list');
    Route::get('pos/list', [UserDashboardController::class, 'posList'])->name('pos.list');
    Route::post('payment', [UserDashboardController::class, 'payment'])->name('payment');
    // Route::post('payment/verify/$id', [UserDashboardController::class, 'verifyPayment'])->name('verify.payment');
    Route::get('my/wallet', [UserDashboardController::class, 'wallet'])->name('wallet');
});
Route::group(['prefix' => 'pos', 'as' => 'pos.', 'middleware' => ['pos']], function () {
    Route::get('/', [PosController::class, 'index'])->name('index');
    Route::get('userlist', [PosController::class, 'userList'])->name('user.list');
    Route::get('walletmanage/{id}', [WalletController::class, 'walletManage'])->name('wallet.manage');
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
});

Route::get('/admin/pos_system/download/{id}/{name}', [AdminPosController::class, 'download_qr'])->name('admin.pos_system.download');
