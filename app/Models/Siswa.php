<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\Rapot;

class Siswa extends Model
{
    use HasFactory;
    protected $table = "siswa";
    protected $guarded = ['id'];
    
    function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    function rapot()
    {
        return $this->belongsTo(Rapot::class, 'id','id_siswa');
    }
}
