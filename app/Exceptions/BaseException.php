<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception {

    // Redefine the exception so message isn't optional

    private $errorCode;
    private $allErrors;
    private $status_code = 200;

    public function __construct($message, $errorCode, $allErrors = array(), $status_code = 200) {

        // make sure everything is assigned properly
        parent::__construct($message, is_int($errorCode)?$errorCode:0);
        $this->errorCode = $errorCode;
        $this->allErrors = $allErrors;
        $this->status_code = $status_code;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    public function getAllErrors() {
        return $this->allErrors;
    }

    public function getStatusCode() {
        return $this->status_code;
    }

}
