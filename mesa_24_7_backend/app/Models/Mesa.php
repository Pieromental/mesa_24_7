<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mesa extends Model
{
    use HasFactory,SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'numero_mesa',
        'capacidad',
        'ubicacion'
    ];

    protected static function booted()
    {
        static::creating(function ($mesa) {
            $mesa->id = (string) Str::uuid();
        });
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
