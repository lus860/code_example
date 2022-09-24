<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        if (!$users) {
            return response()->json([
                'success' => false,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'users' => $users,
        ], Response::HTTP_OK);
    }

    public function forTestSessionCookie()
    {
        if (Session::get('user') != "Ben") {
            abort(404);
        }

        if (Cookie::get('color') != "red") {
            abort(404);
        }

        return "hello";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->header("ExampleHeader") != "Example") {
            abort(404);
        }

        if ($request->data != "HelloWorld") {
            abort(404);
        }

        return response()->json([
                'status' => "success"
            ])->withHeaders([
                "ResponseHeader" => "Response"
            ]);
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
        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
            ], Response::HTTP_NOT_FOUND);
        }
        $user->name = $request->name;
        if ($user->save()) {
            return response()->json([
                'success' => true,
                'user' => $user->refresh(),
            ], Response::HTTP_OK);
        }
    }

}
