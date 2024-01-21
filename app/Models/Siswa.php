<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Siswa extends Eloquent
{
    protected $fillable = ['nama_siswa', 'kelas_id'];

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
