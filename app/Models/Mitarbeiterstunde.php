<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitarbeiterstunde extends Model
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

    public function festival()
    {
        return $this->belongsTo(Festival::class, 'festivalId');
    }

    public function bezeichnung()
    {
        return $this->belongsTo(Bezeichnung::class, 'bezeichnungId');
    }
}
