<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    public function ListUsers($request)
    {
        # code...
        $users = $this->where('id', '>', -1)->get();
        return View('users', ['users' => $users]);
    }

    /**
    *   @param $request[userid]
    *   @return $user
    */

    public function UserById($request)
    {
        # code...
        $UserId = $request['userid'];
        try {
            //code...
            $Users = $this->where('id', '=', $UserId)->get();
            if(count($Users) > 0){
                $User = $Users[0];
                return['status' => 'ok', 'user' => $User];
            }
            else{
                return['status' => 'NOT FOUND'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return['status' => 'ERROR', 'message' => $th];
        }
    }

    /**
     * @param user_id
     * @param name
     * @param email
     * @param user_type
     */

    public function SaveUser($request)
    {
        # code...
        $UserId = $request['user_id'];

        $Name = $request['name'];
        $Email = $request['email'];
        $UserType = $request['user_type'];
        $Users = $this->where('email', $Email)->get();
        if(count($Users) > 0){
            return['status' => 'email taken'];
        }
        try {
            //code...
            $this->where('id', $UserId)->update(['name' => $Name, 'email' => $Email, 'user_type' => $UserType]);
            return ['status' => 'ok', 'user_id' => $UserId, 'name' => $Name, 'email' => $Email, 'user_type' => $UserType];
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'error'];
        }
    }
}
