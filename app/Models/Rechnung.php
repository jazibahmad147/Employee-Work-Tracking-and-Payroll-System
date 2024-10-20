<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rechnung extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'rechnungs';

    public function rechnungdetails()
    {
        return $this->hasMany(Rechnungdetail::class, 'rechnungId', 'id');
    }

    public function festival()
    {
        return $this->belongsTo(Festival::class, 'festivalId');
    }
}
