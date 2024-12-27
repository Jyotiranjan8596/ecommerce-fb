<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\DsrController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\InfoGraphicController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SubSectorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WalletController;

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['admin']], function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile-update', [ProfileController::class, 'update'])->name('profile.update');

    //password
    Route::get('change-password', [ProfileController::class, 'password'])->name('password.index');
    Route::put('update-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    //user
    Route::resource('users', UserController::class);
    Route::get('user-ban-unban/{id}/{status}', 'UserController@banUnban')->name('user.banUnban');
    Route::get('user-customer', [UserController::class, 'customUser'])->name('user.customer');
    Route::get('search-sponsor', [UserController::class, 'searchSponsor'])->name('search.sponsor');


    // Route::get('user/edit/{$id}', [UserController::class,'userEdit'])->name('user.edit');
    Route::post('user-customer/store', [UserController::class, 'storeCustomUser'])->name('user.customer-store');
    Route::post('import/user', [UserController::class, 'importUser'])->name('user.import');
    Route::get('user/export', [UserController::class, 'export'])->name('user.export');


    //roles
    Route::resource('roles', RoleController::class);

    //permission
    Route::resource('permissions', PermissionController::class);

    //banner
    Route::resource('banner', BannerController::class);
    Route::post('banner/{id}/update', [BannerController::class, 'update'])->name('banner.update');

    //sector
    Route::resource('sector', SectorController::class);
    Route::post('sector/{id}/update', [SectorController::class, 'update'])->name('sector.update');

       //sub-sector
       Route::resource('subsector', SubSectorController::class);
       Route::post('subsector/{id}/update', [SubSectorController::class, 'update'])->name('subsector.update');

    //Blog
    Route::resource('blog', BlogController::class);
    Route::post('blog/{id}/update', [BlogController::class, 'update'])->name('blog.update');

    //Blog Category
    Route::resource('blog_category', BlogCategoryController::class);
    Route::post('blog_category/{id}/update', [BlogCategoryController::class, 'update'])->name('blog_category.update');

    //Event Category
    Route::resource('event_category', EventCategoryController::class);
    Route::post('event_category/{id}/update', [EventCategoryController::class, 'update'])->name('event_category.update');

    //Event
    Route::resource('event', EventController::class);
    Route::post('event/{id}/update', [EventController::class, 'update'])->name('event.update');

    //Info Graphic
    Route::resource('info_graphic', InfoGraphicController::class);
    Route::post('info_graphic/{id}/update', [InfoGraphicController::class, 'update'])->name('info_graphic.update');

    //Product
    Route::resource('product', ProductController::class);
    Route::post('product/{id}/update', [ProductController::class, 'update'])->name('product.update');
    Route::post('/get-subsector', [ProductController::class, 'getSubsector'])->name('getSubsector');
    Route::get('/export-product',[ProductController::class, 'export'])->name('product.export');
    Route::post('/import-product',[ProductController::class, 'import'])->name('product.import');

    //pos
    Route::resource('pos_system', PosController::class);
    Route::post('pos_system/{id}/update', [PosController::class, 'update'])->name('pos_system.update');

    //dsr
    Route::get('dsr', [DsrController::class, 'dsr'])->name('dsr');
    Route::get('export-dsr', [DsrController::class, 'export'])->name('dsr.export');
    Route::post('import-dsr', [DsrController::class, 'import'])->name('dsr.import');

    Route::get('msr', [DsrController::class, 'msr'])->name('msr');
   
    Route::get('export-msr', [DsrController::class, 'exportMsr'])->name('msr.export');

    Route::get('wallet', [WalletController::class, 'wallet'])->name('wallet');
    Route::get('/export-wallet', [WalletController::class, 'exportWallet'])->name('wallet.export');
    Route::post('/upload-wallet', [WalletController::class, 'uploadWallet'])->name('wallet.upload');
});
