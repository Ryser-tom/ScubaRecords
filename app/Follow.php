<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Follow extends Model
{
    protected $table = 'following';
    public $timestamps = false;
    protected $fillable = [
        'idFollower',
        'idFollowed'
    ];

    public function getUserArr($user){
        $followed = DB::table('following')
        ->selectRaw('distinct GROUP_CONCAT(idFollowed) as id')
        ->where('idFollower', $user)
        ->get();
        $tmp = json_decode(json_encode($followed->toArray()), true);
        $follow_ar=explode(',', $tmp[0]['id']);
        return $follow_ar;
    }

    public function followStatus($fFollower, $idFollowed){
        $follow = DB::table('following')
        ->select('*')
        ->where('idFollower', $fFollower)
        ->where('idFollowed', $idFollowed)
        ->get();
        return $follow->first();
    }

    public function unfollow($idFollower, $idFollowed){
        $deletedRows = Follow::where('idFollowed', $idFollowed)
            ->where('idFollower', $idFollower)
            ->delete();
    }
}
