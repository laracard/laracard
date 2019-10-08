<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class UserActionHistory extends Model
{

    protected $fillable = ['id', 'user_id', 'action_type', 'ip', 'created_at', 'updated_at'];
    protected $hidden = [];
    public $connection = 'mysql';
    public $table = 'pub_user_action_history';
}
