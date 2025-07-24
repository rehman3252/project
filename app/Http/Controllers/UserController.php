<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['users'] = User::all();
        return response()->json([
            'staut' => true,
            'message' => 'All User Data',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['user'] = User::select(
            'id',
            'name',
            'email',
            'password'
        )->where('id', $id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Your single user data',
            'data' => $data,
        ], 201);
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
        $validateuser = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validateuser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Errors',
                'error' => $validateuser->errors()->all(),
            ], 404);
        }
        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $UpdatedUser = User::find($id);
        return response()->json([
            'status' => true,
            'message' => 'User Updated Successfully',
            'data' => $UpdatedUser,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $User = User::find($id);
        $User->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Deleted Successfully',
            'data' => $User,
        ], 200);
    }
}
