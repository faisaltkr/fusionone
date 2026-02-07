<?php

namespace App\Http\Controllers;

use App\Models\Company;
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
            'contact_person' => 'required|string|max:255',
            'place'        => 'required|string|max:255',
            'address'      => 'required|string|max:65535',
            'phone'        => 'nullable|string|max:12',
            'password'     => 'required|string|min:6|confirmed',
            'activation_count' => 'nullable|integer|min:0',
            'allowed_devices'  => 'nullable|integer|min:1',
            'active_devices'   => 'nullable|integer|min:0',
            'status'       => 'nullable|boolean',

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
        $company = Company::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'unique_register_id'  => $uuid,
            'contact_person'  => $request->contact_person,
            'place'           => $request->place,
            'address'         => $request->address,
            'phone'           => $request->phone,
            'password'        => Hash::make($request->password),
            'activation_count' => $request->activation_count ?? 1,
            'allowed_devices' => $request->allowed_devices ?? 1,
            'active_devices'  => $request->active_devices ?? 0,
            'status'          => $request->status ?? true,
        ]);

        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'password'        => Hash::make($request->password),
        ]);

        // Create the main PC record
        NumberOfClientPC::create([
            'company_id'  => $company->id,
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
