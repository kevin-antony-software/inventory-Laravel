<?php


namespace App\Http\Controllers\Traits;

trait calCommission {
    function calCommission($id){
        $test = $id * 10;
        return $test;
    }
}


?>