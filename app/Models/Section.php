<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $table = 'section';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_section','kode','urutan','id_survey'];

    public function cari_survey()
    {
        return $this->belongsTo('App\Models\Survey', 'id_survey', 'id')->withDefault([
            'judul' => 'Tanpa Judul'
        ]);
    }
}
