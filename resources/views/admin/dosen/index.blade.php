@extends('layouts.admin')

@section('title', 'Data Dosen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Dosen</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Dosen
            </a>
        </div>
    </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table id="example1" class="table table-bordered table-striped">
        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
            </tr>
        </thead>
                        <tbody>
                            @foreach($dosens as $dosen)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dosen->nama }}</td>
                                <td>{{ $dosen->nip }}</td>
                                <td>{{ $dosen->email }}</td>
                                <td>{{ $dosen->no_hp }}</td>
                                <td>
                                    <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                            </button>
                    </form>
                    </td>
                </tr>
                            @endforeach
        </tbody>
    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection 