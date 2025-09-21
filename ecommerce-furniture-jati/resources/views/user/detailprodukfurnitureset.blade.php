@extends('layouts.user')

@section('content')
<section class="container mt-5">
    <div class="row g-4">
        <!-- Kolom Kiri: Gambar Set -->
        <div class="col-lg-4">
            @if($set->images->isNotEmpty())
                <img id="mainImage"
                     src="{{ Storage::url($set->images->first()->link) }}"
                     class="img-fluid rounded shadow-sm"
                     alt="{{ $set->name }}">
                <div class="d-flex gap-2 mt-3 thumbnail">
                    @foreach($set->images as $image)
                        <img src="{{ Storage::url($image->link) }}"
                             class="img-thumbnail border"
                             alt="Thumbnail"
                             onclick="changeMainImage(this)">
                    @endforeach
                </div>
            @else
                <div class="bg-light d-flex align-items-center justify-content-center text-muted rounded shadow-sm"
                     style="width:100%; height:300px;">
                    No Image
                </div>
            @endif
        </div>

        <!-- Kolom Tengah: Detail Set -->
        <div class="col-lg-4">
            <h4 class="fw-bold text-dark" style="font-family: 'Georgia', serif;">
                {{ $set->name }}
            </h4>

            @if($set->discount > 0)
                <p class="text-muted small">{{ $set->discount }}% Diskon</p>
            @endif

            <h3 class="text-danger fw-bold">
                Rp {{ number_format($set->harga, 0, ',', '.') }}
            </h3>

            <hr class="border-dark">

            <h5 class="text-success fw-bold">Deskripsi</h5>
            <p class="text-muted" style="font-family: 'Times New Roman', serif;">
                {{ $set->deskripsi }}
            </p>

            @if($set->products->isNotEmpty())
                <h5 class="mt-4 text-success fw-bold">Produk di Dalam Set</h5>
                <ul class="list-group shadow-sm rounded">
                    @foreach($set->products as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $product->name }}
                            @if($product->minimal_stok_for_furniture_set)
                                <span class="badge bg-primary rounded-pill">
                                    Min Stok: {{ $product->minimal_stok_for_furniture_set }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Kolom Kanan: Atur Jumlah & Beli -->
        <div class="col-lg-4">
            @php
                // Cek: ada produk, dan semua produk stoknya >= minimal_stok_for_furniture_set
                $hasProducts = $set->products->isNotEmpty();
                $allMeetMinStock = $set->products->every(function($product) {
                    // jika minimal_stok_for_furniture_set tidak diisi, anggap 1
                    $min = $product->minimal_stok_for_furniture_set ?: 1;
                    return $product->stok >= $min;
                });
                // Hanya boleh beli ketika ada produk dan semua memenuhi
                $stockAvailable = ($hasProducts && $allMeetMinStock) ? true : false;
            @endphp
            
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body" style="background-color: #f4f1e1; font-family: 'Georgia', serif;">
                  <h5 class="fw-bold mb-3">Pembelian</h5>
                  <div class="mb-2">
                    <span class="text-muted">Total Harga (1 set)</span>
                    <span class="float-end fw-medium">Rp {{ number_format($set->harga, 0, ',', '.') }}</span>
                  </div>
              
                  @if($stockAvailable)
                    <form action="{{ route('invoice') }}" method="POST" class="mb-2">
                      @csrf
                      <input type="hidden" name="set_id" value="{{ $set->id }}">
                      <input type="hidden" name="quantity" value="1">
                      <button type="submit" class="btn btn-custom w-100 fw-bold">Beli Sekarang</button>
                    </form>
                  @else
                    <div class="alert alert-warning d-flex align-items-start" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                        <div class="flex-grow-1">
                        <p class="mb-2"><strong>Maaf!</strong> Set ini sedang kosong</p>
                        <ul class="mb-3 ps-3">
                            @if($hasProducts && ! $allMeetMinStock)
                            <li>Stok beberapa produk di bawah minimal.</li>
                            @endif
                        </ul>
                        <a href="{{ route('kontak') }}" class="btn btn-outline-dark btn-sm">
                            Hubungi Admin
                        </a>
                        </div>
                    </div>  
                  @endif
                </div>
            </div>              
        </div>
        
    </div>
</section>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let quantityInput = document.getElementById("quantity");
        let totalItems     = document.getElementById("totalItems");
        let totalPrice     = document.getElementById("totalPrice");
        let decreaseBtn    = document.getElementById("decrease");
        let increaseBtn    = document.getElementById("increase");
        let pricePerSet    = {{ $set->harga }};
        let stockAvailable = {{ $stockAvailable }};

        function updateSubtotal() {
            let q = parseInt(quantityInput.value);
            let newTotal = pricePerSet * q;
            totalItems.innerText = q;
            totalPrice.innerText = newTotal.toLocaleString("id-ID");
            document.getElementById("form_quantity").value = q;
            document.getElementById("form_quantity_cart").value = q;
        }

        decreaseBtn.addEventListener("click", function () {
            let q = parseInt(quantityInput.value);
            if (q > 1) {
                quantityInput.value = q - 1;
                updateSubtotal();
            }
        });

        increaseBtn.addEventListener("click", function () {
            let q = parseInt(quantityInput.value);
            if (q < stockAvailable) {
                quantityInput.value = q + 1;
                updateSubtotal();
            } else {
                alert("Stok tidak mencukupi!");
            }
        });
    });

    function changeMainImage(thumbnail) {
        document.getElementById('mainImage').src = thumbnail.src;
    }
</script>
@endsection

@section('css')
<style>
    .thumbnail img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        cursor: pointer;
        border-radius: 8px;
        border: 1px solid #ccc;
        transition: border-color 0.3s ease;
    }
    .thumbnail img:hover {
        border-color: #4E3B31;
    }

    .btn-custom {
        background-color: #4E3B31;
        color: white;
        border-radius: 8px;
        border: none;
    }
    .btn-custom:hover {
        background-color: #3B2A1D;
    }
</style>
@endsection
