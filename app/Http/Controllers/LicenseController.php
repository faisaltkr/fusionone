<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\License;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    /**
     * List all licenses.
     */
    public function index(): JsonResponse
    {
        $licenses = License::with('company')->latest()->paginate(20);

        return response()->json([
            'status' => 'success',
            'message' => 'Licenses retrieved successfully.',
            'data' => $licenses,
        ]);
    }

    /**
     * Show a single license.
     */
    public function show(int $id): JsonResponse
    {
        $license = License::with('company')->find($id);

        if (! $license) {
            return response()->json([
                'status' => 'error',
                'message' => 'License not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'License retrieved successfully.',
            'data' => $license,
        ]);
    }

    /**
     * Activation request sent by the desktop app on install/activation.
     * Creates a pending license row (without a license key) holding the
     * client details. An administrator later generates the key from the UI.
     */
    public function activate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'unique_register_id' => 'required|string|exists:companies,unique_register_id',
            'app_id' => 'required|in:fusionOne,R-Pos,Pos',
            'hardware_id' => 'required|string|max:255',
            'license_type' => 'nullable|in:demo,full',
        ]);

        $company = Company::where('unique_register_id', $validated['unique_register_id'])->first();
        $licenseType = $validated['license_type'] ?? 'demo';

        // Already activated and still valid? Return the existing license as-is.
        $existing = License::where('app_id', $validated['app_id'])
            ->where('hardware_id', $validated['hardware_id'])
            ->first();

        if ($existing && $existing->license_key && $existing->status === 'active' && ! $existing->isExpired()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Device already has an active license.',
                'data' => $existing,
            ]);
        }

        $license = License::updateOrCreate(
            [
                'app_id' => $validated['app_id'],
                'hardware_id' => $validated['hardware_id'],
            ],
            [
                'company_id' => $company->id,
                'unique_register_id' => $company->unique_register_id,
                'license_type' => $licenseType,
                'expiry' => License::calculateExpiry($licenseType),
                'license_key' => null,
                'support_expiry_date' => null,
                'status' => 'pending',
                'activated_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Activation request received. Awaiting license key generation by administrator.',
            'data' => $license,
        ], 201);
    }

    /**
     * Verify a license key for a device/app. Called by the desktop app to
     * confirm the license is valid and read its expiry details.
     */
    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'app_id' => 'required|in:fusionOne,R-Pos,Pos',
            'hardware_id' => 'required|string',
            'license_key' => 'required|string',
        ]);

        $license = License::where('app_id', $validated['app_id'])
            ->where('hardware_id', $validated['hardware_id'])
            ->where('license_key', $validated['license_key'])
            ->first();

        if (! $license) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid license key.',
                'valid' => false,
            ], 404);
        }

        if ($license->status === 'revoked') {
            return response()->json([
                'status' => 'error',
                'message' => 'License has been revoked.',
                'valid' => false,
            ], 403);
        }

        if ($license->isExpired()) {
            if ($license->status !== 'expired') {
                $license->update(['status' => 'expired']);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'License has expired.',
                'valid' => false,
                'data' => [
                    'license_type' => $license->license_type,
                    'expiry' => $license->expiry,
                ],
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'License is valid.',
            'valid' => true,
            'data' => [
                'license_type' => $license->license_type,
                'status' => $license->status,
                'expiry' => $license->expiry,
                'support_expiry_date' => $license->support_expiry_date,
            ],
        ]);
    }
}
