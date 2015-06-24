<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
/**
 * @property integer $is_teacher 0|1
 */
class TeacherModel extends Model
{
    protected $primaryKey = 'user_id';
    protected $table      = 'member_social';
    public    $timestamps = false;

    public function member()
    {
        return $this->belongsTo('App\Models\MemberModel', 'user_id', 'id');
    }
}