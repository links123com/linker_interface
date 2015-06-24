<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TeacherModel extends Model
{
    protected $table      = 'member_social';

    public function member()
    {
        return $this->belongsTo('App\Models\MemberModel', 'user_id', 'id');
    }
}