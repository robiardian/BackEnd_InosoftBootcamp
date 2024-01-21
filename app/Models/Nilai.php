<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Nilai extends Eloquent
{
    protected $fillable = ['siswa_id', 'mata_pelajaran', 'latihan_soal', 'ulangan_harian', 'ulangan_tengah_semester', 'ulangan_semester'];
}
