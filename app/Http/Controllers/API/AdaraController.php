<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AdaraController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function response(Request $data)
    {
        $response = $this->searchMessage($data->message, $data->user);
        return response(['response' => $response, 200]);
    }

    private function searchMessage($message, $user){
        $res = "Lo siento no te entiendo";
        $msg = strtolower($message);
        $msg = preg_replace("/(.)\\1+/", "$1", $msg);
        $msg = explode(" ",$msg);
        $msg = array_unique($msg);
        $nombre = ["nombre", "lamas"];
        $eres = ["que", "quien", "eres"];
        $saludar = ["hola"];
        $cantSaludar = 0;
        $cantNombre = 0;
        $cantEres = 0;
        $dictionary = [$saludar, $nombre, $eres];
        $saludarResponse = [ucfirst($saludar[0]).' '.$user."!", ucfirst($saludar[0]).' '.$user."! en que puedo ayudarte?", ucfirst($saludar[0]).' '.$user."! cómo estás hoy?"];
        $nombreResponse = ["Mi nombre es Adara", "Me llamo Adara"];
        $eresResponse = ["Soy una IA", "Soy una Inteligencia Artificial"];
        foreach ($dictionary as $d){
            for ($i = 0; $i< count($d); $i++){
                for($k = 0; $k < count($msg); $k++){
                    if( str_contains($d[$i],$msg[$k]) && $d == $saludar){
                        if($d[$i] === $msg[$k]){
                            $cantSaludar++;
                        }
                        $cantSaludar = $cantSaludar + strlen($msg[$k]);
                    }else if(str_contains($d[$i],$msg[$k]) && $d == $nombre){
                        if($d[$i] === $msg[$k]){
                            $cantNombre++;
                        }
                        $cantNombre = $cantNombre + strlen($msg[$k]);
                    }else if(str_contains($d[$i],$msg[$k]) && $d == $eres){
                        if($d[$i] === $msg[$k]){
                            $cantEres++;
                        }
                        $cantEres = $cantEres + strlen($msg[$k]);
                    }
                }
            }
        }
        if($cantSaludar > $cantEres && $cantSaludar > $cantNombre){
            $res = $saludarResponse[array_rand($saludarResponse)];
        }else if($cantEres > $cantSaludar && $cantEres > $cantNombre){
            $res = $eresResponse[array_rand($eresResponse)];
        }else if($cantNombre > 0){
            $res = $nombreResponse[array_rand($nombreResponse)];
        }
        return $res;
    }

    /**
     * 
     */
    public function token(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-°!"#%&/()|~=%$?¡][*¨{ñ´+:.;,¿]';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $charactersLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return response(['response' => Crypt::encrypt($randomString), 200]);
    }
}
