<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\MyValidator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Crypt\PublicKeyLoader;


class ApiToken extends Controller
{
    public function index(Request $request)
    {
        if($request->has(['pan', 'cvc', 'cardholder', 'expire']))
        {

            if(!MyValidator::luna_check($request->pan))
            {
                Log::channel('card_errors')->info("Некорректный номер карты: ".$request->pan);
                return response()->json(['Invalid card number!'], 400);
            }elseif(!MyValidator::cvc_check($request->cvc))
            {
                Log::channel('card_errors')->info("Некорректный cvc: ".$request->cvc);
                return response()->json(['Invalid cvc!'], 400);
            }elseif(!is_string($request->cardholder))
            {
                Log::channel('card_errors')->info("Некорректный cardholder: ".$request->cardholder);
                return response()->json(['Invalid cardholder!'], 400);
            }elseif(!MyValidator::date_checker($request->expire))
            {
                Log::channel('card_errors')->info("Некорректная дата: ".$request->expire);
                return response()->json(['Invalid date!'], 400);
            }else{
                $card = [
                    'pan'=>$request->pan,
                    'cvc'=>$request->cvc,
                    'cardholder'=>$request->cardholder,
                    'expire'=>$request->expire,
                    'tokenExpire'=>time()+config('ruru.tokenTTL')
                ];
                $card_json = json_encode($card);
                $publicKey = Storage::disk('local')->get(config('ruru.publicKey'));
                $public = PublicKeyLoader::load($publicKey);
                $token = base64_encode($public->encrypt($card_json));
                Log::channel('card_tokens')->info($token);
                $hid_pan = $card['pan'];
                for($i = 4; $i <= 11; $i++)
                {
                    $hid_pan[$i]='*';
                }
                return response()->json(['pan'=>$hid_pan, 'token'=>$token], 200);

            }


        }else
        {
            Log::channel('card_errors')->info("Не введены данные!");
            return response()->json(['No data entered!'], 400);

        }
    }
}
