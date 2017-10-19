<?php

/**
 * Do not use or reference this directly from your client-side code.
 * Instead, this should be required via the endpoint.php or endpoint-cors.php
 * file(s).
 */
namespace Contus\Video\Helpers;

use Exception;

class DeletedVideoException extends Exception {
    public function errorMessage() {
        //error message
        return 'Error on line '.$this->getLine().' in '.$this->getFile().': <b>'.$this->getMessage().'</b> is a deleted video';
    }
}
