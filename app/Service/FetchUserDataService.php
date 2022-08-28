<?php 
namespace App\Service;
use Illuminate\Support\Facades\Http;
use App\Client\OrcasClient;
use Illuminate\Http\Response;

class FetchUserDataService {
    static public function ConsumeUserDataUrls() {
        $arrUrls = OrcasClient::GetUserConsumedUrls();
        $arrUserData = [];
        foreach ($arrUrls as $strUrl) {
            $response = Http::get($strUrl);
            if ($response->status() == Response::HTTP_OK) {
                $arrUserData = array_merge($arrUserData, $response->object());
            }
        }
        return $arrUserData;
    }
}
