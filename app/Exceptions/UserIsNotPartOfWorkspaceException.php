<?php

namespace App\Exceptions;

use Exception;

class UserIsNotPartOfWorkspaceException extends Exception
{
    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => 'User is not part of given workspace.'
        ], 400);
    }
}
