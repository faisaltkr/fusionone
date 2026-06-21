<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\NumberOfClientPC;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyRegistrationController extends Controller
{
    /**
     * Get all registered companies.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $companies = Company::with('clientPCs')->paginate(20);

        return response()->json([
            'status' => 'success',
            'message' => 'Companies retrieved successfully.',
            'data' => $companies,
        ]);
    }

    /**
     * Get a specific company by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $company = Company::with('clientPCs')->find($id);

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Company not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Company retrieved successfully.',
            'data' => $company,
        ]);
    }

    public function register(Request $request)
    {
        $uniqueRegisterId = $request->input('unique_register_id');
        $company = null;

        if ($uniqueRegisterId) {
            $company = Company::where('unique_register_id', $uniqueRegisterId)->first();

            if (! $company) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company not found for provided unique_register_id.',
                ], 404);
            }

            $request->validate([
                'hardware_id' => [
                    'required',
                    'string',
                    Rule::unique('number_of_client_pc')->where(function ($query) use ($request) {
                        return $query->where('app_id', $request->app_id);
                    }),
                ],
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'pc_name' => 'nullable|string|max:255',
                'type' => 'required|in:server,client',
                'app_id' => 'required|in:fusionOne,R-Pos,Pos',
                'pc_status' => 'nullable|in:active,inactive,surrender',
                'license_type' => 'nullable|in:demo,full',
            ]);

            $clientPc = NumberOfClientPC::create([
                'company_id' => $company->id,
                'hardware_id' => $request->hardware_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'pc_name' => $request->pc_name,
                'type' => $request->type,
                'app_id' => $request->app_id,
                'status' => $request->pc_status ?? 'active',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Client PC registered for existing company.',
                'data' => [
                    'company_id' => $company->id,
                    'unique_register_id' => $company->unique_register_id,
                    'client_pc_id' => $clientPc->id,
                ],
            ], 201);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:companies,email',
            'contact_person' => 'required|string|max:255',
            'place' => 'required|string|max:255',
            'address' => 'required|string|max:65535',
            'phone' => 'nullable|string|max:12',
            'password' => 'required|string|min:6|confirmed',
            'activation_count' => 'nullable|integer|min:0',
            'allowed_devices' => 'nullable|integer|min:1',
            'active_devices' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            'hardware_id' => [
                'required',
                'string',
                Rule::unique('number_of_client_pc')->where(function ($query) use ($request) {
                    return $query->where('app_id', $request->app_id);
                }),
            ],
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'pc_name' => 'nullable|string|max:255',
            'type' => 'required|in:server,client',
            'app_id' => 'required|in:fusionOne,R-Pos,Pos',
            'pc_status' => 'nullable|in:active,inactive,surrender',
            'license_type' => 'nullable|in:demo,full',
        ]);

        $uuid = Str::uuid();

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'unique_register_id' => $uuid,
            'contact_person' => $request->contact_person,
            'place' => $request->place,
            'address' => $request->address,
            'phone' => $request->phone,
            'activation_count' => $request->activation_count ?? 1,
            'allowed_devices' => $request->allowed_devices ?? 1,
            'active_devices' => $request->active_devices ?? 0,
            'status' => $request->status ?? true,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $clientPc = NumberOfClientPC::create([
            'company_id' => $company->id,
            'hardware_id' => $request->hardware_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'pc_name' => $request->pc_name,
            'type' => $request->type,
            'app_id' => $request->app_id,
            'status' => $request->pc_status ?? 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Company registered successfully.',
            'data' => [
                'company' => $company,
                'user' => $user,
                'client_pc' => $clientPc,
            ],
        ], 201);
    }

    /**
     * Delete a company.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Company not found.',
            ], 404);
        }

        // Delete associated user and devices
        User::where('id', $company->id)->delete();
        NumberOfClientPC::where('company_id', $company->id)->delete();

        $company->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Company deleted successfully.',
        ]);
    }
}
