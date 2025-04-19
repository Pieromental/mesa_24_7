<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    use HasFactory,SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'fecha',
        'hora',
        'numero_de_personas',
        'comensal_id',
        'mesa_id'
    ];

    protected static function booted()
    {
        static::creating(function ($reserva) {
            $reserva->id = (string) Str::uuid();
        });
    }

    public function comensal()
    {
        return $this->belongsTo(Comensal::class);
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
}
