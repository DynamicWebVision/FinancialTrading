<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\User;
use App\Model\Role;
use Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller {

    public function index() {
        if (auth::check()) {
            return view('home');
        }
        else {
            return view('login');
        }
    }

    public function createUser() {
        $user = new User([
            'first_name' => 'Brian',
            'last_name' => 'ONeill',
            'email' => 'briantamu6@gmail.com',
            'password' => bcrypt('People96321')
        ]);

        $user->save();

        return 1;
    }

    public function loginAttempt() {
        $post = Request::all();

        if (Auth::attempt($post)) {
            return 1;
        }
        else {
            return 0;
        }
    }

    public function home() {
        return view('home');
    }

    public function roles() {
        $roles = Role::get();

        return $roles->toArray();
    }

    public function delete() {
        $post = Request::all();

        User::destroy($post['id']);

        return 1;
    }

    //Returns All Users for the Manage Users View
    public function allUsers() {
        $users = User::get();

        foreach($users as $user) {
            $roles = $user->roles;

            $user->role = [
                "id"=> $roles[0]->id,
                "name"=>$roles[0]->name
            ];
        }
        return $users->toArray();
    }

    //View User Permissions View
    public function userPermissions() {
        return view('user_permissions');
    }
}