# FusionOneAdmin API - Complete CRUD Operations Guide

## API Overview

All APIs are documented in **Swagger UI** and accessible at:
- **Swagger UI**: `http://fusiononeadmin.test/swagger`
- **OpenAPI Spec**: `http://fusiononeadmin.test/openapi.json`

---

## Available Endpoints

### 1. **Sales Management**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/sales` | Get all sales (paginated) |
| GET | `/api/sales/{id}` | Get a specific sale by ID |
| POST | `/api/sales` | Create a new sale |
| PUT | `/api/sales/{id}` | Update an existing sale |
| DELETE | `/api/sales/{id}` | Delete a sale |

**Required Fields for Create/Update:**
- `client_id` (UUID)
- `entry_no` (integer)
- `sales_sale_return_no` (string)
- `customer_name` (string)
- `transaction_type` (string)
- `mode_of_transaction` (string)
- `gross_amount` (number)

**Optional Fields:**
- `discount` (number)
- `net_amount` (number)
- `vat_amount` (number)
- `grand_amount` (number)

---

### 2. **Purchase Management**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/purchases` | Get all purchases (paginated) |
| GET | `/api/purchases/{id}` | Get a specific purchase by ID |
| POST | `/api/purchases` | Create a new purchase |
| PUT | `/api/purchases/{id}` | Update an existing purchase |
| DELETE | `/api/purchases/{id}` | Delete a purchase |

**Required Fields for Create/Update:**
- `client_id` (UUID)
- `entry_no` (integer)
- `purchase_purchase_return_no` (string)
- `supplier_name` (string)
- `transaction_type` (string)
- `mode_of_transaction` (string)
- `gross_amount` (number)
- `tr_date` (date)

**Optional Fields:**
- `discount` (number)
- `net_amount` (number)
- `vat_amount` (number)
- `grand_amount` (number)

---

### 3. **Company Registration**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/companies` | Get all companies (paginated) |
| GET | `/api/companies/{id}` | Get a specific company by ID |
| POST | `/api/register-company` | Register a new company |
| DELETE | `/api/companies/{id}` | Delete a company |

**Required Fields for Registration:**
- `name` (string, max 255)
- `email` (email, must be unique)
- `contact_person` (string)
- `place` (string)
- `address` (string)
- `password` (string, min 6 chars)
- `password_confirmation` (string)
- `hardware_id` (string, unique per app)
- `type` (enum: "server" or "client")
- `app_id` (enum: "fusionOne", "R-Pos", or "Pos")

**Optional Fields:**
- `phone` (string)
- `activation_count` (integer)
- `allowed_devices` (integer, min 1)
- `active_devices` (integer)
- `status` (boolean)
- `latitude` (number)
- `longitude` (number)
- `pc_name` (string)

---

### 4. **E-Invoice Transaction Logs**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/invoice-logs` | Get all invoice logs (paginated) |
| GET | `/api/invoice-logs/{id}` | Get a specific invoice log by ID |
| POST | `/api/invoice-logs` | Create a new invoice log |
| DELETE | `/api/invoice-logs/{id}` | Delete an invoice log |

**Required Fields for Create:**
- `invoice_type` (string, max 50)
- `invoice_id` (integer)
- `invoice_transaction_type` (string, max 50)
- `invoice_date` (date)

**Required Header:**
- `clientId` (Client registration ID)

**Optional Fields:**
- `qr_code` (string)
- `zatca_status` (string)
- `invoice_base64` (string)
- `invoice_file_name` (string)
- `invoice_counter_value` (integer)
- `invoice_reported` (string)
- `invoice_cleared` (string)
- `invoice_hash` (string)
- `buyer_name` (string)
- `buyer_vat_no` (string, max 15)
- `seller_name` (string)
- `buyer_address` (string)
- `seller_address` (string)
- `seller_vat_no` (string)
- `previous_invoice_hash` (string)
- `validation_results` (string)
- `error_results` (string)
- `zatca_response_code` (string)
- `einvoice_sync_time` (datetime)
- `einvoice_uu_id` (string)
- `einvoice_no` (string)
- `resend` (boolean)

---

### 5. **User Authentication**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/user` | Get authenticated user (requires Sanctum token) |

**Security:** Requires `Authorization: Bearer {token}` header

---

## How to Test via Swagger

### Step 1: Open Swagger UI
Navigate to `http://fusiononeadmin.test/swagger`

### Step 2: Test GET Endpoints (No Auth Required)
1. Click on any **GET** endpoint (e.g., `GET /sales`)
2. Click the **"Try it out"** button
3. Click **"Execute"** to see results

### Step 3: Test POST Endpoints
1. Click on **POST** endpoint (e.g., `POST /sales`)
2. Click **"Try it out"**
3. In the request body, enter JSON data with required fields
4. Click **"Execute"**

**Example POST body for `/sales`:**
```json
{
  "client_id": "550e8400-e29b-41d4-a716-446655440000",
  "entry_no": 1,
  "sales_sale_return_no": "INV-001",
  "customer_name": "John Doe",
  "transaction_type": "sale",
  "mode_of_transaction": "cash",
  "gross_amount": 1000.00,
  "discount": 100.00,
  "net_amount": 900.00,
  "vat_amount": 135.00,
  "grand_amount": 1035.00
}
```

### Step 4: Test PUT (Update) Endpoints
1. Click **PUT** endpoint (e.g., `PUT /sales/{id}`)
2. Enter the **ID** in the path parameter
3. Click **"Try it out"**
4. Modify the JSON in request body
5. Click **"Execute"**

### Step 5: Test DELETE Endpoints
1. Click **DELETE** endpoint (e.g., `DELETE /sales/{id}`)
2. Enter the **ID** in the path parameter
3. Click **"Try it out"**
4. Click **"Execute"**

---

## Response Format

All endpoints follow this response structure:

### Success Response (2xx)
```json
{
  "status": "success",
  "message": "Operation description",
  "data": {}
}
```

### Error Response (4xx/5xx)
```json
{
  "status": "error",
  "message": "Error description"
}
```

---

## Testing Workflow Example

### 1. Create a Company
```
POST /api/register-company
{
  "name": "ABC Corporation",
  "email": "abc@example.com",
  "contact_person": "Mr. Ahmed",
  "place": "Riyadh",
  "address": "123 Main Street",
  "password": "password123",
  "password_confirmation": "password123",
  "hardware_id": "HW-001-ABC",
  "type": "server",
  "app_id": "fusionOne"
}
```

### 2. Create a Sale
```
POST /api/sales
{
  "client_id": "550e8400-e29b-41d4-a716-446655440000",
  "entry_no": 1,
  "sales_sale_return_no": "SAL-001",
  "customer_name": "Customer Name",
  "transaction_type": "sale",
  "mode_of_transaction": "cash",
  "gross_amount": 5000.00
}
```

### 3. Get All Sales
```
GET /api/sales
```

### 4. Get Specific Sale
```
GET /api/sales/1
```

### 5. Update a Sale
```
PUT /api/sales/1
{
  "customer_name": "Updated Customer Name",
  ...other fields...
}
```

### 6. Delete a Sale
```
DELETE /api/sales/1
```

---

## Tips for Testing

1. **Use Real UUIDs**: For `client_id`, use valid UUID format
2. **Check Required Fields**: Always include all required fields
3. **Date Format**: Use `YYYY-MM-DD` format for dates
4. **Test Order**: Create → Read → Update → Delete (CRUD)
5. **Response Status**: 
   - 201 = Resource created
   - 200 = Success
   - 404 = Not found
   - 422 = Validation error
6. **Headers**: For invoice logs, include `clientId` header with request

---

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| 404 Not Found | Ensure the resource ID exists or is correct |
| 422 Validation Error | Check all required fields are provided |
| Missing clientId header | Include `clientId` header for invoice logs |
| UUID format error | Use valid UUID format for client_id |
| Unique constraint error | Email or hardware_id already exists |

---

## Database Seeders (Optional)

To populate test data, run:
```bash
php artisan migrate:fresh --seed
```

This will create sample companies, sales, purchases, and invoice logs for testing.

---

## Support

For API documentation updates or issues, check:
- OpenAPI spec: `public/openapi.json`
- Swagger UI: `/swagger`
- Controllers: `app/Http/Controllers/`
- Routes: `routes/api.php`
