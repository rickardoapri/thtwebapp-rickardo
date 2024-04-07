@extends('layouts.app')

@section('title', 'Form Barang')

@section('contents')
  <form action="{{ route('barang.tambah.update', $barang->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-12">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Barang</h6>
          </div>
          <div class="card-body">
            
            <div class="form-group">
              <label for="kategori_id">Kategori Barang</label>
							<select name="kategori_id" id="kategori_id" class="custom-select">
								@foreach ($kategori as $cat)
									<option value="{{ $cat->id }}" {{ $barang->kategori_id == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
								@endforeach
							</select>
              @error('kategori_id')
                    <span class="text-red-500 alert-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
              <label for="nama_barang">Nama Barang</label>
              <input type="text" class="form-control font-normal" id="nama_barang" name="nama_barang" value="{{ $barang->nama_produk }}">
              @error('nama_barang')
                    <span class="text-red-500 alert-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
              <label for="harga_beli">Harga Beli</label>
              <input type="text" class="form-control" id="harga_beli" name="harga_beli" oninput="maskCurrency(this)" value="{{ number_format($barang->harga_beli, 0, ',', '.') }}" >
              @error('harga_beli')
                    <span class="text-red-500 alert-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
              <label for="harga_jual">Harga Jual</label>
              <input type="text" class="form-control" id="harga_jual" name="harga_jual" readonly value="{{ number_format($barang->harga_jual, 0, ',', '.') }}" >
              @error('harga_jual')
                    <span class="text-red-500 alert-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
              <label for="stok">Stok Produk</label>
              <input type="text" class="form-control" id="stok" name="stok" value="{{ $barang->stok }}">
              @error('stok')
                    <span class="text-red-500 alert-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
              <label for="gambar">Upload Gambar</label><br>
              <input type="file" class="border rounded-md px-4 py-2" id="gambar" name="gambar"
              accept="image/*" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])">
              @error('gambar')
                    <span class="text-red-500 alert-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-3">
                <img src="{{ asset($barang->gambar) }}" alt="Gambar {{ $barang->nama_produk }}" class="mt-2" style="max-width: 200px;" id='output'>
              </div>

          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <script>
    // Fungsi untuk menambahkan pemisah ribuan pada input harga beli
    function maskCurrency(input) {
        // Hapus karakter non-digit dari input
        var sanitized = input.value.replace(/[^0-9]/g, '');
        
        // Format angka dengan pemisah ribuan
        var formatted = sanitized.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        
        // Tampilkan angka yang diformat kembali di input
        input.value = formatted;
        
        // Hitung harga jual otomatis berdasarkan harga beli
        var hargaBeli = parseInt(sanitized.replace(/\D/g, ''));
        var hargaJual = hargaBeli + (hargaBeli * 0.3);
        
        // Set nilai harga jual otomatis dan tambahkan pemisah ribuan
        document.getElementById('harga_jual').value = hargaJual.toLocaleString();
    }
</script>

@endsection
