<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Short extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'link'
    ];

    protected $table = 'short_links';
}
