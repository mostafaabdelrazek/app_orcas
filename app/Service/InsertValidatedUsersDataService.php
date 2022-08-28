<?php 
namespace App\Service;
use Illuminate\Support\Facades\DB;


class InsertValidatedUsersDataService {
    public static function Insert($arrUserData) {
        $arrData = [];
        foreach($arrUserData as $objUser) {
            $arrData [] = [
                'firstName' => $objUser->firstName,
                'lastName' => $objUser->lastName,
                'avatar' => $objUser->avatar,
                'email' => $objUser->email
            ];
        }
        $boolResult = DB::table('user_fetcheds')->insert($arrData);
        return $boolResult;
    }
}