<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsersController extends Controller
{
    function register(Request $req){
        $response['success'] = array('success' => false, 'messages' => array());
        $user = new User;
        $user->name = $req->fname;
        $user->email = $req->email;
        $user->password = md5($req->password);
        // check user already registered or not
        $count = DB::table('users')
        ->where('email',$user->email)
        ->count();
        if($count>0){
            $response['success'] = false;
            $response['icon'] = "error";
            $response['title'] = "Failed";
            $response['messages'] = $req->email." Already Registerd!";
        }else{
            $execute = $user->save();
            if($execute){
                $req->session()->put('email',$user->email);
                $response['success'] = true;
                $response['icon'] = "Success";
                $response['title'] = "Congratulations";
                $response['messages'] = $req->fname."'s Record Registerd Successfully!";
            }else{
                $response['success'] = false;
                $response['icon'] = "error";
                $response['title'] = "Failed";
                $response['messages'] = "Sorry! Something went wrong!";
            }
        }
        echo json_encode($response);
    }

    function login(Request $req){
        $response['success'] = array('success' => false, 'messages' => array());
        $user = new User;
        $user->email = $req->email;
        $user->password = md5($req->password);
        $count = DB::table('users')
        ->where('email',$user->email)
        ->where('password',$user->password)
        ->count();
        if($count==1){
            $req->session()->put('email',$user->email);
            $response['success'] = true;
            $response['icon'] = "success";
            $response['title'] = "Congratulations";
            $response['messages'] = "You Have Successfully Loged In!";
        }else{
            $response['success'] = false;
            $response['icon'] = "error";
            $response['title'] = "Wrong Credential";
            $response['messages'] = "Email or Password Maybe Wrong!";
        }
        echo json_encode($response);
    }
}
