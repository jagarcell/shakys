<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tests\Sqltests;

class SqltestController extends Controller
{
    //
    public function SqlTest(Request $request)
    {
        # code...
        return (new Sqltests())->Test($request);
    }
}
