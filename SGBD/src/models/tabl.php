<?php

namespace SGBD\models;
use Illuminate\Database\Eloquent\Model;

class tabl extends Model
{
    protected $table = 'tabl';
    protected $primaryKey = 'numtab';
    public $timestamps = false;
}