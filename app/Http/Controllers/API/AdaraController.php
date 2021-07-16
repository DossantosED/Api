<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


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
        $saludar = ["hola"];
        $saludarResponse = [ucfirst($saludar[0]).' '.$user."!", ucfirst($saludar[0]).' '.$user."! en que puedo ayudarte?", ucfirst($saludar[0]).' '.$user."! cómo estás hoy?"];
        $condition = str_contains($saludar[0],$msg);
        if(strlen($msg) > 2 && $condition){
            $res = $saludarResponse[array_rand($saludarResponse)];
        }

        return $res;
    }
}
