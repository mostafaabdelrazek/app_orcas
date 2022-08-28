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
        Log::debug('>> Consuming users endpoints starts');
        $arrUserFetchedData = \App\Service\FetchUserDataService::ConsumeUserDataUrls();
        Log::debug(">> Consuming users endpoints data done successfully.");
        Log::debug(">> Validate consiming users data starts");
        $arrRole = [
            'email' => 'required|unique:user_fetcheds',
            'firstName' => 'required',
            'lastName' => 'required',
            'avatar' => 'required'
        ];
        $arrValidateUserData = \App\Service\ValidateFetchedUserDataService::Validate($arrUserFetchedData, $arrRole);
        Log::debug(">> Validate consimed users data successfully");
        Log::debug(">> Inserting consimed users data starts");
        $rslt = \App\Service\InsertValidatedUsersDataService::Insert($arrValidateUserData);
        if ($rslt) {
            Log::debug(">> Inserting consimed users data successfully");
        } else {
            Log::debug(">> Failed to inserting consumed user data.");
        }
        return 0;
    }
}
