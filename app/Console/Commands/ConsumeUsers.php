<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ConsumeUsers extends Command
{
    private $_arrConsumedAPI = [
        "https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/users_1",
        "https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/user_2"
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume-users:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::debug('>> Consuming users endpoints starts'.date("Y-m-d H:m:i"));
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
        Log::debug(">> {$strResponseMsg}");
        Log::debug('>> Consuming users endpoints ends'.date("Y-m-d H:m:i"));
        return 0;
    }
}
