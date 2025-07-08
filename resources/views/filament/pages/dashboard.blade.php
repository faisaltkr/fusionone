<x-filament::page class="bg-gray-50 p-6 font-sans text-gray-800">
    <form wire:submit.prevent class="mb-6">
        <div class="flex flex-col md:flex-row items-end gap-4 bg-white p-4 rounded-xl shadow border border-gray-200">
            <div class="w-full md:w-1/4">
                {{ $this->form->getComponent('from_date') }}
            </div>
            <div class="w-full md:w-1/4">
                {{ $this->form->getComponent('to_date') }}
            </div>
            <div class="w-full md:w-1/2 flex justify-end">
                <x-filament::button type="submit" color="primary">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4 mr-1" />
                    Filter
                </x-filament::button>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 font-sans">
        <x-filament::card class="bg-green-50 border border-green-200">
            <div class="flex items-center gap-4">
                <x-heroicon-o-currency-rupee class="w-8 h-8 text-green-600" />
                <div>
                    <p class="text-sm text-green-700">Total Sales</p>
                    <p class="text-2xl font-bold text-green-900">₹ {{ number_format($this->getSalesTotal(), 2) }}</p>
                </div>
            </div>
        </x-filament::card>

        <x-filament::card class="bg-blue-50 border border-blue-200">
            <div class="flex items-center gap-4">
                <x-heroicon-o-shopping-cart class="w-8 h-8 text-blue-600" />
                <div>
                    <p class="text-sm text-blue-700">Total Purchases</p>
                    <p class="text-2xl font-bold text-blue-900">₹ {{ number_format($this->getPurchaseTotal(), 2) }}</p>
                </div>
            </div>
        </x-filament::card>

        <x-filament::card class="bg-yellow-50 border border-yellow-200">
            <div class="flex items-center gap-4">
                <x-heroicon-o-currency-rupee class="w-8 h-8 text-yellow-600" />
                <div>
                    <p class="text-sm text-yellow-700">VAT / Tax</p>
                    <p class="text-2xl font-bold text-yellow-900">₹ {{ number_format($this->getVatTotal(), 2) }}</p>
                </div>
            </div>
        </x-filament::card>
    </div>

    <div class="mt-10">
        <x-filament::section>
            <x-slot name="heading">📊 ZATCA E-Invoice Summary</x-slot>

            <p class="text-lg font-semibold text-gray-800 mb-4">Sales = ₹ {{ number_format($this->getSalesTotal(), 2) }}</p>

            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full border text-sm text-center">
                    <thead>
                        <tr class="bg-purple-100 text-purple-800 text-xs font-bold">
                            <th colspan="5">Invoices - Today</th>
                            <th colspan="5">Debit Notes - Today</th>
                            <th colspan="5">Credit Notes - Today</th>
                        </tr>
                        <tr class="bg-gray-100 text-gray-700 font-semibold">
                            @foreach (['Type', 'Success', 'Pending', 'Failed', 'Total'] as $label)
                                <th class="p-2">{{ $label }}</th>
                            @endforeach
                            @foreach (['Type', 'Success', 'Pending', 'Failed', 'Total'] as $label)
                                <th class="p-2">{{ $label }}</th>
                            @endforeach
                            @foreach (['Type', 'Success', 'Pending', 'Failed', 'Total'] as $label)
                                <th class="p-2">{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @foreach (['B2B', 'B2C', 'TOTAL'] as $rowLabel)
                            <tr class="border-t bg-white hover:bg-gray-50">
                                @foreach (['invoice', 'debit', 'credit'] as $type)
                                    <td class="p-2 font-medium">{{ $rowLabel }}</td>
                                    <td class="p-2 text-green-600 font-bold">0</td>
                                    <td class="p-2 text-yellow-500 font-bold">0</td>
                                    <td class="p-2 text-red-600 font-bold">0</td>
                                    <td class="p-2 text-blue-600 font-bold">0</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>

    <div class="mt-10">
        <x-filament::section>
            <x-slot name="heading">🧾 Recent Transactions</x-slot>

            <table class="w-full mt-4 text-sm border border-gray-200">
                <thead class="bg-gray-100 text-left font-semibold">
                    <tr>
                        <th class="p-2">Date</th>
                        <th class="p-2">Customer</th>
                        <th class="p-2 text-right">Amount</th>
                        <th class="p-2 text-right">VAT</th>
                        <th class="p-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->getRecentTransactions() as $txn)
                        <tr class="border-t bg-white hover:bg-gray-50">
                            <td class="p-2">{{ \Carbon\Carbon::parse($txn->date)->format('d-m-Y') }}</td>
                            <td class="p-2">{{ $txn->customer_name ?? 'N/A' }}</td>
                            <td class="p-2 text-right">₹ {{ number_format($txn->net_amount, 2) }}</td>
                            <td class="p-2 text-right">₹ {{ number_format($txn->vat_amount, 2) }}</td>
                            <td class="p-2 text-right">₹ {{ number_format($txn->grand_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-filament::section>
    </div>
</x-filament::page>
