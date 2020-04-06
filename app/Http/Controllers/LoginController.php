<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Carbon\Carbon;

class LoginController extends Controller {
    public function index() {
        return view('login');
    }

    public function dashboardIndex(Request $request) {
        if(!Auth::check()) {
            //validate request has the fields we need
            request()->validate([
                'email' => 'required',
                'password' => 'required',
            ]);
    
            //attempt to login
            if (!Auth::attempt($request->only('email', 'password')))
                return Redirect::to('login');
        }
        //if everything passes, user is valid and logged in
        $user = Auth::user();
        $servers = \DB::table('servers')->whereNull('deleted_at')->get();
        return view('dashboard')
            ->with('name', $user->firstname . " " . $user->lastname)
            ->with('servers', $servers);
    }

    public function handleServer(Request $request) {
        if(!Auth::check())
            return Redirect::to('login');
        try {
            //this is an update
            if($request->input('id') != '-1') {
                //validate the elements
                request()->validate([
                    'name' => 'min:1|max:50',
                    'location' => 'min:1|max:50',
                    'status' => 'in:Up,Down,Maintenance',
                    'ipv4' => 'ipv4',
                ]);
                $id = '';
                $query = '';
                foreach($request->all() as $fieldName => $value) {
                    if($fieldName == 'id') {
                        $id = htmlspecialchars($value);
                        continue;
                    }
                    
                    $query .= htmlspecialchars($fieldName) . '= "' .  htmlspecialchars($value) . '"';
                }
                $currentTime = Carbon::now()->format('Y-m-d H:m:s');
                $query .= ',updated_at = "' .  $currentTime . '"';

                //this contains the pdo so I just give it the query so it can run
                \DB::statement("
                    UPDATE servers
                    SET $query
                    WHERE id = $id
                ");
                return response()->json([
                    'id' => $id,
                    'updated_at' => $currentTime
                ]); 
            } else { //this is an insert
                //validate the elements
                request()->validate([
                    'name' => 'required|min:1|max:50',
                    'location' => 'required|min:1|max:50',
                    'status' => 'required|in:Up,Down,Maintenance',
                    'ipv4' => 'required|ipv4',
                ]);
                $query = '';
                $DbFields = '';
                $name = '';
                foreach($request->all() as $fieldName => $value) {
                    if($fieldName == 'id') 
                        continue;

                    $DbFields .=  htmlspecialchars($fieldName) . ',';
                    $query .= '"' .  htmlspecialchars($value) . '",';

                    if($fieldName == 'name')
                        $name = $value;
                }
                $currentTime = Carbon::now()->format('Y-m-d H:m:s');
                $query .= '"' .  $currentTime . '","' .  $currentTime . '"';
                $DbFields .= 'created_at,updated_at';
                //this contains the pdo so I just give it the query so it can run
                \DB::statement("
                    INSERT INTO servers ($DbFields)
                    VALUES ($query)
                ");
                
                //i cheated here and used the laravel way to get the id. no need to verify $name it is done internaly
                return response()->json(
                    \DB::table('servers')->where('name', '=', $name)->first()
                ); 
            }
        } catch(\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400); 
        }
    }
    
    public function handleServerDelete(Request $request) {
        if(!Auth::check())
            return Redirect::to('login');
        try {
            //validate the elements
            request()->validate([
                'id' => 'required'
            ]);
            $id = htmlspecialchars($request->input('id'));
            $currentTime = Carbon::now()->format('Y-m-d H:m:s');
            \DB::statement("
                UPDATE servers 
                SET deleted_at = '$currentTime'
                where id = $id
            ");
            return response()->json([
                'id' => $id,
                'deleted_at' => $currentTime
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400); 
        }
    }
    public function logout() {
        \Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
