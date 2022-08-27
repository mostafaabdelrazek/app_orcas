<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class FetchUserDataController extends Controller
{
    private $_arrConsumedAPI = [
        "https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/users_1",
        "https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/user_2"
    ];

    protected function Fetch()
    {
        $arrValidFetchedAPI = [];
        $arrInValidFetchedAPI = [];
        foreach ($this->_arrConsumedAPI as $strUrl) {
            $arrResponse = Http::get($strUrl);
            if ($arrResponse->status() != Response::HTTP_OK) {
                $arrInValidFetchedAPI[] = "status code = {$arrResponse->status()} , {$strUrl}";
            } else {
                $arrUsers = $arrResponse->object();
                foreach ($arrUsers as $objUser) {
                    $arrUser = (array) $objUser;
                    $objValidate  = Validator::make($arrUser, [
                        'firstName' => 'required',
                        'lastName' => 'required',
                        'email' => 'required|unique:user_fitcheds',
                        'avatar' => 'required'
                    ]);
                    if (!$objValidate->fails()) {
                        $boolRslt = DB::table('user_fitcheds')->insert([
                            'firstName' => $objUser->firstName,
                            'lastName' => $objUser->lastName,
                            'email' => $objUser->email,
                            'avatar' => $objUser->avatar,
                        ]);
                    }
                }
            }
            $arrValidFetchedAPI[] = $strUrl;
        }
        $strResponseMsg = "";
        if (!empty($arrInValidFetchedAPI)) {
            $strResponseMsg .= "  >> Failed to consume [".implode("] , [", $arrInValidFetchedAPI)."].";
        }
        if (!empty($arrValidFetchedAPI)) {
            $strResponseMsg .= "  >> Apis -> [".implode("] , [", $arrValidFetchedAPI)."] consumed successfully.";
        }
        Log::debug('An informational message.');
        return response($strResponseMsg, Response::HTTP_ACCEPTED)->header('Content-Type', 'text/plain');
    }

    protected function Get()
    {
        $arrUser = DB::table('user_fitcheds')->paginate(10);
        return response($arrUser, Response::HTTP_ACCEPTED);
    }

    protected function SearchUser(Request $request)
    {
        $strSearchField = "%".str_replace(" ", "%",$request->strSearchText)."%";
        $arrData = DB::table('user_fitcheds')->
        where('firstName', 'Like', "%$strSearchField%")->
        orWhere('lastName', 'Like', "%$strSearchField%")->
        orWhere('email', 'Like', "%$strSearchField%")->
        paginate(10);
        return response($arrData, Response::HTTP_ACCEPTED);
    }
}
