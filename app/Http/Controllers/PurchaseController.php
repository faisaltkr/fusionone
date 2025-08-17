<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request; // Not strictly needed here, but often useful
use App\Services\PurchaseService;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse; // Better type hinting for JSON responses

class PurchaseController extends Controller
{
    

    // The SalesService is injected here by Laravel's service container.
    // It should not depend on request-specific data in its constructor.
    public function __construct(protected PurchaseService $purchaseService){}

    /**
     * Store a newly created sale in storage.
     *
     * @param SalesRequest $request
     * @return JsonResponse
     */
    public function store(PurchaseRequest $request): JsonResponse
    {
        // Get the validated data from the form request
        $data = $request->validated();

        // Pass the validated data directly to the service method.
        // The service method will now handle the client lookup.
        $purchase = $this->purchaseService->savePurchaseData($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Purchase created successfully.', // Corrected message for creation
            'purchase' => $purchase,
        ], 201); // Use 201 Created status code for resource creation
    }

    /**
     * Update the specified sale in storage.
     *
     * @param SalesRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PurchaseRequest $request, int $id): JsonResponse
    {
        // Get the validated data from the form request
        $data = $request->validated();

        // Pass the sale ID and the validated data to the service method.
        $purchase = $this->purchaseService->updatePurchaseData($id, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Purchase updated successfully.',
            'purchase' => $purchase,
        ]);
    }
}