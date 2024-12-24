<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tindakan';

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
        'tindakan_kode',
        'tindakan_nama',
        'metode_id',
        'hama_id',
    ];

    
    /**
     * Get the metode that owns the tindakan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metode()
    {
        return $this->belongsTo(Metode::class, 'metode_id', 'id');
    }

    /**
     * Get the hama that owns the tindakan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hama()
    {
        return $this->belongsTo(Hama::class, 'hama_id', 'id');
    }
}
