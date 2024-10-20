<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geld extends Model
{
    use HasFactory;
    public $timestamps = false;  

    protected $fillable = [
        'date',
        'mitarbeiterId',
        'festivalId',
        'bezeichnungId',
        'beginn',
        'ende',
        'pause',
    ];

    public function mitarbeiter()
    {
        return $this->belongsTo(Mitarbeiter::class, 'mitarbeiterId');
    }
}
