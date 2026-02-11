<?php

namespace SGBD\models;
use Illuminate\Database\Eloquent\Model;

class reservation extends Model
{
    protected $table = 'reservation';
    protected $primaryKey = 'numres';
    public $timestamps = false;

    public function numtab()
    {
        return $this->belongsTo('\SGBD\models\tabl', 'numtab');
    }
}