<?php 
namespace App\Client;

class OrcasClient {
    const HTTPS_CONSUMED_SINGLE_POINT_USER_1_URL = "https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/users_1";
    const HTTPS_CONSUMED_SINGLE_POINT_USER_2_URL = "https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/user_2";
    
    public static function GetUserConsumedUrls() {
        return [
            self::HTTPS_CONSUMED_SINGLE_POINT_USER_1_URL,
            self::HTTPS_CONSUMED_SINGLE_POINT_USER_2_URL
        ];
    }
}