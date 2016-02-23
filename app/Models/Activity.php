<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['user_id', 'label_id', 'activity_at', 'remark'];

    protected $hidden = [];

    public function label() {
        return $this->belongsTo('App\Models\ActivityLabel');
    }

}
