<?php
namespace App\Service;

use Illuminate\Support\Facades\Validator;

class ValidateFetchedUserDataService {
    
    public static function Validate($arrUserData, $arrValidationRules) {
        $intArraySize = sizeof($arrUserData);
        $arrEmails = [];
        for ($i=0; $i < $intArraySize; $i++){
            $objValidate  = Validator::make((array)$arrUserData[$i], $arrValidationRules);
            if ($objValidate->fails() || in_array($arrUserData[$i]->email, $arrEmails)) {
                unset($arrUserData[$i]);
            } else {
                $arrEmails[] = $arrUserData[$i]->email;
            }
        }
        sort($arrUserData);
        return $arrUserData;
    }
}