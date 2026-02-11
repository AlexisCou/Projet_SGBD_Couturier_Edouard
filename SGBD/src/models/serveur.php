<?php

namespace SGBD\models;
use Illuminate\Database\Eloquent\Model;

class serveur extends Model
{
    protected $table = 'serveur';
    protected $primaryKey = 'id-serv';
    public $timestamps = false;
}