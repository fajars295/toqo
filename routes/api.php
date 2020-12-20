<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('login', 'Api\Auth\UserController@eror')->name('login');
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Api\Auth\UserController@Login');
    Route::post('signup', 'Api\Auth\UserController@Register');
    Route::post('Upload-Fcm-Token', 'Api\Content\FcmTokenController@store');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'Api\Auth\UserController@logout');
        Route::get('GetProfile', 'Api\Auth\UserController@GetProfile');
        Route::post('Update-New', 'Api\Auth\UserController@UpdateNew');
        Route::post('Update', 'Api\Auth\UserController@UpdateProfile');
        Route::post('Update-Foto', 'Api\Auth\UserController@UpdateFoto');
        Route::get('List-Fcm-Token', 'Api\Content\FcmTokenController@List');
        Route::get('Delete-Fcm-Token/{delete}', 'Api\Content\FcmTokenController@Destroy');
        Route::get('ClaimLoyalty', 'Api\Auth\Loyalty@ClaimLoyalty');
    });
});
Route::group([
    'prefix' => 'Benner',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Content\BennerController@store');
    Route::post('update', 'Api\Content\BennerController@Updatedata');
    Route::get('list/{code}', 'Api\Content\BennerController@List');
    Route::get('delete/{code}', 'Api\Content\BennerController@Destroy');
});

Route::group([
    'prefix' => 'TypeCategory',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Product\TypeCategoryController@store');
    Route::post('update', 'Api\Product\TypeCategoryController@Updatedata');
    Route::get('list', 'Api\Product\TypeCategoryController@List');
    Route::get('delete/{code}', 'Api\Product\TypeCategoryController@Destroy');
});
Route::group([
    'prefix' => 'Category',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Product\CategoryController@store');
    Route::post('update', 'Api\Product\CategoryController@Updatedata');
    Route::get('list', 'Api\Product\CategoryController@List');
    Route::get('list-by-Type-Category/{code}', 'Api\Product\CategoryController@ListTypeCategory');
    Route::get('delete/{code}', 'Api\Product\CategoryController@Destroy');
});
Route::group([
    'prefix' => 'TypeSpesifikasi',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Product\TypeSpesifikasiController@store');
    Route::post('update', 'Api\Product\TypeSpesifikasiController@Updatedata');
    Route::get('list', 'Api\Product\TypeSpesifikasiController@List');
    Route::get('delete/{code}', 'Api\Product\TypeSpesifikasiController@Destroy');
});
Route::group([
    'prefix' => 'Product',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Product\ProductController@store');
    Route::get('list', 'Api\Product\ProductController@GetProduct');
    Route::get('list-category/{code}', 'Api\Product\ProductController@ProductCatagory');
    Route::get('list-type-category/{code}', 'Api\Product\ProductController@ProductTypeCatagory');
    Route::get('Product-Promo-Or-Diskon', 'Api\Product\ProductController@ProductPromoOrDiskon');
    Route::get('Product-Casback', 'Api\Product\ProductController@ProductCasback');
    Route::get('Product-Gratis-Ongkir', 'Api\Product\ProductController@ProductGratisOnkir');
    Route::get('Product-COD', 'Api\Product\ProductController@ProductCOD');
    Route::get('Product-Brand-Baru', 'Api\Product\ProductController@ProductBrandBaru');


    Route::get('list-populer', 'Api\Product\ProductController@ProductPopuler');
    Route::get('list-terlaris', 'Api\Product\ProductController@ProductTerlaris');
    Route::post('update', 'Api\Product\ProductController@updatedata');
    Route::get('detail/{code}', 'Api\Product\ProductController@DetailsProduct');
    Route::get('delete/{code}', 'Api\Product\ProductController@DeleteProduct');
    Route::post('add-foto-product', 'Api\Product\ProductController@TambahFoto');
    Route::get('delete-foto-product/{code}', 'Api\Product\ProductController@DeleteFoto');
    Route::post('add-Spesifikasi-product', 'Api\Product\ProductController@TambahSpesifikasi');
    Route::get('delete-Spesifikasi-product/{code}', 'Api\Product\ProductController@DeleteSpesifikasi');
    Route::post('update-stock-product', 'Api\Product\ProductController@PenambahanStock');
    Route::get('history-product/{code}', 'Api\Product\ProductController@HistoryProduct');
    Route::get('like-product/{code}', 'Api\Product\ProductController@LikeProduct');
    Route::get('Unlike-product/{code}', 'Api\Product\ProductController@UnLikeProduct');
    Route::get('list-favorit', 'Api\Product\ProductController@ProductFavorit');
    Route::post('add-product-favorit', 'Api\Product\ProductController@AddProduct');
});

Route::group([
    'prefix' => 'Card',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Pembelian\CardController@store');
    Route::get('list', 'Api\Pembelian\CardController@List');
    Route::get('delete/{code}', 'Api\Pembelian\CardController@Destroy');
});
Route::group([
    'prefix' => 'Rekening',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Pembelian\RekeningController@store');
    Route::post('update', 'Api\Pembelian\RekeningController@Updatedata');
    Route::get('list', 'Api\Pembelian\RekeningController@List');
    Route::get('delete/{code}', 'Api\Pembelian\RekeningController@Destroy');
});
Route::group([
    'prefix' => 'InfoCategory',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Info\CategoryInfoController@store');
    Route::post('update', 'Api\Info\CategoryInfoController@Updatedata');
    Route::get('list', 'Api\Info\CategoryInfoController@List');
    Route::get('delete/{code}', 'Api\Info\CategoryInfoController@Destroy');
});
Route::group([
    'prefix' => 'Info',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Info\InfoController@store');
    Route::post('update', 'Api\Info\InfoController@Updatedata');
    Route::get('list', 'Api\Info\InfoController@List');
    Route::get('detail/{code}', 'Api\Info\InfoController@detail');
    Route::get('list-Info-Category/{code}', 'Api\Info\InfoController@GetInfoCategory');
    Route::get('delete/{code}', 'Api\Info\InfoController@Destroy');
});

Route::group([
    'prefix' => 'Content',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Content\ContentController@store');
    Route::post('update', 'Api\Content\ContentController@Updatedata');
    Route::get('list', 'Api\Content\ContentController@List');
    Route::get('detail/{code}', 'Api\Content\ContentController@detail');
    Route::get('delete/{code}', 'Api\Content\ContentController@Destroy');
    Route::get('Like-Content/{code}', 'Api\Content\ContentController@LikeContent');
    Route::get('Un-Like-Content/{code}', 'Api\Content\ContentController@UnLikeContent');
    Route::post('Comment-Content', 'Api\Content\ContentController@CommentContent');
    Route::get('Comment-delete-Content/{code}', 'Api\Content\ContentController@CommentContentDelete');
});
Route::group([
    'prefix' => 'Notifikasi',
    'middleware' => 'auth:api'
], function () {
    Route::get('list', 'Api\Content\NotifikasiController@List');
});
Route::group([
    'prefix' => 'Alamat',
    'middleware' => 'auth:api'
], function () {
    Route::get('provisi', 'Api\Auth\AlamatController@GetProvinsi');
    Route::get('kabupaten/{code}', 'Api\Auth\AlamatController@GetKabupaten');
    Route::get('kecamatan/{code}', 'Api\Auth\AlamatController@GetKecamatan');
    Route::get('kelurahan/{code}', 'Api\Auth\AlamatController@GetKelurahan');

    Route::post('create', 'Api\Auth\AlamatController@store');
    Route::post('update', 'Api\Auth\AlamatController@Updatedata');
    Route::get('list', 'Api\Auth\AlamatController@List');
    Route::get('detail/{code}', 'Api\Auth\AlamatController@detail');
    Route::get('delete/{code}', 'Api\Auth\AlamatController@Destroy');
});


Route::group([
    'prefix' => 'Content',
    'middleware' => 'auth:api'
], function () {
    Route::post('create', 'Api\Content\ContentAplikasiController@store');
    Route::post('update', 'Api\Content\ContentAplikasiController@Updatedata');
    Route::get('list/{code}', 'Api\Content\ContentAplikasiController@List');
    Route::get('delete/{code}', 'Api\Content\ContentAplikasiController@Destroy');
});

Route::group([
    'prefix' => 'Transaksi',
    'middleware' => 'auth:api'
], function () {
    Route::post('query', 'Api\Pembelian\TransaksiController@QueryTransaksi');
    Route::post('CommitTransaksi', 'Api\Pembelian\TransaksiController@CommitTransaksi');
    Route::get('ListTransacation', 'Api\Pembelian\TransaksiController@ListTransacation');
    Route::get('ListTransaksiDikemas', 'Api\Pembelian\TransaksiController@ListTransaksiDikemas');
    Route::get('Update-Dikemas/{code}', 'Api\Pembelian\TransaksiController@UpdateDikemas');
    Route::get('ListransaksiDiKirim', 'Api\Pembelian\TransaksiController@ListransaksiDiKirim');
    Route::get('Update-Diterima/{code}', 'Api\Pembelian\TransaksiController@UpdateDiterima');
    Route::get('ListransaksiDiTerima', 'Api\Pembelian\TransaksiController@ListransaksiDiTerima');
    Route::post('Retting-Product', 'Api\Pembelian\TransaksiController@RettingProduct');


    // upload bukti transaksi
    Route::post('Upload-Bukti', 'Api\Pembelian\UploadBuktiPembeyaranController@store');
    Route::get('List-All-Upload-Bukti', 'Api\Pembelian\UploadBuktiPembeyaranController@List');
    Route::get('List-Invoice-Upload-Bukti/{code}', 'Api\Pembelian\UploadBuktiPembeyaranController@ListByInvoiceId');
    Route::post('Update-Upload-Bukti', 'Api\Pembelian\UploadBuktiPembeyaranController@UpdateStatus');

    // Complain
    Route::post('Complain-Product', 'Api\Product\KompalianController@store');
    Route::get('List-Complain', 'Api\Product\KompalianController@ListComplain');
});
