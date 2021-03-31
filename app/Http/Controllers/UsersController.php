<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    //
    public function ListUsers(Request $request)
    {
        # code...
        return (new User())->ListUsers($request);
    }
}
