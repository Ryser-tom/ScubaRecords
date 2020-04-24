<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $table = 'certifications';
    protected $primaryKey = 'idCertification';
    public $timestamps = false;
    protected $fillable = [
        'idCertification',
        'user',
        'name',
        'date'
    ];
}
