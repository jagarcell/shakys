<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Users extends Model
{
    use HasFactory;

    /**
     * 
     *  @return View users
     */
    public function ListUsers($request)
    {
        # code...
        $users = $this->where('id', '>', -1)->get();
        return View('users', ['users' => $users]);
    }

    /**
    * 
    *   @param userid
    *   @param element_tag
    *
    *   @return status
    *           ok
    *           notfound
    *           error
    *
    *   @return user
    *   @return message
    *   @return element_tag
    *
    */
    public function UserById($request)
    {
        # code...
        $UserId = $request['userid'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Users = $this->where('id', '=', $UserId)->get();
            if(count($Users) > 0){
                $User = $Users[0];
                return['status' => 'ok', 'user' => $User, 'element_tag' => $ElementTag];
            }
            else{
                return['status' => 'notfound', 'element_tag' => $ElementTag];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return['status' => 'error', 'message' => $th, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param user_id
     * @param name
     * @param email
     * @param user_type
     * 
     * @return status
     *          ok
     *          emailtaken
     *          noadmin
     *          error
     * 
     * @return user
     * @return element_tag
     * @return message
     *
     */

    public function SaveUser($request)
    {
        # code...
        $UserId = $request['user_id'];
        $Name = $request['name'];
        $Email = $request['email'];
        $UserType = $request['user_type'];
        $ElementTag = $request['element_tag'];

        try {
            // CHECK IF THE USER HASN'T BEEN DELETED
            $Users = $this->where('id', $UserId)->get();
            if(count($Users) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }

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
                        return['status' => 'noadmin', 'element_tag' => $ElementTag];
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
                    return['status' => 'emailtaken', 'element_tag' => $ElementTag];
                }
            }

            // UPDATE THE USER DATA
            $this->where('id', $UserId)->update(['name' => $Name, 'email' => $Email, 'user_type' => $UserType]);

            // RETURN status ok AND USER DATA
            $User = ['id' => $UserId, 'name' => $Name, 'email' => $Email, 'user_type' => $UserType];
            return ['status' => 'ok', 'user' => $User, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            // EXCEPTION THROWN RETURN status error;
            return ['status' => 'error', 'message' => $th, 'element_tag' => $ElementTag];
        }
    }

    /**
     * @param _token
     * @param userid
     * @param element_tag
     * 
     * @return [data, element_tag]
     */


    /**
     *  
     * @param userid
     * @param element_tag
     *
     * @return status
     *         ok
     *         noadmin
     *         error
     *         notfound
     *  
     * @return element_tag
     *
     *   
     */
    public function DeleteUser($request)
    {
        # code...
        $UserId = $request['userid'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Users = $this->where('id', $UserId)->get();
            if(count($Users) > 0){
                $User = $Users[0];
                if($User->user_type == 'admin') {
                    $Users = $this->where('user_type', 'admin')->get();
                    if(count($Users) < 2){
                        return['status' => 'noadmin', 'element_tag' => $ElementTag];
                    }
                }
                $this->where('id', $UserId)->delete();
                return['status' => 'ok', 'element_tag' => $ElementTag];
            }
            else{
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'error', 'message' => $th, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param _token
     * @param user_id
     * @param password
     * @param element_tag
     * 
     * @return status
     *         ok
     *         notfound
     *         passwordmissmatch
     *         error
     *   
     * @return element_tag
     */

    public function ChangePassword($request)
    {
        # code...
        $UserId = $request['user_id'];
        $Password = $request['password'];
        $ConfirmPassword = $request['confirm_password'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            if($Password != $ConfirmPassword){
                return ['status' => 'passwordmissmatch', 'element_tag' => $ElementTag];
            }

            $Users = $this->where('id', $UserId)->get();
            if(count($Users) > 0){
                $HashPassword = Hash::make($Password);
                $this->where('id', $UserId)->update(['password' => $HashPassword]);
                return['status' => 'ok', 'element_tag' => $ElementTag];
            }
            else{
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return['status' => 'error', 'message' => $th, 'element_tag' => $ElementTag];
        }
    }

    /**
     *
     * 
     * @param email
     * @param name
     * @param user_type 
     * @param password
     * @param confirm_password
     * @param element_tag
     * 
     * @return status:ok/user/element_tag
     * @return status:emailtaken/element_tag
     * @return status:passwordmissmatch/element_tag
     * @return status:error/message/element_tag
     * 
     * 
     */

    public function CreateUser($request)
    {
        # code...
        $Email = $request['email'];
        $Name = $request['name'];
        $UserType = $request['user_type'];
        $Password = $request['password'];
        $ConfirmPassword = $request['confirm_password'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Users = $this->where('email', $Email)->get();
            if(count($Users) > 0){
                return ['status' => 'emailtaken', 'element_tag' => $ElementTag];
            }
            if($Password != $ConfirmPassword){
                return ['status' => 'passwordmissmatch', 'element_tag' => $ElementTag, 'p' => $Password, 'cp' => $ConfirmPassword];
            }
            $this->email = $Email;
            $this->name = $Name;
            $this->user_type = $UserType;
            $this->password = Hash::make($Password);
            $this->save();
            $User = ['id' => $this->id, 'email' => $this->email, 'name' => $this->name, 'user_type' => $this->user_type];
            return ['status' => 'ok', 'user' => $User, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'error', 'message' => $th, 'element_tag' => $ElementTag];
        }
    }
}
