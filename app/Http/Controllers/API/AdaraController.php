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


    private function searchMessage($input, $user)
    {
        $msg = $this->inputFormat($input);
        $name = ["nombre", "lamas"];
        $be = ["que", "quien", "eres"];
        $greet = ["hola"];
        $cantGreet = 0;
        $cantName = 0;
        $cantBe = 0;
        $dictionary = ['Greet' => $greet, 'Name' => $name, 'Be' => $be];
        $sizeMsg = count($msg);
        
        foreach ($dictionary as $j => $d){
            $sizeDictionary = count($d);
            for ($i = 0; $i < $sizeDictionary; $i++){
                for($k = 0; $k < $sizeMsg; $k++){
                    ${'cant'.$j} = ${'cant'.$j} + $this->calculateHeft($msg[$k], $d[$i]);
                }
            }
        }

        return $this->getResponse($cantGreet, $cantBe, $cantName, $user, $greet);
    }

    private function inputFormat($input)
    {
        $convertLowerInput = strtolower($input);
        $delWordsRepeat = preg_replace("/(.)\\1+/", "$1", $convertLowerInput);
        $splitBySpaces = explode(" ",$delWordsRepeat);
        return array_unique($splitBySpaces);
    }

    private function calculateHeft($input, $vec)
    {
        $heft = 0;
        if( str_contains($vec,$input) ){
            $heft = $heft + strlen($input);
            $vec === $input ? $heft++ : $heft;
        }
        return $heft;
    }

    private function getResponse($cantGreet, $cantBe, $cantName, $user, $greet)
    {
        $response = "Lo siento no te entiendo";
        $greetResponse = [ucfirst($greet[0]).' '.$user."!", ucfirst($greet[0]).' '.$user."! en que puedo ayudarte?", ucfirst($greet[0]).' '.$user."! cómo estás hoy?"];
        $nameResponse = ["Mi nombre es Adara", "Me llamo Adara"];
        $beResponse = ["Soy una IA", "Soy una Inteligencia Artificial"];
        
        if($cantGreet > $cantBe && $cantGreet > $cantName){
            $response = $greetResponse[array_rand($greetResponse)];
        }else if($cantBe > $cantGreet && $cantBe > $cantName){
            $response = $beResponse[array_rand($beResponse)];
        }else if($cantName > 0){
            $response = $nameResponse[array_rand($nameResponse)];
        }
        return $response;
    }

    /**
     * 
     */
    public function token()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-°!"#%&/()|~=%$?¡][*¨{ñ´+:.;,¿]';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $charactersLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return response(['response' => Crypt::encrypt($randomString), 200]);
    }
}
