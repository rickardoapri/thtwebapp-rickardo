<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
	use HasFactory;

	protected $table = 'produk';

	protected $fillable = ['nama_produk', 'harga_beli', 'harga_jual', 'stok', 'gambar', 'kategori_id'];

	public function kategori()
	{
		return $this->belongsTo(Kategori::class, 'kategori_id');
	}
}
