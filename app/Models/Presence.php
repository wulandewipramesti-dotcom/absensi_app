<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = ['nama_kegiatan', 'slug', 'tgl_kegiatan'];

    protected $casts = [
        'tgl_kegiatan' => 'datetime', // penting!
    ]; // ← titik koma di sini

    public function details()
    {
        return $this->hasMany(PresenceDetail::class, 'presence_id', 'id');
    }
}
