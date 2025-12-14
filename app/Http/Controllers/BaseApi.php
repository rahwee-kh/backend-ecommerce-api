<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Exceptions\BaseException;

abstract class BaseApi {

    protected function getService() {
        return null;
    }

    protected function respondError($error) {
        $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
        $ret = new \stdClass();
        $ret->success = false;
        $msg = $error->getMessage();

        if ($error instanceof BaseException ) {
            $ret->code = $error->getStatusCode();
            $ret->error_code = $error->getErrorCode();
            $ret->message = $msg;

            $status_code = $error->getStatusCode();
        }
        else if ($error instanceof ModelNotFoundException) {
            $model       = $error->getModel();
            $replace     = "App\\Models\\";
            $ret->code       = Response::HTTP_NOT_FOUND;
            $ret->message    = 'Record for '. str_replace($replace,'', $model) .' not found';
            $ret->error_code = 'GENERIC_ERROR';
            $msg = $ret->message;
            $status_code = Response::HTTP_NOT_FOUND;
        }
        else {
            $ret->code       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $ret->error_code = $error->getCode() ?? 'GENERIC_ERROR';
            $ret->message    = $msg;
        }

        // In back-office need check this one
        $ret->error_message = $msg;
        $ret->data          = array();

        $ret->data = [
            'uri'           => request()->getUri(),
            'method'        => request()->getMethod(),
            "line"          => $error->getLine(),
            "file"          => $error->getFile(),
            "trace"         => $error->getTrace()[0]
        ];

        if (method_exists($error, 'getAllErrors') && is_array($error->getAllErrors())) {
            $ret->data = array_merge($error->getAllErrors(), $ret->data);
        }

        # Send Telegram notification
        // if (app()->environment("production") && $ret->code == Response::HTTP_INTERNAL_SERVER_ERROR) {
        //     # Get Restaurant Name
        //     $accountInfo = DB::table("account")->select("name", "global_id", "db_name")->where("global_id", @auth()->user()->restaurant_gid)->first();
        //     $ret->data['authorization'] = request()->header('Authorization') ?? request()->get('token');
        //     \App\Jobs\TelegramReport::dispatch($status_code, $ret, null, $accountInfo);
        //     logReporter(Constants::SOURCE_LOG, "ERROR API", $ret, 'error');
        //     $ret->message       = __('oops_server_error_500');
        //     $ret->error_message = $ret->message;
        // }

        if (app()->environment("production") && count($ret->data)) $ret->data = null;

        if (method_exists($error, 'getAllErrors') && $error->getAllErrors() && app()->environment("production"))
            $ret->data = $error->getAllErrors();

        return response()->json($ret, $status_code);
    }

    protected function respondSuccess($data, $extra = [], $delay = 0) {

        $ret             = new \stdClass();
        $ret->success    = true;
        $ret->code       = 200;
        $ret->error_code = "";
        $ret->message    = "success";
        $ret->data       = $data;
        $ret->delay      = $delay;

        foreach ($extra as $key => $val) {
            $ret->{$key} = $val;
        }
        return response()->json($ret);
    }

}

