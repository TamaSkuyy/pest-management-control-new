<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hama extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hama';

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
        'hama_kode',
        'hama_nama',
        'metode_id',
    ];

    
    /**
     * Get the metode that owns the Hama
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metode()
    {
        return $this->belongsTo(Metode::class, 'metode_id', 'id');
    }

    /**
     * Get all of the inspeksi for the Hama
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inspeksi()
    {
        return $this->hasMany(Inspeksi::class, 'hama_id', 'id');
    }
}
