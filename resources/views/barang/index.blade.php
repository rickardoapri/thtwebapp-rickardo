@extends('layouts.app')

@section('title', 'Data Produk')

@section('contents')
<div class="d-flex justify-content-between bg-light">
<div>
  <form method="GET" action="{{ route('barang') }}" class="flex items-center space-x-2">
  <input type="text" name="search" placeholder="Cari barang" value="{{ request('search') }}" class="input h-5" />
  <select name="filter" class="input h-5">
      <option value="">
          <img src="{{ asset('img/Package.png') }}" class="w-4 h-4 mr-2">
          Semua
      </option>
      @foreach ($kategori as $cat)
          <option value="{{ $cat->id }}" {{ request('filter') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
      @endforeach
  </select>
  <button type="submit" class="btn btn-primary" style="height: 35px"><i class="fas fa-search fa-sm"></i></button>
  </form>
</div>
  <div>
			@if (auth()->user()->level == 'Admin')
      <a href="{{ route('barang.export', ['search' => $search, 'filter' => $filter]) }}" class="btn btn-primary mb-2">
        <img src="{{ asset('img/MicrosoftExcelLogo.png') }}" class="w-4 h-4 mr-2">
        Export Excel
      </a>
      <a href="{{ route('barang.tambah') }}" class="btn btn-primary mb-2">
        <img src="{{ asset('img/PlusCircle.png') }}" class="w-4 h-4 mr-2">
        Tambah Produk
      </a>
  </div>
</div>


<div class="card shadow mb-4">
<div class="card-body"> 
			@endif
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Image</th>
              <th>Nama Produk</th>
              <th>Kategori Produk</th>
              <th>Harga Beli (Rp)</th>
              <th>Harga Jual (Rp)</th>
              <th>Stok Produk</th>
							@if (auth()->user()->level == 'Admin')
              <th colspan="2" class="text-center">Aksi</th>
							@endif
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $key => $row)
              <tr>
                <th style="vertical-align: middle">{{ $data->firstItem() + $key }}</th>
                <td style="vertical-align: middle"><img src="{{ asset($row->gambar) }}" width="50"></td>
                <td style="vertical-align: middle">{{ $row->nama_produk }}</td>
                <td style="vertical-align: middle">{{ $row->kategori->nama }}</td>
                <td style="vertical-align: middle">{{ number_format ($row->harga_beli) }}</td>
                <td style="vertical-align: middle">{{ number_format ($row->harga_jual) }}</td>
                <td style="vertical-align: middle">{{ $row->stok }}</td>
								@if (auth()->user()->level == 'Admin')

                <td style="text-align: center; vertical-align: middle;">
                  <a href="{{ route('barang.edit', $row->id) }}">
                    <img src="{{ asset('img/edit.png') }}" alt="Edit">
                  </a>
                  </td>
                <td style="text-align: center; vertical-align: middle;">
                  <a href="{{ route('barang.hapus', $row->id) }}">
                    <img src="{{ asset('img/delete.png') }}" alt="Delete">
                  </a>
                </td>

								@endif
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $data->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>

<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session('success') }}',
        });
    </script>
@endif

@endsection
