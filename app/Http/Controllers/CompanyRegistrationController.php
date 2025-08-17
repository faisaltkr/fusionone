<?php

namespace App\Http\Controllers;

use App\Models\NumberOfClientPC;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyRegistrationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'company_name' => 'required|string',
            'place'        => 'required|string',
            'address'      => 'required|string',
            'phone'        => 'required|string|max:15',
            'password'     => 'required|string|min:6|confirmed',

            // Client PC fields
            'hardware_id'  => [
                'required',
                'string',
                Rule::unique('number_of_client_pc')->where(function ($query) use ($request) {
                    return $query->where('app_id', $request->app_id);
                }),
            ],
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'pc_name'      => 'nullable|string',
            'type'         => 'required|in:server,client',
            'app_id'       => 'required|in:fusionOne,R-Pos,Pos',
            'status'       => 'nullable|in:activate,deactivate,surrender',
        ]);


        // Generate UUID for company
        $uuid = Str::uuid();

        // Create the user (company)
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'company_reg_id'  => $uuid,
            'company_name'    => $request->company_name,
            'place'           => $request->place,
            'address'         => $request->address,
            'phone'           => $request->phone,
            'password'        => Hash::make($request->password),
            'user_type'       => 'admin',
        ]);

        // Create the main PC record
        NumberOfClientPC::create([
            'company_id'  => $user->id,
            'hardware_id' => $request->hardware_id,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'pc_name'     => $request->pc_name,
            'type'        => $request->type,
            'app_id'      => $request->app_id,
            'status'      => $request->status ?? 'activate',
        ]);

        return response()->json([
            'message' => 'Company registered successfully',
            'user'    => $user,
        ], 201);
    }
}
