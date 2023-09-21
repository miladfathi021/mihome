<?php

namespace App\Exceptions;

use Exception;

abstract class CustomException extends Exception
{
    protected $data;

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data) : static
    {
        $this->data = $data;

        return $this;
    }
}
