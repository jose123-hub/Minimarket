@push('portal-styles')
<style>
  .metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
  .metric-card { background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #eee; display: flex; justify-content: space-between; align-items: flex-start; }
  .metric-label { font-size: 13px; color: #999; margin-bottom: 8px; }
  .metric-value { font-size: 26px; font-weight: 800; color: #111; margin-bottom: 6px; }
  .metric-change { font-size: 12px; color: #22c55e; }
  .metric-icon { width: 40px; height: 40px; background: #fff0f2; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
  .metric-icon svg { width: 20px; height: 20px; stroke: #e8192c; fill: none; stroke-width: 1.8; }

  .new-sale-btn { display: flex; align-items: center; justify-content: center; gap: 12px; background: #e8192c; color: #fff; border-radius: 12px; padding: 22px; font-size: 18px; font-weight: 700; text-decoration: none; margin-bottom: 20px; transition: background 0.2s; }
  .new-sale-btn:hover { background: #c41525; color: #fff; }
  .new-sale-btn svg { width: 24px; height: 24px; stroke: #fff; fill: none; stroke-width: 2; }

  .bottom-row { display: grid; grid-template-columns: 1fr 340px; gap: 16px; }
  .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
  .table-header h3 { font-size: 15px; font-weight: 700; color: #111; }
  .ver-todo { font-size: 13px; color: #e8192c; text-decoration: none; font-weight: 500; }

  .quick-card { background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee; }
  .quick-card h3 { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 16px; }
  .quick-link { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 8px; border: 1px solid #eee; text-decoration: none; color: #333; font-size: 14px; font-weight: 500; margin-bottom: 10px; transition: all 0.15s; }
  .quick-link:hover { border-color: #e8192c; color: #e8192c; }
  .quick-link svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .queue-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f5f5f5; }
  .queue-item:last-child { border-bottom: none; }
  .queue-customer { font-size: 13px; font-weight: 600; color: #111; }
  .queue-meta { font-size: 12px; color: #999; margin-top: 2px; }
</style>
@endpush

<x-portal-layout
    title="Dashboard"
    subtitle="Your activity summary for today"
    active="dashboard"
>

    <div class="metrics">
      <div class="metric-card">
        <div>
          <div class="metric-label">Sales today</div>
          <div class="metric-value">S/ {{ number_format($totalToday, 2) }}</div>
          <div class="metric-change">Your sales today</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Transactions</div>
          <div class="metric-value">{{ $transactionsToday }}</div>
          <div class="metric-change">Sales registered</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg></div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Average ticket</div>
          <div class="metric-value">S/ {{ $transactionsToday > 0 ? number_format($totalToday / $transactionsToday, 2) : '0.00' }}</div>
          <div class="metric-change">Per transaction</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
      </div>
    </div>

    <a href="{{ route('sales.create') }}" class="new-sale-btn">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      New Sale
    </a>

    <div class="table-card" style="margin-bottom:20px;">
      <div class="table-header">
        <h3>Pending Orders Queue</h3>
        <span style="font-size:12px; color:#999;">Sorted by priority</span>
      </div>
      <div id="order-queue">
        <div class="empty-row">No pending orders</div>
      </div>
    </div>

    <div class="bottom-row">
      <div class="table-card">
        <div class="table-header">
          <h3>My latest sales</h3>
          <a href="#" class="ver-todo">View all</a>
        </div>
        @if($recentSales->count() > 0)
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Date</th>
              <th style="text-align:right">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentSales as $sale)
            <tr>
              <td>#{{ $sale->id }}</td>
              <td>{{ $sale->customer->name ?? 'N/A' }}</td>
              <td>{{ $sale->created_at->format('h:i A') }}</td>
              <td style="text-align:right">S/ {{ number_format($sale->total, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="empty-row">No sales registered today</div>
        @endif
      </div>

      <div class="quick-card">
        <h3>Quick access</h3>
        <a href="{{ route('sales.create') }}" class="quick-link">
          <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
          Register new sale
        </a>
        <a href="{{ route('cashier.inventory') }}" class="quick-link">
          <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
          View products
        </a>
        <a href="{{ route('cashier.loyalty') }}" class="quick-link">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          Loyalty program
        </a>
      </div>
    </div>

    <script>
    class PriorityQueue {
      constructor() {
        this.items = {};
        this.size = 0;
      }

      getPriority(total) {
        if (total >= 30) return 3;
        if (total >= 10) return 2;
        return 1;
      }

      enqueue(order) {
        const priority = this.getPriority(order.total);
        order.priority = priority;

        if (!this.items[priority]) {
          this.items[priority] = [];
        }

        let i = this.size;
        this.items[priority][this.items[priority].length] = order;
        this.size++;
      }

      dequeue() {
        if (this.isEmpty()) return null;

        for (let p = 3; p >= 1; p--) {
          if (this.items[p] && this.items[p].length > 0) {
            const order = this.items[p][0];
            for (let i = 0; i < this.items[p].length - 1; i++) {
              this.items[p][i] = this.items[p][i + 1];
            }
            this.items[p].length--;
            this.size--;
            return order;
          }
        }
        return null;
      }

      peek() {
        for (let p = 3; p >= 1; p--) {
          if (this.items[p] && this.items[p].length > 0) {
            return this.items[p][0];
          }
        }
        return null;
      }

      isEmpty() {
        return this.size === 0;
      }

      toArray() {
        const result = {};
        let i = 0;
        for (let p = 3; p >= 1; p--) {
          if (this.items[p]) {
            for (let j = 0; j < this.items[p].length; j++) {
              result[i] = this.items[p][j];
              i++;
            }
          }
        }
        return Object.values(result);
      }
    }

    const orderQueue = new PriorityQueue();
    </script>
    <script id="pending-orders-data" type="application/json">{!! json_encode($pendingOrders ?? []) !!}</script>
    <script>
    const pendingOrders = JSON.parse(document.getElementById('pending-orders-data').textContent || '[]');

    pendingOrders.forEach(order => {
      orderQueue.enqueue({
        id: order.id,
        customer: order.customer_name,
        total: parseFloat(order.total),
        items: order.items_count,
        time: order.time,
      });
    });

    function renderQueue() {
      const container = document.getElementById('order-queue');
      if (!container) return;

      const orders = orderQueue.toArray();
      container.innerHTML = '';

      if (orders.length === 0) {
        container.innerHTML = '<div class="empty-row">No pending orders</div>';
        return;
      }

      const priorityLabels = { 3: 'High', 2: 'Medium', 1: 'Low' };
      const priorityColors = { 3: '#e8192c', 2: '#f59e0b', 1: '#22c55e' };

      orders.forEach(order => {
        const div = document.createElement('div');
        div.className = 'queue-item';
        div.innerHTML = `
          <div class="queue-item-info">
            <div class="queue-customer">${order.customer}</div>
            <div class="queue-meta">${order.items} items · ${order.time}</div>
          </div>
          <div style="display:flex; align-items:center; gap:10px;">
            <span style="font-size:11px; font-weight:700; color:${priorityColors[order.priority]}">
              ${priorityLabels[order.priority]}
            </span>
            <span style="font-weight:700; color:#111">S/ ${order.total.toFixed(2)}</span>
            <button onclick="attendOrder(${order.id})" style="padding:6px 12px; background:#e8192c; color:#fff; border:none; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer;">
              Attend
            </button>
          </div>
        `;
        container.appendChild(div);
      });
    }

    function attendOrder(id) {
     window.location.href = `/cashier/online-orders/${id}`;
    }

    renderQueue();
    </script>

</x-portal-layout>