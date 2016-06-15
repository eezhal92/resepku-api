<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index() {
        return User::paginate(10);
    }
}
