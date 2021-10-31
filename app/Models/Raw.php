<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;

class Raw extends Model
{
    use HasFactory;
    protected $table = "raw";
    protected $guarded = ['id'];
    
    function siswa()
    {
        return $this->hasOne(Siswa::class, 'nis', 'nis');
    }

}
