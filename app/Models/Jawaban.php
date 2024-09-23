<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;
    protected $table = 'jawaban';
    protected $primaryKey = 'id';
    protected $fillable = ['nilai','id_pertanyaan','id_user'];

    public function cari_pertanyaan()
    {
        return $this->belongsTo('App\Models\Pertanyaan', 'id_pertanyaan', 'id')->withDefault([
            'keterangan' => 'Tidak Ada'
        ]);
    }

    public function cari_user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id')->withDefault([
            'name' => 'Tanpa Nama',
            'email' => 'Tidak Ada',
            'level' => 'Tidak Diketahui'
        ]);
    }
}
