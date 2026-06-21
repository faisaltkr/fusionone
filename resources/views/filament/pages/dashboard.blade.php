<x-filament::page class="dashboard-page-bg min-h-screen p-6 font-sans text-slate-100">
    <div class="mx-auto max-w-7xl space-y-8">
        <header class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.35em] text-cyan-300/80">Admin Analytics</p>
                <h1 class="text-4xl font-semibold tracking-tight text-white">FusionOne Inventory Dashboard</h1>
                <p class="max-w-2xl text-slate-300">Live business metrics, transaction highlights and E-Invoice summaries in one polished Admin UI.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-slate-900/70 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-200">Material style</span>
                <x-filament::button color="secondary" class="bg-cyan-500/95 hover:bg-cyan-400/95 ring-1 ring-cyan-400/30 text-white">
                    <x-heroicon-o-sparkles class="w-4 h-4 mr-2" />
                    Animated UX
                </x-filament::button>
            </div>
        </header>

        <form wire:submit.prevent class="dashboard-glass-card grid gap-4 p-6 shadow-xl shadow-cyan-500/10 border border-white/10 xl:grid-cols-[1.8fr_1fr]">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="dashboard-card animate-slide-up rounded-3xl border border-cyan-400/20 bg-slate-950/80 p-5 shadow-[0_40px_120px_-60px_rgba(56,189,248,0.55)]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-cyan-200/70">Total Sales</p>
                            <p class="mt-4 text-3xl font-semibold text-white">₹ {{ number_format($this->getSalesTotal(), 2) }}</p>
                        </div>
                        <div class="dashboard-icon-box from-cyan-500 to-sky-500 text-white">
                            <x-heroicon-o-currency-rupee class="h-6 w-6" />
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-slate-300">Strong sales momentum powered by today's orders.</p>
                </div>

                <div class="dashboard-card animate-slide-up rounded-3xl border border-sky-400/20 bg-slate-950/80 p-5 shadow-[0_40px_120px_-60px_rgba(56,189,248,0.35)]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-sky-200/70">Total Purchases</p>
                            <p class="mt-4 text-3xl font-semibold text-white">₹ {{ number_format($this->getPurchaseTotal(), 2) }}</p>
                        </div>
                        <div class="dashboard-icon-box from-sky-500 to-indigo-500 text-white">
                            <x-heroicon-o-shopping-cart class="h-6 w-6" />
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-slate-300">Purchase volume tracked across all active suppliers.</p>
                </div>

                <div class="dashboard-card animate-slide-up rounded-3xl border border-amber-400/20 bg-slate-950/80 p-5 shadow-[0_40px_120px_-60px_rgba(245,158,11,0.28)]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-amber-200/70">VAT / Tax</p>
                            <p class="mt-4 text-3xl font-semibold text-white">₹ {{ number_format($this->getVatTotal(), 2) }}</p>
                        </div>
                        <div class="dashboard-icon-box from-amber-500 to-orange-500 text-white">
                            <x-heroicon-o-currency-rupee class="h-6 w-6" />
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-slate-300">Tax liability overview for the currently filtered period.</p>
                </div>
            </div>

            <div class="dashboard-summary-panel rounded-3xl p-5 bg-slate-950/85 border border-white/10">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-300/70">ZATCA E-Invoice Summary</p>
                        <h2 class="mt-3 text-xl font-semibold text-white">Live transaction health</h2>
                    </div>
                    <span class="rounded-full border border-cyan-400/25 bg-slate-900/70 px-4 py-2 text-xs uppercase tracking-[0.24em] text-cyan-200">Updated now</span>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-900/70 p-4 text-white">
                        <p class="text-sm uppercase tracking-[0.2em] text-cyan-200/70">Invoices</p>
                        <p class="mt-3 text-3xl font-semibold">0</p>
                        <p class="mt-2 text-sm text-slate-400">Successful / pending / failed</p>
                    </div>
                    <div class="rounded-3xl bg-slate-900/70 p-4 text-white">
                        <p class="text-sm uppercase tracking-[0.2em] text-sky-200/70">Debit Notes</p>
                        <p class="mt-3 text-3xl font-semibold">0</p>
                        <p class="mt-2 text-sm text-slate-400">Captured across today’s invoices.</p>
                    </div>
                </div>
            </div>
        </form>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_0.8fr]">
            <div class="dashboard-glass-card rounded-3xl p-6 shadow-xl shadow-cyan-500/10 border border-white/10 animate-slide-up">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-white">📊 E-Invoice totals</h3>
                        <p class="mt-2 text-sm text-slate-300">Quick overview of the latest invoice activity.</p>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center text-xs uppercase tracking-[0.24em] text-slate-400 sm:grid-cols-3">
                        <span class="rounded-full bg-slate-900/70 px-3 py-2">B2B</span>
                        <span class="rounded-full bg-slate-900/70 px-3 py-2">B2C</span>
                        <span class="rounded-full bg-slate-900/70 px-3 py-2">TOTAL</span>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="dashboard-table min-w-full text-sm text-left text-slate-200">
                        <thead>
                            <tr class="bg-slate-900/80 text-slate-300">
                                <th class="p-3">Type</th>
                                <th class="p-3">Success</th>
                                <th class="p-3">Pending</th>
                                <th class="p-3">Failed</th>
                                <th class="p-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (['B2B', 'B2C', 'TOTAL'] as $rowLabel)
                                <tr class="border-t border-slate-700/60 hover:bg-slate-900/70">
                                    @foreach (['invoice', 'debit', 'credit'] as $type)
                                        <td class="p-3 font-medium">{{ $rowLabel }}</td>
                                        <td class="p-3 text-emerald-300 font-semibold">0</td>
                                        <td class="p-3 text-yellow-300 font-semibold">0</td>
                                        <td class="p-3 text-rose-300 font-semibold">0</td>
                                        <td class="p-3 text-sky-300 font-semibold">0</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-glass-card rounded-3xl p-6 shadow-xl shadow-cyan-500/10 border border-white/10 animate-slide-up">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-semibold text-white">🧾 Recent Transactions</h3>
                        <p class="mt-2 text-sm text-slate-300">Most recent entries for your accounting review.</p>
                    </div>
                    <span class="rounded-full bg-slate-900/70 px-3 py-2 text-xs uppercase tracking-[0.18em] text-cyan-200">Last 20</span>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-200">
                        <thead class="bg-slate-900/80 text-slate-300">
                            <tr>
                                <th class="p-3">Date</th>
                                <th class="p-3">Customer</th>
                                <th class="p-3 text-right">Amount</th>
                                <th class="p-3 text-right">VAT</th>
                                <th class="p-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/60">
                            @foreach ($this->getRecentTransactions() as $txn)
                                <tr class="hover:bg-slate-900/70">
                                    <td class="p-3">{{ \Carbon\Carbon::parse($txn->date)->format('d-m-Y') }}</td>
                                    <td class="p-3">{{ $txn->customer_name ?? 'N/A' }}</td>
                                    <td class="p-3 text-right text-cyan-200">₹ {{ number_format($txn->net_amount, 2) }}</td>
                                    <td class="p-3 text-right text-emerald-300">₹ {{ number_format($txn->vat_amount, 2) }}</td>
                                    <td class="p-3 text-right text-sky-300">₹ {{ number_format($txn->grand_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
