<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class LoginFailedException extends Exception
{
    public function __construct(string $message = "")
        {
            parent::__construct($message);
        }

    public function render(Request $request)
    {
        return back()
            ->withErrors(['nombre' => $this->getMessage()])
            ->withInput();
    }
}
