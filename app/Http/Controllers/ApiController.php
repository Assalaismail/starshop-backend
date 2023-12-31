<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\RestfulTrait;

class ApiController extends Controller
{
    use RestfulTrait;

    public const STATUS_OK=200;
    public const STATUS_CREATED=201;
    public const STATUS_NO_CONTENT=204;
    public const STATUS_RESET_CONTENT=205;

    //Exception
    public const STATUS_BAD_REQUEST=400;
    public const STATUS_UNAUTHORIZED=401;
    public const STATUS_NOT_AUTHENTICATED =402;
    public const STATUS_FORBIDDEN=403;
    public const STATUS_NOT_FOUND=404;
    public const STATUS_VALIDATION=405;

}
