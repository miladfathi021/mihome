<?php

namespace App\Exceptions;

class ValidationException extends CustomException
{
    public function render()
    {
        return response()->json([
            'success' => false,
            'errors' => $this->data
        ], 400);
    }
}
