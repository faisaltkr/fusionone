<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\User;
use Exception; // Import the Exception class

class SalesService
{
    // No properties for request-specific data needed here, as it's passed via methods.
    // protected Sale $sale; // This property is not needed if you're creating/finding directly in methods
    // protected ?User $client = null; // Removed, client lookup now happens per-method

    // The constructor should not depend on request-specific data.
    // It's used for injecting other dependencies if needed, e.g., a repository.
    public function __construct()
    {
        // For example, if you had a SaleRepository:
        // $this->saleRepository = $saleRepository;
    }

    /**
     * Resolves the client (User model) based on the company_registration_id.
     *
     * @param string|null $companyRegistrationId
     * @return User
     * @throws Exception
     */
    private function resolveClient(?string $companyRegId): User
    {
        if (is_null($companyRegId)) {
            throw new Exception('Client registration ID cannot be null.');
        }

        $client = User::where('company_reg_id', $companyRegId)->first();

        if (!$client) {
            throw new Exception('Client not found or unauthorized.');
        }

        return $client;
    }

    /**
     * Validate and prepare sale data for storage.
     * This method now also handles client resolution.
     *
     * @param array $data
     * @return array
     * @throws Exception If client is not found.
     */
    private function prepareSaleData(array $data): array
    {
        // Resolve the client for the current operation
        $client = $this->resolveClient($data['client_id'] ?? null);

        // Populate the data array with client-specific IDs
        return [
            'company_id' => $client->id,
            'branch_id' => $client->branch_id ?? null, // Assuming branch_id exists on User model
            'entry_no' => $data['entry_no'],
            'sales_sale_return_no' => $data['sales_sale_return_no'],
            'customer_name' => $data['customer_name'],
            'transaction_type' => $data['transaction_type'],
            'customer_id' => $data['customer_id'] ?? null, // Assuming customer_id is optional
            'mode_of_transaction' => $data['mode_of_transaction'],
            'gross_amount' => $data['gross_amount'],
            'discount' => $data['discount'] ?? 0.00,
            'net_amount' => $data['net_amount'] ?? 0.00,
            'vat_amount' => $data['vat_amount'] ?? 0.00,
            'grand_amount' => $data['grand_amount'] ?? 0.00,
            // Add other fields from your Sales table as needed
        ];
    }

    /**
     * Save new sale data to the database.
     *
     * @param array $data The validated request data.
     * @return Sale The newly created Sale model instance.
     * @throws Exception If client not found or data preparation fails.
     */
    public function saveSaleData(array $data): Sale
    {
        // Prepare the data including client resolution
        $preparedData = $this->prepareSaleData($data);

        // Create and return the new Sale instance
        $sale = Sale::create($preparedData);

        return $sale;
    }

    /**
     * Update existing sale data in the database.
     *
     * @param int $id The ID of the sale to update.
     * @param array $data The validated request data.
     * @return Sale The updated Sale model instance.
     * @throws Exception If sale not found, client not found or data preparation fails.
     */
    public function updateSaleData(int $id, array $data): Sale
    {
        // Find the sale by ID, throws ModelNotFoundException if not found
        $sale = Sale::findOrFail($id);

        // Prepare the data including client resolution
        $preparedData = $this->prepareSaleData($data);

        // Update the sale
        $sale->update($preparedData);

        return $sale;
    }
}