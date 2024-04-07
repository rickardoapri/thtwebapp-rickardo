@extends('layouts.app')

@section('title', 'Form Barang')

@section('contents')
  <form action="{{ route('barang.tambah.simpan') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-12">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Barang</h6>
          </div>
          <div class="card-body">
            
            <div class="form-group">
              <label for="kategori_id">Kategori Barang</label>
							<select name="kategori_id" id="kategori_id" class="custom-select">
								@foreach ($kategori as $row)
									<option value="{{ $row->id }}" {{ old('kategori_id') == $row->id ? 'selected' : '' }}>{{ $row->nama }}</option>
								@endforeach
							</select>
              @error('kategori_id')
                    <span class="alert-danger" style="color: red">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
              <label for="nama_barang">Nama Barang</label>
              <input type="text" class="form-control font-normal" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}">
              @error('nama_barang')
                    <span class="alert-danger" style="color: red">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
              <label for="harga_beli">Harga Beli</label>
              <input type="text" class="form-control" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" oninput="maskCurrency(this)">
              @error('harga_beli')
                    <span class="alert-danger" style="color: red">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
              <label for="harga_jual">Harga Jual</label>
              <input type="text" class="form-control" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" readonly>
              @error('harga_jual')
                    <span class="alert-danger" style="color: red">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
              <label for="stok">Stok Produk</label>
              <input type="text" class="form-control" id="stok" name="stok" value="{{ old('stok') }}">
              @error('stok')
                    <span class="alert-danger" style="color: red">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
              <label for="gambar">Upload Gambar</label><br>
              <input type="file" class="border rounded-md px-4 py-2" id="gambar" name="gambar"
              accept="image/*" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])">
              @error('gambar')
                    <span class="alert-danger" style="color: red">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-3"><img src="" id="output" width="200"></div>

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
