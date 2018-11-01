<?php
namespace app\api\model;

class User extends BaseModel {
<<<<<<< HEAD

    public function address() {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }
    
    public static function getByOpenID($openid) {
        $user = self::where('openid', '=', $openid)
            ->find();
        return $user;
    }
=======
    
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21
}