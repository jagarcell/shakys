<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class UsersController extends Controller
{
    //
    public function ListUsers(Request $request)
    {
        # code...
        return (new Users())->ListUsers($request);
    }

    public function UserById(Request $request)
    {
        # code...
        return (new Users())->UserById($request);
    }

    public function SaveUser(Request $request)
    {
        # code...
        return (new Users())->SaveUser($request);
    }

    public function DeleteUser(Request $request)
    {
        # code...
        return (new Users())->DeleteUser($request);
    }
}
