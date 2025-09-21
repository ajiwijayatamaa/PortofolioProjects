@extends('layouts.admin')

@section('tittle')
Daftar Custom Furniture
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success border-left-success alert-dismissible fade show text-white" role="alert" onclick="this.style.display='none';">
    {{ session('success') }}
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Custom Furniture</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-primary">
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Jenis</th>
                            <th>Ukuran</th>
                            <th>Finishing</th>
                            <th>Deadline</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                            <th>Bukti Pembayaran</th>
                        </thead>
                        <tbody>
                            @foreach ($customFurnitures as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->phone_number }}</td>
                                <td>{{ $item->address }}</td>
                                <td>{{ $item->type }}</td>
                                <td>{{ $item->size }}</td>
                                <td>{{ $item->finishing }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->deadline)->format('d-m-Y') }}</td>
                                <td>{{ $item->description }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $item->status === 'pending' ? 'warning' :
                                        ($item->status === 'verified' ? 'success' :
                                        ($item->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                {{-- Kolom Aksi --}}
                                <td>
                                    @if ($item->status === 'pending')
                                        <div class="d-flex justify-content-center gap-2">
                                            <form method="POST" action="{{ route('custom.verify', $item->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success">Verifikasi</button>
                                            </form>
                                            <form method="POST" action="{{ route('custom.reject', $item->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Tolak</button>
                                            </form>
                                        </div>
                                    @else
                                        <small class="text-muted">Sudah Diproses</small>
                                    @endif
                                </td>

                                {{-- Kolom Bukti Pembayaran --}}
                                <td>
                                    @if ($item->status === 'verified')
                                        @if ($item->payment_proof)
                                            <a href="{{ asset('storage/' . $item->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                Lihat Bukti
                                            </a>
                                        @else
                                            <form action="{{ route('custom.uploadProof', $item->id) }}" method="POST" enctype="multipart/form-data" class="d-grid gap-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="file" name="payment_proof" class="form-control form-control-sm" required>
                                                <button type="submit" class="btn btn-sm btn-primary mt-1">Upload Bukti</button>
                                            </form>
                                        @endif
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    {{-- Tambahkan pagination jika perlu --}}
                    {{-- {{ $customFurnitures->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
