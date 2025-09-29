<?php

namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Validator;
use App\Models\Employees;

class ChangePasswordController extends Controller
 {
    public function index()
 {
        return view( 'superadm.change-password' );
    }

    public function updatePassword( Request $request )
 {
        $validator = Validator::make( $request->all(), [
            'new_password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^(?=(?:.*\d){2,})(?=(?:.*[A-Za-z]){5,})(?=.*[^A-Za-z0-9]).+$/'
            ],
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.required' => 'Enter employee password',
            'new_password.min'      => 'Password must be at least 8 characters',
            'new_password.max'      => 'Password must not exceed 255 characters',
            'new_password.regex'    => 'Password must contain at least 2 digits, 5 letters, and 1 special character',
            'confirm_password.required' => 'Please confirm your password',
            'confirm_password.same'     => 'New Password & Confirm Password must match',
        ] );

        if ( $validator->fails() ) {
            return redirect()->back()->withErrors( $validator )->withInput();
        }

        $userId = Session::get( 'user_id' );
        if ( !$userId ) {
            return redirect()->back()->with( 'error', 'Password not updated!' );
        }

        $user = Employees::find( $userId );
        if ( !$user ) {
            return redirect()->back()->with( 'error', 'Password not updated!' );
        }
        Employees::where( 'id', $userId )->update( [
            'employee_password' => bcrypt( $request->new_password ),
        ] );
            Session::flush(); 
            auth()->logout();
        return redirect()->back()->with( 'success', 'Password updated successfully!' );
    }
}
