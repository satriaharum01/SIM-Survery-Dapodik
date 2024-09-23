<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;
    protected $table = 'pertanyaan';
    protected $primaryKey = 'id';
    protected $fillable = ['keterangan','id_section'];

    public function cari_section()
    {
        return $this->belongsTo('App\Models\Section', 'id_section', 'id')->withDefault(
            [
                'nama_section' => 'Tidak Ada',
                'kode' => 'Tidak Ada',
                'urutan' => 'Tidak Ada'
            ]);
    }
}
