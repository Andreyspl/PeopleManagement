<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'type',
        'cep',
        'logradouro',
        'number',
        'complement',
        'neighborhood',
        'state',
        'city',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
