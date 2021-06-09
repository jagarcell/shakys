<?php

namespace App\Errors;

class ErrorInfo
{

    /**
     * 
     * @param $th
     * 
     * @return $Message
     * 
     * 
     */
    public function GetErrorInfo($th)
    {
        # code...
        if(!property_exists($th, 'errorInfo') || count($th->errorInfo) == 0){
            $Message = ["Undefined Server Error"];
        }
        else{
            $Message = $th->errorInfo[count($th->errorInfo) - 1];
        }
        return $Message;
    }

}

