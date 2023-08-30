<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\usersweb;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Collection;


class UserswebController extends ApiController
{
     //get all users
     public function getAllUsers(Request $request)
     {
        $users = usersweb::all();
        $collection = [];
        foreach ($users as $user) {
            $collection[] = [
                'id' => $user->id,
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'phoneNumber' => $user->phoneNumber,
                'role' => $user->role,
            ];
        }
        return $this->apiResponse($collection, self::STATUS_OK, __('Response ok!'));
     }

     //get user by ID
    public function getUserById(Request $request, $id)
    {
        $user = usersweb::find($id);

        $collection = new collection([
            'id' =>  $user->id,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'email' => $user->email,
            'password' => $user->password,
            'phoneNumber' => $user->phoneNumber,
            'role' => $user->role,
            ]);
            return $this->apiResponse($collection, self::STATUS_OK, __('Response ok!'));
    }


    //create new user
    public function addUser(Request $request)
    {

        $user = new usersweb;


        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phoneNumber' => 'required',
            'role' => 'required',
        ]);
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $email = $request->input('email');
        $password = Hash::make($request->password);
        $phoneNumber = $request->input('phoneNumber');
        $role = $request->input('role');

        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->password = $password;
        $user->role = $role;
        $user->phoneNumber = $phoneNumber;
        $user->save();


        $token = $user->createToken('tokenss')->plainTextToken;

        return response()->json([
            'message' => 'DONE! User Created Successfully',
            'token' => $token,
        ]);
    }

     //delete user
     public function deleteUser(Request $request, $id){
        $user = usersweb::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        $user->delete();

        return response()->json([
            'message' => 'DONE! User deleted'
        ]);
    }

     //login
     public function login(Request $request)
     {
         $fields = $request->validate([
             'email' => 'required',
             'password' => 'required',
         ]);

         //check
         $user = usersweb::where('email', $fields['email'])->first();

         if (!$user || !Hash::check($fields['password'], $user->password)) {
             return response()->json([
                 'message' => 'Bad creds',
             ], 401);
         }
         $token = $user->createToken('token')->plainTextToken;

         return response()->json([
             'message' => 'Loggedin Successfully',
             'token' => $token,
             'role'=>$user->role,
             'id'=>$user->id,
         ]);

     }

        //logout
     public function logout()
         {
           Auth::user()->tokens->each(function($token, $key) {
           $token->delete();
         });
          return response()->json([
         'message' => 'Logged out successfully!',
         'status_code' => 200
     ], 200);
    }
}
