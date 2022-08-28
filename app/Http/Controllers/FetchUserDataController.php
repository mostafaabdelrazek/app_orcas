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

    /*  
    protected function Fetch()
    {
        $arrValidFetchedAPI = [];
        $arrInValidFetchedAPI = [];
        $arr = \App\Service\FetchUserDataService::ConsumeUserDataUrls();
        var_dump($arr);
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
    */

    protected function Get()
    {
        $arrData = DB::table('user_fetcheds')->paginate(10);
        return response(['Data' => $arrData, 'message'=> 'Data listed successfully', 'status' => Response::HTTP_OK], Response::HTTP_OK);
    }

    protected function SearchUser(Request $request)
    {
        // still need to fix search scan n row issue
        $strFirstName = "";
        $strLastName = "";
        $strEmail = "";
        $arrDataRequest = $request->all();
        if (empty($arrDataRequest['first_name']) && empty($arrDataRequest['last_name']) && empty($arrDataRequest['email'])) {
            return response(['Data'=>[],'message'=>"Missing arguments", "status" => Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
        }
        $arrData = DB::table('user_fetcheds')->
        when(!empty($arrDataRequest['first_name']),function($query) use ($arrDataRequest){
            return $query->where('firstName' , "like", "%".$arrDataRequest['first_name']."%");
        })->when(!empty($arrDataRequest['last_name']),function($query) use ($arrDataRequest){
            return $query->where('lastName' , "like", "%".$arrDataRequest['last_name']."%");
        })->when(!empty($arrDataRequest['email']),function($query) use ($arrDataRequest){
            return $query->where('email' , "like", "%".$arrDataRequest['email']."%");
        })->paginate(10);
        return response(['Data'=> $arrData, 'message'=> 'Search done successfully', 'status' => Response::HTTP_OK], Response::HTTP_OK);
    }
}
