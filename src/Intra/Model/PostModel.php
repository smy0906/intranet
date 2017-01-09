<?php

namespace Intra\Model;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;

class PostModel extends Model
{
    use SoftDeletes;
    protected $table = 'posts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content_html'];

    public static function init()
    {
        Capsule::schema()->create(
            'posts',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('group', 20);
                $table->string('title', 200);
                $table->integer('uid');
                $table->boolean('is_sent');
                $table->text('content_html');
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }
}
