<?php

namespace Intra\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Press extends Eloquent
{
    protected $table = 'press';
    protected $fillable = ['date', 'media', 'title', 'link_url', 'note'];

    public $timestamps = false;
}
