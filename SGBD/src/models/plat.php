<?php

namespace SGBD\models;
use Illuminate\Database\Eloquent\Model;

class plat extends Model
{
    protected $table = 'plat';
    protected $primaryKey = 'numplat';
    public $timestamps = false;
}