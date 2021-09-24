<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aplikasi extends Model
{
    protected $table = 'aplikasi';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'file',
        'keterangan',
        'username',
        'sts',
        'attach',
        'link',

    ];

    function user(){
        return $this->belongsTo('App\User','username','username');
    }
}
