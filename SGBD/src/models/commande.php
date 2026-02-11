<?php

namespace SGBD\models;
use Illuminate\Database\Eloquent\Model;

use SGBD\models\plat;
use SGBD\models\reservation;

class Commande extends Model
{
    protected $table = 'commande';
    public $timestamps = false;

    public function reservation()
    {
        return $this->belongsTo('\SGBD\models\reservation', 'numres');
    }

    public function plat()
    {
        return $this->belongsTo('\SGBD\models\plat', 'numplat');
    }
}