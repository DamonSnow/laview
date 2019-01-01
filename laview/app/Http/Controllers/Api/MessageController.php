<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends ApiController
{
    public function count()
    {
        return $this->success(1, 'success');
    }
}
