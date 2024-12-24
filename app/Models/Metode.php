<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Metode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'metode';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'metode_kode',
        'metode_nama',
        'metode_jenis',  //1 = indoor, 2 = outdoor
    ];

}
