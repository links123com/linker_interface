<?php namespace App\Services;

use Illuminate\Support\Facades\Redis;

class UserService
{
    /**
     * 重写iterator_to_array,使迭代mongodb文档时能够返回用户昵称
     *
     * @param \MongoCursor $iterator mongodb 文档
     * @param $field string 需要转换出好友昵称的字段名
     * @return array
     */
    public static function iterator_to_array($iterator, $field)
    {
        $temp = [];
        $results = Redis::pipeline(function($pipe) use ($iterator, $field, &$temp)
        {
            foreach($iterator as $key => $value) {

                if(isset($value[$field])) {
                    $pipe->hMget('users_' .$value[$field],['nickname']);
                } else {
                    die("the field name is error.");
                }

                $value['_id'] = strval($value['_id']);
                $temp[] = $value;
            }
        });

        foreach($results as $key => $result) {
            if(is_null(current($result))) {
                // 数据库中取出昵称并将新数据缓存到数据库中
                $member = \DB::table('lnk_member')->find($temp[$key][$field]);
                $profile = \DB::table('lnk_member_social')->find($temp[$key][$field]);

                if(is_null($member))  {$member = [];}
                if(is_null($profile)) {$profile = [];}

                Redis::hMset('users_'.$temp[$key][$field], array_merge($member, $profile));
                $result[0] = $member['nickname'];
            }
            $prefix = current(explode('_', $field));
            $temp[$key][$prefix . '_nickname'] = current($result);
        }
        return $temp;
    }
}