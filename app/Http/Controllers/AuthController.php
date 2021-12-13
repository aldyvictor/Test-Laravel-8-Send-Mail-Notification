<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $message =
        [
            'name.required' => 'Kolom nama harus di isi !',
            'email.required' => 'Kolom Email harus di isi !',
            'email.email' => 'Format email salah !',
            'email.unique' => 'Email sudah tersedia, harap menggunakan email baru !',
            'password.required' => 'Kolom password harus di isi !',
            'password_confirmation.same' => 'Password harus sama !'
        ];

        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'same:password'
        ], $message);

        if ($validated->fails()) {
            return $validated->errors();
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
        }


        // Generate OTP code 6 digits
         $arr = [0,1,2,3,4,5,6,7,8,9];
         $arr = implode('',Arr::random($arr, 6));

         $otp_codes = OtpVerification::create([
            'email' => $request->email,
            'otp_code' => $arr,
            'user_id' => $user->id
         ]);


         Mail::to($user)->send(new SendOtpMail($otp_codes, $user->name));

        return response()->json([
            'message' => 'Input User '.$user->name.' Success'
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
