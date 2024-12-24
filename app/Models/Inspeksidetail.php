<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspeksidetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inspeksi_detail';

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
        'inspeksi_id',
        'tindakan_id',
        'check',
    ];

    /**
     * Get the Inspeksi that owns the Inspeksidetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'inspeksi_id', 'id');
    }

    /**
     * Get the Tindakan that owns the Inspeksidetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tindakan()
    {
        return $this->belongsTo(Tindakan::class, 'tindakan_id', 'id');
    }
}
