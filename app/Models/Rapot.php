<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapot extends Model
{
    use HasFactory;
    protected $table   = "rapot";
    protected $guarded = ['id'];
    public $timestamps = false;
}
