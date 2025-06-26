@extends('layouts.dosen')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Supervisi</h1>
        <a href="{{ route('dosen.supervisi.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Content Row -->
    <div class="row">
        <!-- Informasi Konsultasi -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Konsultasi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Tanggal:</strong>
                        <p>{{ $konsultasi->tanggal->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <p>
                            <span class="badge badge-{{ $konsultasi->status == 'selesai' ? 'success' : ($konsultasi->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($konsultasi->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <strong>Pasien:</strong>
                        <p>{{ $konsultasi->pasien->nama }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Dokter:</strong>
                        <p>{{ $konsultasi->dokter->nama }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Keluhan:</strong>
                        <p>{{ $konsultasi->keluhan }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Diagnosa:</strong>
                        <p>{{ $konsultasi->diagnosa ?? '-' }}</p>
                    </div>
                    @if($konsultasi->rating)
                    <div class="mb-3">
                        <strong>Rating Pasien:</strong>
                        <p>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $konsultasi->rating ? 'text-warning' : 'text-gray-300' }}"></i>
                            @endfor
                        </p>
                    </div>
                    @endif
                    @if($konsultasi->review)
                    <div class="mb-3">
                        <strong>Review Pasien:</strong>
                        <p>{{ $konsultasi->review }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Penilaian -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Penilaian</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dosen.supervisi.nilai', $konsultasi->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nilai_supervisi">Nilai Supervisi (1-100)</label>
                            <input type="number" class="form-control @error('nilai_supervisi') is-invalid @enderror" 
                                   id="nilai_supervisi" name="nilai_supervisi" 
                                   value="{{ old('nilai_supervisi', $konsultasi->nilai_supervisi) }}"
                                   min="1" max="100" required>
                            @error('nilai_supervisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="catatan_supervisi">Catatan Supervisi</label>
                            <textarea class="form-control @error('catatan_supervisi') is-invalid @enderror" 
                                      id="catatan_supervisi" name="catatan_supervisi" 
                                      rows="5" required>{{ old('catatan_supervisi', $konsultasi->catatan_supervisi) }}</textarea>
                            @error('catatan_supervisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save fa-sm fa-fw mr-2"></i>
                            Simpan Penilaian
                        </button>
                    </form>
                </div>
            </div>

            <!-- Riwayat Chat -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Chat</h6>
                </div>
                <div class="card-body">
                    @if($konsultasi->chatRoom && $konsultasi->chatRoom->messages->count() > 0)
                        <div class="chat-messages p-4">
                            @foreach($konsultasi->chatRoom->messages as $message)
                            <div class="mb-3">
                                <div class="font-weight-bold text-primary mb-1">
                                    {{ $message->user->name }}
                                    <small class="text-gray-500 font-weight-normal ml-2">
                                        {{ $message->created_at->format('H:i') }}
                                    </small>
                                </div>
                                <div class="bg-light p-3 rounded">
                                    {{ $message->message }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 my-3">Belum ada riwayat chat</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 