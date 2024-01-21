<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Kelas extends Eloquent
{
    protected $fillable = ['nama_kelas'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
