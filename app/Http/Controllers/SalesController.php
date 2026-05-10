<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesRequest;
use Illuminate\Http\Request; // Not strictly needed here, but often useful
use App\Services\SalesService;
use App\Models\Sale;
use Illuminate\Http\JsonResponse; // Better type hinting for JSON responses

class SalesController extends Controller
{
    protected SalesService $salesService; // Type-hint for clarity

    // The SalesService is injected here by Laravel's service container.
    // It should not depend on request-specific data in its constructor.
    public function __construct(SalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    /**
     * Get all sales records.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $sales = Sale::paginate(20);

        return response()->json([
            'status' => 'success',
            'message' => 'Sales retrieved successfully.',
            'data' => $sales,
        ]);
    }

    /**
     * Get a specific sale by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $sale = Sale::find($id);

        if (!$sale) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sale not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sale retrieved successfully.',
            'data' => $sale,
        ]);
    }

    /**
     * Store a newly created sale in storage.
     *
     * @param SalesRequest $request
     * @return JsonResponse
     */
    public function store(SalesRequest $request): JsonResponse
    {
        // Get the validated data from the form request
        $data = $request->validated();

        // Pass the validated data directly to the service method.
        // The service method will now handle the client lookup.
        $sale = $this->salesService->saveSaleData($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Sale created successfully.', // Corrected message for creation
            'sale' => $sale,
        ], 201); // Use 201 Created status code for resource creation
    }

    /**
     * Update the specified sale in storage.
     *
     * @param SalesRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(SalesRequest $request, int $id): JsonResponse
    {
        // Get the validated data from the form request
        $data = $request->validated();

        // Pass the sale ID and the validated data to the service method.
        $sale = $this->salesService->updateSaleData($id, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Sale updated successfully.',
            'sale' => $sale,
        ]);
    }

    /**
     * Delete a sale record.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $sale = Sale::find($id);

        if (!$sale) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sale not found.',
            ], 404);
        }

        $sale->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sale deleted successfully.',
        ]);
    }
}