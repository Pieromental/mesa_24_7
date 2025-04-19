<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comensal extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'comensales';
    public $incrementing = false;
    protected $keyType = 'string';
 
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'direccion'
    ];

    protected static function booted()
    {
        static::creating(function ($comensal) {
            $comensal->id = (string) Str::uuid();
        });
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
