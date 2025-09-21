@extends('layouts.user')

@section('title')
    Home Page
@endsection

@section('content')
<main class="container-main-content my-3">
    <!-- Warning -->
    <button type="button" class="btn btn-outline-warning text-black border-secondary my-3" style="display: flex; ">
        <p class="d-inline-flex gap-1">
            <img src="/icons/error.svg" alt="">
            <a href="kontak" class="" role="button">
                Jangan ragu untuk menghubungi Furniture Jati Indonesia melalui kontak yang tertera di website kami!!
            </a>
        </p>
    </button>
    <!-- END Warning -->

    <!-- DISKON -->

    <section class="container">
        <!-- DISKON -->
        <section class="my-5">
            <h5 class="h3 card-title font-bold">Diskon</h5>

            @if($discountedProducts->count() > 0)
            <div id="discountCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded shadow overflow-hidden">
                    @foreach($discountedProducts as $key => $product)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="position-relative" style="height: 400px;">
                                @if($product->images->isNotEmpty())
                                <a href="{{ route('produk.show', $product->id) }}">
                                    <img src="{{ asset('storage/' . $product->images->first()->link) }}" 
                                        class="d-block w-100 h-100" 
                                        alt="{{ $product->name }}" 
                                        style="object-fit: cover;">
                                </a>
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center w-100 h-100">
                                        <span class="text-muted">Gambar tidak tersedia</span>
                                    </div>
                                @endif
                                <div class="position-absolute bottom-0 start-0 bg-danger px-3 py-1 text-white fw-bold" style="border-bottom-right-radius: 10px;">
                                    Diskon {{ $product->discount }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Kontrol -->
                <button class="carousel-control-prev" type="button" data-bs-target="#discountCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle" style="width: 40px; height: 40px;" aria-hidden="true"></span>
                    <span class="visually-hidden">Sebelumnya</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#discountCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle" style="width: 40px; height: 40px;" aria-hidden="true"></span>
                    <span class="visually-hidden">Berikutnya</span>
                </button>
            </div>
            @else
            <p class="text-center text-muted">
                <i class="bi bi-file-earmark-x text-warning" style="font-size: 50px;"></i> <br>
                <span class="fw-bold" style="font-size: 18px;">Belum ada produk diskon tersedia saat ini.</span>
            </p>
            @endif
        </section>
    </section>
    <!-- END DISKON -->

    <!-- Awal Produk Unggulan -->
    <div class="container px-0 mt-4">
        <h5 class="h3 card-title font-bold mb-3">Produk Unggulan</h5>

        <div id="unggulanCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($unggulanProducts->chunk(4) as $chunkIndex => $productChunk)
                    <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                        <div class="row">
                            @foreach($productChunk as $product)
                                <div class="col-3 mb-3 mb-sm-0">
                                    <a href="{{ route('produk.show', $product->id) }}">
                                        <div class="card text-bg-dark border-0 shadow-sm">
                                            @if($product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $product->images->first()->link) }}" class="card-img" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                                                    <span class="text-light">Gambar Tidak Ada</span>
                                                </div>
                                            @endif
                                            <div class="card-img-overlay d-flex justify-content-center align-items-center">
                                                <h5 class="h4 card-title text-center font-bold bg-dark bg-opacity-50 rounded p-2">{{ $product->name }}</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tombol Prev & Next -->
            <button class="carousel-control-prev" type="button" data-bs-target="#unggulanCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                <span class="visually-hidden">Sebelumnya</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#unggulanCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                <span class="visually-hidden">Selanjutnya</span>
            </button>
        </div>
    </div>
    <!-- END Produk Unggulan -->




    <!-- Custom Furniture -->
    <section class="container my-3">
        <h5 class="h3 card-title font-bold">Custom Furniture</h5>
        <div class="row">
            <div class="col-md-6 section left d-flex flex-column justify-content-center align-items-start">
                <h3 class="fw-bold text-uppercase">Pilih</h3>
                <h1 class="fw-bold">Warnamu</h1>
                <p>Temukan warna, model dan bahan sesuai selera anda</p>
                <button class="btn btn-custom">
                    <a href="{{ route('formcustom') }}">GET PRODUCT</a>
                </button>
            </div>
            <div class="col-md-6 section right d-flex flex-column justify-content-center align-items-start">
                <h3 class="fw-bold">Room ideas and inspiration</h3>
                <p>Temukan inspirasi ruangan untuk rumah anda</p>
                <button class="btn btn-custom">
                    <a href="{{ route('customFurniture') }}">GET INSPIRED</a>
                </button>
            </div>
        </div>
    </section>
    <!-- END Custom Furniture -->

    <!-- Furniture Set -->
    <div class="container px-0 mt-5">
        <h5 class="h3 card-title font-bold mb-3">Furniture Set</h5>

        <div id="setCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($furnitureSets->chunk(4) as $chunkIndex => $setChunk)
                    <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                        <div class="row">
                            @foreach($setChunk as $set)
                                <div class="col-3 mb-3 mb-sm-0">
                                    <a href="{{ route('userfurnitureset.show', $set->id) }}">
                                        <div class="card text-bg-dark border-0 shadow-sm">
                                            @if($set->images->isNotEmpty())
                                                <img
                                                    src="{{ asset('storage/' . $set->images->first()->link) }}"
                                                    class="card-img"
                                                    alt="{{ $set->name }}"
                                                    style="height: 250px; object-fit: cover;"
                                                >
                                            @else
                                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                                                    <span class="text-light">Gambar Tidak Ada</span>
                                                </div>
                                            @endif
                                            <div class="card-img-overlay d-flex justify-content-center align-items-center">
                                                <h5 class="h4 card-title text-center font-bold bg-dark bg-opacity-50 rounded p-2">
                                                    {{ $set->name }}
                                                </h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tombol Prev & Next -->
            <button class="carousel-control-prev" type="button" data-bs-target="#setCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                <span class="visually-hidden">Sebelumnya</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#setCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                <span class="visually-hidden">Selanjutnya</span>
            </button>
        </div>
    </div>
    {{-- End Furnitureset --}}


 </main>
@endsection
