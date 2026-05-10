<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\EInvoiceTransactionLog;
use App\Models\User;
use Exception;

class EInvoiceTransactionLogController extends Controller
{
    /**
     * Get all invoice logs.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $logs = EInvoiceTransactionLog::paginate(20);

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice logs retrieved successfully.',
            'data' => $logs,
        ]);
    }

    /**
     * Get a specific invoice log by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $log = EInvoiceTransactionLog::find($id);

        if (!$log) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice log not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice log retrieved successfully.',
            'data' => $log,
        ]);
    }

    public function store(Request $request)
    {

        $this->resolveClient();

        $validated = $request->validate([
            'invoice_type' => 'required|string|max:50',
            'invoice_id' => 'required|integer',
            'invoice_transaction_type' => 'required|string|max:50',
            'invoice_date' => 'required|date',
            'qr_code' => 'nullable|string',
            'zatca_status' => 'nullable|string|max:50',
            'invoice_base64' => 'nullable|string',
            'invoice_file_name' => 'nullable|string|max:255',
            'invoice_counter_value' => 'nullable|integer',
            'invoice_reported' => 'nullable|string|max:50',
            'invoice_cleared' => 'nullable|string|max:50',
            'invoice_hash' => 'nullable|string|max:255',
            'buyer_name' => 'nullable|string|max:255',
            'buyer_vat_no' => 'nullable|string|max:15',
            'seller_name' => 'nullable|string|max:255',
            'buyer_address' => 'nullable|string',
            'seller_address' => 'nullable|string',
            'seller_vat_no' => 'nullable|string|max:15',
            'previous_invoice_hash' => 'nullable|string|max:255',
            'validation_results' => 'nullable|string',
            'error_results' => 'nullable|string',
            'zatca_response_code' => 'nullable|string|max:10',
            'einvoice_sync_time' => 'nullable|date',
            'einvoice_uu_id' => 'nullable|string|max:150',
            'einvoice_no' => 'nullable|string|max:50',
            'resend' => 'nullable|boolean',
        ]);

        $log = EInvoiceTransactionLog::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Invoice log inserted successfully',
            'data' => $log
        ]);
    }

    /**
     * Delete an invoice log record.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $log = EInvoiceTransactionLog::find($id);

        if (!$log) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice log not found.',
            ], 404);
        }

        $log->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice log deleted successfully.',
        ]);
    }

    private function resolveClient(): User
    {
        $companyRegId = request()->header('clientId');
        if (is_null($companyRegId)) {
            throw new Exception('Client registration ID cannot be null.');
        }

        $client = User::where('company_reg_id', $companyRegId)->first();

        if (!$client) {
            throw new Exception('Client not found or unauthorized.');
        }

        return $client;
    }

}
