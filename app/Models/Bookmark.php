<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookmark extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['user_id', 'content'];

    protected $hidden = [];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

}
