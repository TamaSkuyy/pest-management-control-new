<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspeksi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inspeksi';

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
        'metode_id',
        'lokasi_id',
        'hama_id',
        'tanggal',
        'pegawai',
        'jumlah',
    ];

    /**
     * Get the metode that owns the Inspeksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metode()
    {
        return $this->belongsTo(Metode::class, 'metode_id', 'id');
    }

    /**
     * Get the lokasi that owns the Inspeksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id', 'id');
    }

    /**
     * Get the hama that owns the Inspeksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hama()
    {
        return $this->belongsTo(Hama::class, 'hama_id', 'id');
    }

    /**
     * Get all of the inspeksidetails for the Inspeksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inspeksidetail()
    {
        return $this->hasMany(Inspeksidetail::class, 'inspeksi_id', 'id');
    }
}
