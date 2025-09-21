<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Models\Product as ProductModel; // 👈 kasih alias "ProductModel"
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FurnitureSetController;
use App\Http\Controllers\UserFurnitureSetController;
use App\Http\Controllers\CustomFurnitureController;

// -----------------------------------------------------------------------------------------------------
// Rute yang bisa diakses oleh semua pengguna
// -----------------------------------------------------------------------------------------------------
Route::get('/home', [UserProductController::class, 'index'])->name('user.home');
Route::get('/admin', function () {
    return view('layouts.admin');
});
Route::get('/informasiToko', function () {
    return view('informasiToko');
});
Route::get('/kontak', function () {
    return view('user.kontak');
})->name('kontak');
Route::get('/detailProduk', function () {
    return view('detailProduk');
});
Route::get('/customFurniture', function () {
    return view('user.customFurniture');
})->name('customFurniture');

 Route::get('/formcustom', function () {
    return view('user.formcustom');
})->name('formcustom');
Route::resource('userfurnitureset', UserFurnitureSetController::class);
Route::resource('produk', UserProductController::class);// Kalo buat sekaligus nambah index,create,store,edit,update,show,delete, tetapi harus ada 6 6 nya kalo misalnya di tambah yang lain gpp yang penting ada 6 6 nya itu
Route::get('/keranjang', function () {
    return view('user.keranjang');
})->name('user.keranjang');
Route::get('/', [UserProductController::class, 'home'])->name('index');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// -----------------------------------------------------------------------------------------------------
// END Rute yang bisa diakses oleh semua pengguna
// -----------------------------------------------------------------------------------------------------

// -----------------------------------------------------------------------------------------------------
// AUTH
// -----------------------------------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    
    // Semua rute di dalam group ini hanya untuk pengguna terautentikasi
    // User profile
    Route::get('/profile/show', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    // Untuk "Beli Sekarang" 1 produk
    Route::get('/invoice/{id}', [UserProductController::class, 'showInvoice'])->name('invoice.show');
    Route::post('/invoice', [UserProductController::class, 'invoice'])->name('invoice');
     // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{orderProductId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    // Untuk "Checkout Keranjang"
    Route::get('/checkout', [UserProductController::class, 'checkoutCart'])->name('cart.checkout');
    // Tampilkan form custom furniture
    Route::get('/custom-furniture', [CustomFurnitureController::class, 'create'])->name('custom.furniture.create');
    // Proses penyimpanan custom furniture (dari form)
    Route::post('/custom-furniture', [CustomFurnitureController::class, 'store'])->name('custom.furniture.store');
    // Bayar Invoice
    Route::get('/bayar_invoice', function () {
        return view('user.upload_bukti');
    })->name('bayar.invoice');
    Route::post('/bayar-invoice', [UserProductController::class, 'bayarInvoice'])->name('userproduct.bayarInvoice');
    
    Route::post('/upload-bukti', [UserProductController::class, 'uploadBukti'])->name('user.uploadBukti');

    // Status Pembayaran (harus login)
    Route::get('/statusPembayaran', [UserProductController::class, 'statusPembayaran'])->name('statusPembayaran');
});
// -----------------------------------------------------------------------------------------------------
// END AUTH
// -----------------------------------------------------------------------------------------------------

// -----------------------------------------------------------------------------------------------------
// GUEST
// -----------------------------------------------------------------------------------------------------
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // penting
    
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    
    // Menambahkan route untuk mengirimkan email reset password
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    
    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
    
    // Proses reset password setelah form di-submit
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    
});
// -----------------------------------------------------------------------------------------------------
// END GUEST
// -----------------------------------------------------------------------------------------------------

// -----------------------------------------------------------------------------------------------------
// ADMIN
// -----------------------------------------------------------------------------------------------------
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/payment-proofs', [OrderController::class, 'showPaymentProofs'])->name('payment.proofs');
    Route::patch('/payment/{order}/verify', [OrderController::class, 'verify'])->name('payment.verify');
    Route::patch('/payment/{order}/reject', [OrderController::class, 'reject'])->name('payment.reject');
    Route::patch('/custom-furniture/{id}/verify', [CustomFurnitureController::class, 'verify'])->name('custom.verify');
    Route::patch('/custom-furniture/{id}/reject', [CustomFurnitureController::class, 'reject'])->name('custom.reject');
    Route::patch('/admin/custom-furniture/{id}/upload-proof', [CustomFurnitureController::class, 'uploadProof'])->name('custom.uploadProof');
    Route::resource('products', ProductController::class);
    Route::resource('furnitureset', FurnitureSetController::class);
    Route::get('/customfurniture', [CustomFurnitureController::class, 'index'])->name('customfurniture');
});
// -----------------------------------------------------------------------------------------------------
// END ADMIN
// -----------------------------------------------------------------------------------------------------



Route::get('/admin-user', function () {
    return view('admin.user');
});

Route::get('/page2', function () {
    return view('customFurniture.page2');
});


// Route::get('/perawatanFurniture', function () {
//     return view('perawatanFurniture');
// });
// Route::view('/admin/kelola-custom', 'admin.kelolacustomfurniture');
// Route::get('/testimoni', function () {
//     return view('testimoni');
// });
// Route::get('/caraPesan', function () {
//     return view('caraPesan');
// });
// Route::get('/admin-review', function () {
//     return view('admin.review');
// });
// Route::get('/promo', function () {
//     return view('promo');
// });
