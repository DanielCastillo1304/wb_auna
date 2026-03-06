<?php
if ( ! function_exists('sendSuccess')) {
    function sendSuccess($data=[], $message="" , $success=TRUE){
        $result = ["success"=>$success, "message"=>$message];

        return response()->json(array_merge($result, $data));
    }
}