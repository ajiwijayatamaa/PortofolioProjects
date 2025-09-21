<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini di bagian atas
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use App\Models\FurnitureSet;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Image;
use App\Models\Order;


class UserProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('user.produk',compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        return view('detailProduk',compact('product'));
    }


    public function showInvoice($id)
    {
        $order = Order::with('orderProducts')->findOrFail($id);
        $user  = auth()->user();

        if (!$order) {
            abort(404, 'Order tidak ditemukan');
        }

        // Ambil item pertama
        $item = $order->orderProducts->first();
        if (!$item) {
            abort(404, 'Tidak ada produk di order ini.');
        }

        // Cari apakah ID produk milik Product atau FurnitureSet
        $product = Product::find($item->product_id);
        if (!$product) {
            $product = FurnitureSet::find($item->product_id);
        }

        $quantity   = $item->quantity;
        $totalHarga = $order->total_harga;

        return view('user.invoiceCustomer', compact('order', 'user', 'product', 'quantity', 'totalHarga'));
    }







    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }   

   
    public function destroy(string $id)
    {
        //
    }

    public function home()
    {
        $discountedProducts = Product::with('images')  // Mengambil produk diskon
            ->where('discount', '>', 0)
            ->get();

        $unggulanProducts = Product::with('images') // Produk unggulan
            ->where('stok', '>', 5)
            ->get();

            $furnitureSets = FurnitureSet::with('images') // Mengambil data furniture sets (sesuaikan model)
            ->has('products')
            ->get();

        return view('user.home', compact('discountedProducts', 'unggulanProducts', 'furnitureSets'));
    }

    public function invoice(Request $request)
    {
        $user = Auth::user();

        // Jika checkout dari keranjang
        if ($request->filled('order_id')) {
            $order = Order::with(['orderProducts.product'])->where('id', $request->order_id)
                        ->where('user_id', $user->id)
                        ->whereNull('order_code') // pastikan belum checkout
                        ->firstOrFail();

            return view('user.invoiceCustomer', compact('order', 'user'));
        }

        // Jika beli langsung produk biasa
        $quantity = $request->input('quantity');

        if ($request->filled('set_id')) {
            $product = FurnitureSet::with('images')->findOrFail($request->set_id);
        } else {
            $product = Product::findOrFail($request->product_id);
        }

        return view('user.invoiceCustomer', compact('product', 'quantity', 'user'));
    }

    
    public function bayarInvoice(Request $request)
    {
        $user              = auth()->user();
        $qty               = $request->input('quantity');
        $tenggat_pembayaran = $request->input('tenggat_pembayaran');

        // 🔁 Jika ini berasal dari checkout keranjang (order_id langsung digunakan)
        if ($request->filled('order_id') && !$request->filled('product_id') && !$request->filled('set_id')) {
            $order = Order::findOrFail($request->order_id);

            return view('user.upload_bukti', [
                'order'   => $order,
                'product' => null, // Tidak perlu produk spesifik karena bisa lebih dari 1
                'tenggat' => \Carbon\Carbon::parse($tenggat_pembayaran)->format('d M Y, H:i'),
            ]);
        }
        
        // Tentukan prefix berdasarkan apakah beli furniture set atau produk
        $prefix = $request->filled('set_id') ? 'SET-' : 'PROD-';

        // 1) Buat order tanpa total_harga dulu
        $order = Order::create([
            'user_id'           => $user->id,
            'order_code'        => $prefix . strtoupper(uniqid()),
            'total_harga'       => 0,                          // *CHANGED*
            'status'            => 'waiting_confirmation',
            'payment_status'    => 'pending',
            'alamat_pengiriman' => $user->address,
            'maximal_pembayaran'=> $tenggat_pembayaran,
        ]);

        if ($request->filled('set_id')) {
            $set = FurnitureSet::findOrFail($request->set_id);
        
            // Hanya satu entri OrderProduct untuk satu set
            OrderProduct::create([
                'order_id'   => $order->id,
                'product_id' => $set->id, // ← masukkan ID dari furniture set
                'quantity'   => $qty,
                'price'      => $set->harga,
                'subtotal'   => $set->harga * $qty,
            ]);
        
            // Kurangi stok tiap produk di dalam set
            foreach ($set->products as $prod) {
                $prod->decrement('stok', $qty);
            }
        
            // Update total_harga order
            $order->update(['total_harga' => $set->harga * $qty]);

            Mail::to($user->email)->send(new InvoiceMail($order, $set, $qty));



            return view('user.upload_bukti', [
                'order'   => $order,
                'product' => $set,
                'tenggat' => \Carbon\Carbon::parse($tenggat_pembayaran)->format('d M Y, H:i'),
            ]);
        }

        // original: pembelian produk tunggal
        $prod     = Product::findOrFail($request->product_id);
        $subtotal = $prod->harga * $qty;

        OrderProduct::create([
            'order_id'   => $order->id,
            'product_id' => $prod->id,
            'quantity'   => $qty,
            'price'      => $prod->harga,
            'subtotal'   => $subtotal,
        ]);
        $prod->decrement('stok', $qty);

        // *CHANGED*: update total_harga setelah create OrderProduct
        $order->update(['total_harga' => $subtotal]);
        
        Mail::to($user->email)->send(new InvoiceMail($order, $prod, $qty));

        return view('user.upload_bukti', [
            'order'   => $order,
            'product' => $prod,
            'tenggat' => \Carbon\Carbon::parse($tenggat_pembayaran)->format('d M Y, H:i'),
        ]);
    }


    
    public function uploadBukti(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Simpan file ke storage/app/public/bukti_pembayaran
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // Simpan path-nya ke database (asumsinya ada kolom 'payment_proof' di tabel orders)
        $order->payment_proof = $path;
        $order->payment_status = 'waiting_verification';
        $order->save();

        return redirect()->route('user.home')->with('success', 'Bukti pembayaran berhasil diupload.');
    }
    
    public function statusPembayaran()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)
                    ->latest()
                    ->get();

        return view('user.statusPembayaran', compact('orders'));
    }

}
