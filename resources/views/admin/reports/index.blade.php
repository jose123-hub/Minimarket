@push('portal-styles')
<style>
.reports-toolbar {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 18px;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.report-tabs {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.tab-btn {
    border: none;
    background: #f2f2f2;
    color: #111;
    padding: 11px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 800;
    transition: 0.2s ease;
}

.tab-btn.active {
    background: #e8192c;
    color: #fff;
}

.tab-btn:hover {
    transform: translateY(-1px);
}

.toolbar-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.date-filter {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.date-filter input {
    height: 38px;
    padding: 0 12px;
    border: 1px solid #ddd;
    border-radius: 9px;
    background: #fff;
    color: #111;
    font-weight: 600;
    font-size: 13px;
    outline: none;
}

.date-filter input:focus {
    border-color: #e8192c;
}

.date-filter button {
    height: 38px;
    padding: 0 16px;
    background: #111;
    color: #fff;
    border: none;
    border-radius: 9px;
    font-weight: 800;
    cursor: pointer;
    font-size: 13px;
}

.date-sep {
    color: #999;
    font-weight: 700;
}

.download-box {
    display: flex;
    align-items: center;
    gap: 8px;
}

.download-label {
    font-size: 13px;
    color: #777;
    font-weight: 700;
}

.download-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-download {
    height: 38px;
    padding: 0 14px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: auto;
    transition: 0.2s ease;
}

.btn-download:hover {
    transform: translateY(-1px);
}

.btn-pdf {
    color: #e8192c;
    border: 1px solid #e8192c;
}

.btn-excel {
    color: #00a651;
    border: 1px solid #00a651;
}

.section-title {
    margin: 16px 0 12px;
}

.section-title h2 {
    font-size: 18px;
    font-weight: 900;
    color: #111;
}

.section-title p {
    font-size: 13px;
    color: #888;
    margin-top: 2px;
}

.metrics {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 18px;
}

.metric-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 14px;
    padding: 22px;
    min-height: 120px;
}

.metric-label {
    font-size: 13px;
    color: #666;
    margin-bottom: 12px;
}

.metric-value {
    font-size: 32px;
    font-weight: 900;
    color: #000;
}

.metric-change {
    font-size: 12px;
    color: #00a651;
    margin-top: 10px;
}

.metric-change.negative {
    color: #e8192c;
}

.charts-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

.chart-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e5e5e5;
    padding: 20px;
}

.chart-header {
    margin-bottom: 14px;
}

.chart-header h3 {
    font-size: 15px;
    font-weight: 800;
    color: #111;
}

.chart-header p {
    font-size: 12px;
    color: #888;
    margin-top: 3px;
}

.chart-wrap {
    height: 280px;
}

.table-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e5e5e5;
    padding: 20px;
    margin-bottom: 20px;
}

.table-header {
    margin-bottom: 16px;
}

.table-header h3 {
    font-size: 15px;
    font-weight: 800;
    color: #111;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    font-size: 11px;
    color: #999;
    font-weight: 700;
    text-align: left;
    padding: 9px 0;
    border-bottom: 1px solid #f0f0f0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

td {
    font-size: 13px;
    color: #333;
    padding: 12px 0;
    border-bottom: 1px solid #f9f9f9;
}

tr:last-child td {
    border-bottom: none;
}

.invoice {
    font-family: monospace;
    font-weight: 700;
    color: #111;
}

.method-badge {
    display: inline-flex;
    padding: 3px 8px;
    background: #f5f5f5;
    color: #555;
    border-radius: 6px;
    font-size: 11px;
}

.total-amount {
    font-weight: 800;
    color: #111;
}

.empty {
    text-align: center;
    color: #bbb;
    font-size: 13px;
    padding: 32px 0;
}

.report-section {
    display: none;
    width: 100%;
}

.report-section.active {
    display: block;
}

@media (max-width: 1200px) {
    .metrics {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .charts-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 900px) {
    .reports-toolbar {
        align-items: flex-start;
    }

    .toolbar-actions {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 700px) {
    .main {
        margin-left: 0;
        width: 100%;
    }

    .sidebar {
        display: none;
    }

    .topbar {
        padding: 14px 18px;
    }

    .content {
        padding: 18px;
    }

    .metrics {
        grid-template-columns: 1fr;
    }

    .date-filter,
    .download-box,
    .download-actions {
        width: 100%;
    }

    .date-filter input,
    .date-filter button,
    .btn-download {
        width: 100%;
    }
}
</style>
@endpush

<x-portal-layout
    title="Reports"
    subtitle="Sales, purchases and inventory analysis"
    active="reports"
>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

    <div class="reports-toolbar">
    <div class="report-tabs">
        <button class="tab-btn active" data-tab="sales">Sales</button>
        <button class="tab-btn" data-tab="purchases">Purchases</button>
        <button class="tab-btn" data-tab="inventory">Inventory</button>
        <button class="tab-btn" data-tab="customers">Customers</button>
    </div>

    <div class="toolbar-actions">
        <form method="GET" action="{{ route('admin.reports') }}" class="date-filter">
            <input type="date" name="start_date" value="{{ $startDate }}">
            <span class="date-sep">→</span>
            <input type="date" name="end_date" value="{{ $endDate }}">
            <button type="submit">Apply</button>
        </form>

        <div class="download-box">
            <span class="download-label">Download</span>

            <div class="download-actions">
                <a href="{{ route('admin.reports.pdf', [
                 'type' => 'sales',
                 'start_date' => $startDate,
                 'end_date' => $endDate
                 ]) }}"
                 id="pdfExportBtn"
                 class="btn-download btn-pdf">
                PDF
               </a>
               <a href="{{ route('admin.reports.excel', [
                'type' => 'sales',
                'start_date' => $startDate,
                'end_date' => $endDate
                ]) }}"
                id="excelExportBtn"
                class="btn-download btn-excel">
               Excel
              </a>
            </div>
        </div>
    </div>
</div>

<div class="section-title">
    <h2>Business overview</h2>
    <p>General summary of sales, purchases, inventory and customers</p>
</div>

<div class="metrics">
    <div class="metric-card">
        <div class="metric-label">Total sales</div>
        <div class="metric-value">S/ {{ number_format($totalSales, 2) }}</div>
        <div class="metric-change">{{ $salesCount }} sales registered</div>
    </div>

    <div class="metric-card">
        <div class="metric-label">Total purchases</div>
        <div class="metric-value">S/ {{ number_format($totalPurchases, 2) }}</div>
        <div class="metric-change">{{ $purchaseOrdersCount }} purchase orders</div>
    </div>

    <div class="metric-card">
        <div class="metric-label">Inventory value</div>
        <div class="metric-value">S/ {{ number_format($inventoryValue, 2) }}</div>
        <div class="metric-change">{{ $totalProducts }} registered products</div>
    </div>

    <div class="metric-card">
        <div class="metric-label">Total customers</div>
        <div class="metric-value">{{ $totalCustomers }}</div>
        <div class="metric-change">{{ $activeCustomers }} active customers</div>
    </div>
</div>

<div class="report-section active" id="sales-section">

    <div class="section-title">
        <h2>Sales analysis</h2>
        <p>Sales indicators and sales share grouped by main category</p>
    </div>

    <div class="metrics">
        <div class="metric-card">
            <div class="metric-label">Sales count</div>
            <div class="metric-value">{{ $salesCount }}</div>
            <div class="metric-change">Completed sales in the selected period</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Average ticket</div>
            <div class="metric-value">S/ {{ number_format($averageTicket, 2) }}</div>
            <div class="metric-change">Average amount per sale</div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3>Sales vs Purchases</h3>
                    <p>Daily comparison for the selected period</p>
                </div>
            </div>

            <div class="chart-wrap">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3>Sales share by main category</h3>
                    <p>Percentage of sales grouped by parent category</p>
                </div>
            </div>

            <div class="chart-wrap">
                <canvas id="categoryPieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
            <div>
                <h3>Sales detail for period</h3>
                <p id="sort-status" style="font-size:12px; color:#999; margin-top:2px;">
                    Showing sales in default order (most recent first)
                </p>
            </div>

            @if($recentSales->count() > 0)
                <button type="button" id="sortByTotalBtn" class="tab-btn" style="background:#e8192c; color:#fff;">
                    Sort by total (Merge Sort)
                </button>
            @endif
        </div>

        @if($recentSales->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Time</th>
                        <th>Items</th>
                        <th>Method</th>
                        <th>Cashier</th>
                        <th style="text-align:right">Total</th>
                    </tr>
                </thead>

                <tbody id="sales-detail-body">
                    @foreach($recentSales as $sale)
                        <tr>
                            <td>
                                <span class="invoice">
                                    {{ $sale->invoice_number ?? 'B-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td>{{ $sale->created_at->format('h:i A') }}</td>
                            <td>{{ $sale->details->count() }} items</td>
                            <td>
                                <span class="method-badge">
                                    {{ ucfirst($sale->payment_method ?? 'Cash') }}
                                </span>
                            </td>
                            <td>{{ $sale->cashier?->name ?? '-' }}</td>
                            <td style="text-align:right">
                                <span class="total-amount">
                                    S/ {{ number_format($sale->total, 2) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">No sales recorded for this period</div>
        @endif
    </div>

</div>

<div class="report-section" id="purchases-section">

    <div class="section-title">
        <h2>Purchases analysis</h2>
        <p>Purchase orders, supplier spending and reception status</p>
    </div>

    <div class="metrics">
        <div class="metric-card">
            <div class="metric-label">Total purchases</div>
            <div class="metric-value">S/ {{ number_format($totalPurchases, 2) }}</div>
            <div class="metric-change">{{ $purchaseOrdersCount }} purchase orders</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Pending orders</div>
            <div class="metric-value">{{ $pendingPurchases }}</div>
            <div class="metric-change">Waiting for reception</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Received orders</div>
            <div class="metric-value">{{ $receivedPurchases }}</div>
            <div class="metric-change">{{ $partialPurchases }} partial orders</div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3>Purchases by supplier</h3>
                    <p>Total purchase amount grouped by supplier</p>
                </div>
            </div>

            <div class="chart-wrap">
                <canvas id="purchasesSupplierChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3>Purchase order status</h3>
                    <p>Distribution of pending, partial and received orders</p>
                </div>
            </div>

            <div class="chart-wrap">
                <canvas id="purchaseStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header">
            <h3>Recent purchase orders</h3>
        </div>

        @if($recentPurchases->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($recentPurchases as $purchase)
                        <tr>
                            <td>PO-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $purchase->supplier?->company_name ?? '-' }}</td>
                            <td>{{ ucfirst($purchase->status) }}</td>
                            <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                            <td style="text-align:right;">S/ {{ number_format($purchase->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">No purchase orders registered in this period.</div>
        @endif
    </div>
</div>

<div class="report-section" id="inventory-section">

    <div class="section-title">
        <h2>Inventory analysis</h2>
        <p>Stock value, product availability and low stock alerts</p>
    </div>

    <div class="metrics">
        <div class="metric-card">
            <div class="metric-label">Inventory value</div>
            <div class="metric-value">S/ {{ number_format($inventoryValue, 2) }}</div>
            <div class="metric-change">Current stock value by cost</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Total products</div>
            <div class="metric-value">{{ $totalProducts }}</div>
            <div class="metric-change">Registered products</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Low stock</div>
            <div class="metric-value">{{ $lowStockProducts }}</div>
            <div class="metric-change">{{ $outOfStockProducts }} out of stock</div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3>Stock by main category</h3>
                    <p>Available stock grouped by parent category</p>
                </div>
            </div>

            <div class="chart-wrap">
                <canvas id="stockCategoryChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3>Stock status</h3>
                    <p>Normal stock, low stock and out of stock products</p>
                </div>
            </div>

            <div class="chart-wrap">
                <canvas id="stockStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header">
            <h3>Low stock products</h3>
        </div>

        @if($lowStockList->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Current stock</th>
                        <th>Minimum stock</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($lowStockList as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->min_stock }}</td>
                            <td>
                                @if($product->stock <= 0)
                                    Out of stock
                                @else
                                    Low stock
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">No low stock products.</div>
        @endif
    </div>
</div>

<div class="report-section" id="customers-section">

    <div class="section-title">
        <h2>Customers analysis</h2>
        <p>Customer activity, loyalty stars and top customers</p>
    </div>

    <div class="metrics">
        <div class="metric-card">
            <div class="metric-label">Total customers</div>
            <div class="metric-value">{{ $totalCustomers }}</div>
            <div class="metric-change">Registered customers</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Active customers</div>
            <div class="metric-value">{{ $activeCustomers }}</div>
            <div class="metric-change">Customers with purchases</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Total stars</div>
            <div class="metric-value">{{ $totalStars }}</div>
            <div class="metric-change">{{ $newCustomers }} new customers</div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3>Top customers by stars</h3>
                    <p>Loyalty ranking based on accumulated stars</p>
                </div>
            </div>

            <div class="chart-wrap">
                <canvas id="topCustomersChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3>Customer activity</h3>
                    <p>Active customers compared with inactive customers</p>
                </div>
            </div>

            <div class="chart-wrap">
                <canvas id="customerActivityChart"></canvas>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header">
            <h3>Top loyalty customers</h3>
        </div>

        @if($topCustomers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Stars</th>
                        <th>Registered</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($topCustomers as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email ?? '-' }}</td>
                            <td>{{ $client->accumulated_stars ?? 0 }}</td>
                            <td>{{ $client->created_at?->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">No customers registered.</div>
        @endif
    </div>
</div> {{-- End customers-section --}}

</div> {{-- End content --}}
</div> {{-- End main --}}

<script id="weekly-data" type="application/json">{!! $weeklySalesJson !!}</script>
<script>
const weeklyData = JSON.parse(document.getElementById('weekly-data').textContent || '[]');

const lineCtx = document.getElementById('lineChart').getContext('2d');
new Chart(lineCtx, {
  type: 'line',
  data: {
    labels: weeklyData.map(d => d.day),
    datasets: [
      {
        label: 'Sales',
        data: weeklyData.map(d => d.sales),
        borderColor: '#e8192c',
        backgroundColor: 'rgba(232,25,44,0.08)',
        borderWidth: 2.5, fill: true, tension: 0.4, pointRadius: 4,
        pointBackgroundColor: '#e8192c',
      },
      {
        label: 'Purchases',
        data: weeklyData.map(d => d.purchases),
        borderColor: '#111',
        backgroundColor: 'rgba(0,0,0,0.03)',
        borderWidth: 2, fill: true, tension: 0.4, pointRadius: 4,
        pointBackgroundColor: '#111',
      }
    ]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
    scales: {
      x: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 }, color: '#aaa' } },
      y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 }, color: '#aaa' } }
    }
  }
});

</script>

<script>
const tabButtons = document.querySelectorAll('.tab-btn[data-tab]');
const reportSections = document.querySelectorAll('.report-section');

let currentReportType = 'sales';

function updateExportLinks() {
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');

    const startDate = startDateInput ? startDateInput.value : '';
    const endDate = endDateInput ? endDateInput.value : '';

    const pdfButton = document.getElementById('pdfExportBtn');
    const excelButton = document.getElementById('excelExportBtn');

    const pdfBaseUrl = "{{ route('admin.reports.pdf') }}";
    const excelBaseUrl = "{{ route('admin.reports.excel') }}";

    const params = new URLSearchParams({
        type: currentReportType,
        start_date: startDate,
        end_date: endDate
    });

    if (pdfButton) {
        pdfButton.href = `${pdfBaseUrl}?${params.toString()}`;
    }

    if (excelButton) {
        excelButton.href = `${excelBaseUrl}?${params.toString()}`;
    }
}

tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        const selectedTab = button.dataset.tab;

        currentReportType = selectedTab;

        tabButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        reportSections.forEach(section => {
            section.classList.remove('active');
        });

        const selectedSection = document.getElementById(`${selectedTab}-section`);

        if (selectedSection) {
            selectedSection.classList.add('active');
        }

        updateExportLinks();

        setTimeout(() => {
            Object.values(window.reportCharts || {}).forEach(chart => {
                if (chart) {
                    chart.resize();
                }
            });
        }, 100);
    });
});

document.querySelectorAll('input[name="start_date"], input[name="end_date"]').forEach(input => {
    input.addEventListener('change', updateExportLinks);
});

updateExportLinks();
</script>

@php
    $reportData = [
        'salesByCategoryLabels' => json_decode($salesByCategoryLabelsJson ?? '[]', true) ?: [],
        'salesByCategoryAmounts' => json_decode($salesByCategoryAmountsJson ?? '[]', true) ?: [],

        'purchasesBySupplier' => json_decode($purchasesBySupplierJson ?? '[]', true) ?: [],
        'stockByCategory' => json_decode($stockByCategoryJson ?? '[]', true) ?: [],
        'topCustomers' => json_decode($topCustomersJson ?? '[]', true) ?: [],

        'pendingPurchases' => $pendingPurchases ?? 0,
        'partialPurchases' => $partialPurchases ?? 0,
        'receivedPurchases' => $receivedPurchases ?? 0,

        'normalStockProducts' => $normalStockProducts ?? 0,
        'lowStockProducts' => max(($lowStockProducts ?? 0) - ($outOfStockProducts ?? 0), 0),
        'outOfStockProducts' => $outOfStockProducts ?? 0,

        'activeCustomers' => $activeCustomers ?? 0,
        'inactiveCustomers' => $inactiveCustomers ?? 0,
    ];

    $encodedReportData = base64_encode(json_encode($reportData, JSON_UNESCAPED_UNICODE));
@endphp
<div id="report-data" data-json="{{ $encodedReportData }}"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const reportDataElement = document.getElementById('report-data');

    let reportData = {
        salesByCategoryLabels: [],
        salesByCategoryAmounts: [],
        purchasesBySupplier: [],
        stockByCategory: [],
        topCustomers: [],
        pendingPurchases: 0,
        partialPurchases: 0,
        receivedPurchases: 0,
        normalStockProducts: 0,
        lowStockProducts: 0,
        outOfStockProducts: 0,
        activeCustomers: 0,
        inactiveCustomers: 0
    };

    if (reportDataElement) {
        const encodedData = reportDataElement.getAttribute('data-json');

        if (encodedData) {
            reportData = JSON.parse(atob(encodedData));
        }
    }

    window.reportCharts = {};

    function createChart(canvasId, config) {
        const canvas = document.getElementById(canvasId);

        if (!canvas) {
            return null;
        }

        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded.');
            return null;
        }

        return new Chart(canvas, config);
    }

    function moneyTooltip(context) {
        const value = Number(context.raw || 0);
        return `${context.label}: S/ ${value.toFixed(2)}`;
    }

    function simpleMoneyTooltip(context) {
        const value = Number(context.raw || 0);
        return `S/ ${value.toFixed(2)}`;
    }

    window.reportCharts.categoryPieChart = createChart('categoryPieChart', {
        type: 'doughnut',
        data: {
            labels: reportData.salesByCategoryLabels,
            datasets: [{
                data: reportData.salesByCategoryAmounts,
                backgroundColor: [
                    '#e8192c',
                    '#111111',
                    '#f59e0b',
                    '#22c55e',
                    '#3b82f6',
                    '#8b5cf6',
                    '#64748b'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 11 },
                        boxWidth: 12,
                        boxHeight: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: moneyTooltip
                    }
                }
            }
        }
    });

    window.reportCharts.purchasesSupplierChart = createChart('purchasesSupplierChart', {
        type: 'bar',
        data: {
            labels: reportData.purchasesBySupplier.map(item => item.name),
            datasets: [{
                data: reportData.purchasesBySupplier.map(item => item.total),
                backgroundColor: '#e8192c',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: simpleMoneyTooltip
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    window.reportCharts.purchaseStatusChart = createChart('purchaseStatusChart', {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Partial', 'Received'],
            datasets: [{
                data: [
                    reportData.pendingPurchases,
                    reportData.partialPurchases,
                    reportData.receivedPurchases
                ],
                backgroundColor: [
                    '#f59e0b',
                    '#3b82f6',
                    '#22c55e'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    window.reportCharts.stockCategoryChart = createChart('stockCategoryChart', {
        type: 'bar',
        data: {
            labels: reportData.stockByCategory.map(item => item.name),
            datasets: [{
                data: reportData.stockByCategory.map(item => item.stock),
                backgroundColor: '#111111',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    window.reportCharts.stockStatusChart = createChart('stockStatusChart', {
        type: 'doughnut',
        data: {
            labels: ['Normal stock', 'Low stock', 'Out of stock'],
            datasets: [{
                data: [
                    reportData.normalStockProducts,
                    reportData.lowStockProducts,
                    reportData.outOfStockProducts
                ],
                backgroundColor: [
                    '#22c55e',
                    '#f59e0b',
                    '#e8192c'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    window.reportCharts.topCustomersChart = createChart('topCustomersChart', {
        type: 'bar',
        data: {
            labels: reportData.topCustomers.map(item => item.name),
            datasets: [{
                data: reportData.topCustomers.map(item => item.stars),
                backgroundColor: '#e8192c',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    window.reportCharts.customerActivityChart = createChart('customerActivityChart', {
        type: 'doughnut',
        data: {
            labels: ['Active customers', 'Inactive customers'],
            datasets: [{
                data: [
                    reportData.activeCustomers,
                    reportData.inactiveCustomers
                ],
                backgroundColor: [
                    '#22c55e',
                    '#e5e7eb'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    });
</script>

<script id="sales-detail-data" type="application/json">{!! $salesDetailJson !!}</script>
<script>
  const originalSalesDetail = JSON.parse(document.getElementById('sales-detail-data').textContent || '[]');
  let mergeComparisons = 0;
  let mergeOperations = 0;

  function mergeSort(items) {
    if (items.length <= 1) return items;

    const middle = Math.floor(items.length / 2);
    const left = mergeSort(items.slice(0, middle));
    const right = mergeSort(items.slice(middle));

    return merge(left, right);
  }

  function merge(left, right) {
    const result = [];
    let i = 0, j = 0;

    while (i < left.length && j < right.length) {
      mergeComparisons++;
      if (left[i].total >= right[j].total) {
        result.push(left[i]);
        i++;
      } else {
        result.push(right[j]);
        j++;
      }
      mergeOperations++;
    }

    while (i < left.length) { result.push(left[i]); i++; mergeOperations++; }
    while (j < right.length) { result.push(right[j]); j++; mergeOperations++; }

    return result;
  }

  function renderSalesDetail(sales) {
    const tbody = document.getElementById('sales-detail-body');
    tbody.innerHTML = sales.map(s => `
      <tr>
        <td><span class="invoice">${s.invoice}</span></td>
        <td>${s.time}</td>
        <td>${s.items}</td>
        <td><span class="method-badge">${s.method}</span></td>
        <td>${s.cashier}</td>
        <td style="text-align:right"><span class="total-amount">S/ ${s.total.toFixed(2)}</span></td>
      </tr>
    `).join('');
  }

  const sortBtn = document.getElementById('sortByTotalBtn');
  if (sortBtn) {
    let isSorted = false;

    sortBtn.addEventListener('click', () => {
      const status = document.getElementById('sort-status');

      if (!isSorted) {
        mergeComparisons = 0;
        mergeOperations = 0;
        const sorted = mergeSort(originalSalesDetail);
        renderSalesDetail(sorted);
        status.textContent = `Sorted by total (highest first) — Merge Sort: ${mergeComparisons} comparisons, ${mergeOperations} merge operations over ${originalSalesDetail.length} sales`;
        sortBtn.textContent = 'Reset to default order';
        isSorted = true;
      } else {
        renderSalesDetail(originalSalesDetail);
        status.textContent = 'Showing sales in default order (most recent first)';
        sortBtn.textContent = 'Sort by total (Merge Sort)';
        isSorted = false;
      }
    });
  }
</script>

</x-portal-layout>