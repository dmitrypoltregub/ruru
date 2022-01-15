<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Lib\MyValidator;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::match(['post', 'get'], '/api/token/', function(Request $request){

    if($request->has(['pan', 'cvc', 'cardholder', 'expire']))
    {

        if(!MyValidator::luna_check($request->pan))
        {
            Log::channel('card_errors')->info("Некорректный номер карты: ".$request->pan);
            return response()->json(['Некорректный номер карты!'], 400);
        }elseif(!MyValidator::cvc_check($request->cvc))
        {
            Log::channel('card_errors')->info("Некорректный cvc: ".$request->cvc);
            return response()->json(['Некорректный cvc!'], 400);
        }elseif(!is_string($request->cardholder))
        {
            Log::channel('card_errors')->info("Некорректный cardholder: ".$request->cardholder);
            return response()->json(['Некорректный cardholder!'], 400);
        }elseif(!MyValidator::date_checker($request->expire))
        {
            Log::channel('card_errors')->info("Некорректная дата: ".$request->expire);
            return response()->json(['Некорректная дата!'], 400);
        }


    }else
    {
        echo "Не введены данные!";
    }


});





