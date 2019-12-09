<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Password;

class PasswordController extends Controller
{

    /**
     * Adding authentication as middleware instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    /**
     * Validation
     *
     * @return boolean
     */
    private function validatePassword($request)
    {
        return Validator::make($request->all(), [
            'website' => 'required|string|max:255',
            'account' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json('Wrong Credentials',401);
        }
        $passwords = Password::where('user_id', '=', $user->id)
                            ->orderBy('website','asc')
                            ->get();
        return response()->json($passwords);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validatePassword($request);
        if ($validator->fails()) {
            $response = ["errors" => $validator->messages()];
            return response()->json($response,400);
        }
        //Still in testing mode
        $password = new Password;
        $password->website = $request->website;
        $password->account = $request->account;
        $password->password = $request->password;
        $password->user_id = auth()->user()->id;
        $password->save();

        return response()->json($password,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validatePassword($request);
        if ($validator->fails()) {
            $response = ["errors" => $validator->messages()];
            return response()->json($response,400);
        }

        $password = Password::find($id);
        if ($password->user_id !== auth()->user()->id)
        {
            return response()->json('Unauthorized',400);
        }
        $password->website = $request->website;
        $password->account = $request->account;
        $password->password = $request->password;
        $password->save();
        
        return response()->json($password,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $password = Password::find($id);

        if(!$password) {
            return response()->json('No Password with this ID',400);
        }

        if ($password->user_id !== auth()->user()->id){
            return response()->json('Unauthorized',400);
        }
        $password->delete();
        return response()->json('Password Deleted Successfully',200);
    }
}
