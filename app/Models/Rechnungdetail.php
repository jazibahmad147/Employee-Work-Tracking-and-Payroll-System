<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rechnungdetail extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'rechnungdetails';

    public function rechnung()
    {
        return $this->belongsTo(Rechnung::class, 'rechnungId', 'id');
    }
}
