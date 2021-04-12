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
     * 
     * @return status
     * @return user-data
     */

    public function SaveUser($request)
    {
        # code...
        $UserId = $request['user_id'];

        $Name = $request['name'];
        $Email = $request['email'];
        $UserType = $request['user_type'];

        // IF THE USER TYPE TO BE UPDATED IS 'user' WE CHECK
        // THAT WE HAVE AT LEAST ONE 'admin' USER AND THAN THAT
        // 'admin' USER IS NOT THE SAME BEING UPDATED HERE AS 'user'

        if($UserType != 'admin'){
            $AdminUsers = $this->where('user_type', 'admin')->get();
            if(count($AdminUsers) < 2){
                // ONLY ONE 'admin' USER LEFT
                $AdminUser = $AdminUsers[0];
                if($AdminUser->id == $UserId){
                    // THE ONLY 'admin' LEFT IS THIS ONE AND IS BEING
                    // CHANGED TO 'user'. THE SYSTEM NEEDS AT LEAST 
                    // ONE 'admin'
                    return['status' => 'noadmin', 'element_tag' => $UserId];
                }
            }
        }

        // SEARCH IF THERE IS ANY USER WITH THIS EMAIL 
        $Users = $this->where('email', $Email)->get();
        if(count($Users) > 0){
            $User = $Users[0];
            // IF THE USER WITH THIS EMAIL IS THE SAME THAT IS BEING UPDATED ...
            if($User->id != $UserId){
                // ... THEN THERE IS NOT EMAIL REUSE
                return['status' => 'email taken', 'element_tag' => $UserId];
            }
        }
        try {
            //UPDATE THE USER DATA
            $this->where('id', $UserId)->update(['name' => $Name, 'email' => $Email, 'user_type' => $UserType]);
            // RETURN status ok AND USER DATA
            return ['status' => 'ok', 'user_id' => $UserId, 'name' => $Name, 'email' => $Email, 'user_type' => $UserType, 'element_tag' => $UserId];
        } catch (\Throwable $th) {
            // EXCEPTION THROWN RETURN status error;
            return ['status' => 'error', 'element_tag' => $UserId];
        }
    }

    /**
     * @param _token
     * @param userid
     * @param element_tag
     * 
     * @return [data, element_tag]
     */


    public function DeleteUser($request)
    {
        # code...
        $UserId = $request['userid'];
        $ElementTag = $request['element_tag'];

        $Users = $this->where('id', $UserId)->get();
        if(count($Users) > 0){
            $User = $Users[0];
            if($User->user_type == 'admin') {
                $Users = $this->where('user_type', 'admin')->get();
                if(count($Users) < 2){
                    return['status' => 'noadmin', 'element_tag' => $ElementTag];
                }
            }
            try {
                $this->where('id', $UserId)->delete();
                return['status' => 'ok', 'element_tag' => $ElementTag];
            } catch (\Throwable $th) {
                //throw $th;
                return['status' => 'error', 'element_tag' => $ElementTag, 'th' => $th];
            }
        }
    }
}
