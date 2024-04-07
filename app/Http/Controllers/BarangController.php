<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;

class BarangController extends Controller
{
	public function index(Request $request)
	{
		//search dan filter
        $search = $request->input('search');
        $filter = $request->input('filter');

		//jika search dan filter isinya kosong
        $search = $search ?? '';
        $filter = $filter ?? '';

		//query untuk mengambil data produk
        $produkQuery = Barang::query();

		//filter berdasarkan kategori jika filter dipilih
        if ($filter) {
            $produkQuery->where('kategori_id', $filter);
        }

		//filter berdasarkan pencarian jika ada
        if ($search) {
            $produkQuery->where('nama_produk', 'ilike', '%' . $search . '%');
        }

		$barang = $produkQuery->paginate(10);

		return view('barang.index', [
			'data' => $barang,
			'kategori' => Kategori::all(),
			'search' => $search,
			'filter' => $filter,
		]);

	}

	public function tambah()
	{
		$kategori = Kategori::get();

		return view('barang.form', ['kategori' => $kategori]);
	}

	public function simpan(Request $request)
	{
		//validasi produk
		$request->validate([
			'nama_barang' => 'required|unique:barang|max:255',
			'harga_beli' => 'required',
			'harga_jual' => 'required',
			'stok' => 'required|numeric',
			'gambar' => 'required|image|mimes:png,jpeg|max:100',
			'kategori_id' => 'required',
		]);

		//upload gambar dan beri nama ke penyimpanan
		$gambar = $request->file('gambar');
		$slug = Str::slug($gambar->getClientOriginalName());
		$new_gambar = time() .'_'. $slug;
		$gambar->move('public/images/gambar-produk/', $new_gambar);

		//hapus pemisah ribuan dari harga beli dan harga jual
        $hargaBeli = preg_replace('/[^0-9]/', '', $request->harga_beli);
        $hargaJual = preg_replace('/[^0-9]/', '', $request->harga_jual);

		//simpan produk beserta path gambar
		$data = [
			'nama_produk' => $request->get('nama_barang'),
			'harga_beli' => $hargaBeli,
			'harga_jual' => $hargaJual,
			'stok' => $request->get('stok'),
			'gambar' => 'public/images/gambar-produk/'.$new_gambar,
			'kategori_id' => $request->get('kategori_id'),
		];

		//menjalankan query database
		Barang::create($data);

		return redirect()->route('barang')
		->with('success', 'Produk berhasil ditambahkan.');
	}

	public function edit($id)
	{
		//mencari baris berdasarkan id
		$barang = Barang::find($id);
		$kategori = Kategori::all();

		return view('barang.ubah', compact('barang','kategori'));
	}

	public function update(Request $request, $id)
	{
		//validasi edit barang
		$request->validate([
			'nama_barang' => 'required|unique:barang|max:255',
			'harga_beli' => 'required',
			'harga_jual' => 'required',
			'stok' => 'required|numeric',
			'gambar' => 'image|mimes:png,jpeg|max:100',
			'kategori_id' => 'required',
		]);

		//mencari barang berdasarkan id
		$barang = Barang::find($id);

		//jika ada gambar baru diunggah, hapus yang lama
		if($request->hasFile('gambar')){
			
			//hapus gambar lama jika ada
			File::delete($barang->gambar);
			
			//upload gambar baru ke penyimpanan
			$gambar = $request->file('gambar');
			$slug = Str::slug($gambar->getClientOriginalName());
			$new_gambar = time() .'_'. $slug;
			$gambar->move('public/images/gambar-produk/', $new_gambar);

			//simpan nama gambar baru ke dalam data produk
            $barang->gambar = 'public/images/gambar-produk/'.$new_gambar;
			$barang->save();
		};

		//hapus pemisah ribuan dari harga beli dan harga jual
        $hargaBeli = preg_replace('/[^0-9]/', '', $request->harga_beli);
        $hargaJual = preg_replace('/[^0-9]/', '', $request->harga_jual);

		//simpan data edit produk
		$data = [
			'nama_produk' => $request->nama_barang,
			'harga_beli' => $hargaBeli,
			'harga_jual' => $hargaJual,
			'stok' => $request->stok,
			'kategori_id' => $request->kategori_id,
		];

		//menjalankan query database
		Barang::find($id)->update($data);

		return redirect()->route('barang')
		->with('success', 'Produk berhasil diperbarui.');
	}

	public function hapus($id)
	{
		Barang::find($id)->delete();

		return redirect()->route('barang');
	}

	//ekspor data ke Excel
    public function export(Request $request)
    {
        //mencari search & filter
        $search = $request->input('search');
        $filter = $request->input('filter');

        //query untuk mengambil data produk
        $produkQuery = Barang::query();
        
        //filter berdasarkan kategori jika filter dipilih
        if ($filter !== null) {
            $produkQuery->where('kategori_id', $filter);
        }

        //filter berdasarkan pencarian jika ada
        if ($search) {
            $produkQuery->where('nama_produk', 'ilike', '%' . $search . '%');
        }

        //ambil semua data produk sesuai dengan query yang telah difilter
        $barang = $produkQuery->get();
        
        //inisialisasi objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //tulis header kolom
        $sheet->setCellValue('A1', 'Nama Produk');
        $sheet->setCellValue('B1', 'Harga Beli');
        $sheet->setCellValue('C1', 'Harga Jual');
        $sheet->setCellValue('D1', 'Stok');
        $sheet->setCellValue('E1', 'Kategori');

        //mengatur style header
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        //tulis data produk
        $row = 2;
        foreach ($barang as $produk) {
            $sheet->setCellValue('A' . $row, $produk->nama_produk);
            $sheet->setCellValue('B' . $row, $produk->harga_beli);
            $sheet->setCellValue('C' . $row, $produk->harga_jual);
            $sheet->setCellValue('D' . $row, $produk->stok);
            $sheet->setCellValue('E' . $row, $produk->kategori->nama);
            $row++;
        }

        //set lebar kolom
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        //simpan file Excel
        $fileName = 'produk_' . date('YmdHis') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $fileName));

        //redirect atau berikan link untuk mendownload file Excel
        return redirect()->route('barang')->with('success', 'Data produk berhasil diekspor ke file server.');
    }

}
