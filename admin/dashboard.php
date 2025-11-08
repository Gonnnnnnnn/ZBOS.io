<?php
session_start();

// Protect admin pages
if (empty($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}

$orders_file = __DIR__ . '/../orders.json';
$orders = [];
if (file_exists($orders_file)) {
    $orders = json_decode(file_get_contents($orders_file), true) ?: [];
}

// Audit log path
$audit_file = __DIR__ . '/admin_audit.log';

// Precompute status counts for dashboard cards
$status_counts = ['pending' => 0, 'processing' => 0, 'completed' => 0, 'cancelled' => 0];
foreach ($orders as $o) {
    $st = isset($o['status']) ? $o['status'] : 'pending';
    if (!isset($status_counts[$st])) $status_counts[$st] = 0;
    $status_counts[$st]++;
}
 $total_orders = count($orders);

// Export action removed — CSV export disabled

// Handle status update (with CSRF validation)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], $csrf)) {
        // Bad CSRF token
        http_response_code(400);
        echo "Invalid CSRF token.";
        exit;
    }

    $order_number = isset($_POST['order_number']) ? (string)$_POST['order_number'] : '';
    $new_status = isset($_POST['status']) ? (string)$_POST['status'] : '';

    foreach ($orders as &$o) {
        if (isset($o['order_number']) && $o['order_number'] === $order_number) {
            $o['status'] = $new_status;
            $o['updated_at'] = date('Y-m-d H:i:s');
            break;
        }
    }
    unset($o);

    // Save back and log action
    file_put_contents($orders_file, json_encode($orders, JSON_PRETTY_PRINT));
    $entry = date('Y-m-d H:i:s') . " | update_status | " . ($order_number) . " | new_status=" . ($new_status) . "\n";
    file_put_contents($audit_file, $entry, FILE_APPEND | LOCK_EX);
    // Reload to show changes with message
    header('Location: dashboard.php?msg=' . urlencode('Status updated'));
    exit;
}

// Handle delete order (with CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_order') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], $csrf)) {
        http_response_code(400);
        echo "Invalid CSRF token.";
        exit;
    }

    $order_number = isset($_POST['order_number']) ? (string)$_POST['order_number'] : '';
    $new_orders = [];
    foreach ($orders as $o) {
        if (!isset($o['order_number']) || $o['order_number'] !== $order_number) {
            $new_orders[] = $o;
        }
    }
    // Save back and log
    file_put_contents($orders_file, json_encode($new_orders, JSON_PRETTY_PRINT));
    $entry = date('Y-m-d H:i:s') . " | delete_order | " . ($order_number) . "\n";
    file_put_contents($audit_file, $entry, FILE_APPEND | LOCK_EX);
    header('Location: dashboard.php?msg=' . urlencode('Order deleted'));
    exit;
}

// Handle bulk update (with CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'bulk_update') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], $csrf)) {
        http_response_code(400);
        echo "Invalid CSRF token.";
        exit;
    }
    $order_numbers = $_POST['order_numbers'] ?? [];
    $new_status = $_POST['status'] ?? '';
    if (!is_array($order_numbers) || !$new_status) {
        header('Location: dashboard.php?msg=' . urlencode('No orders selected or invalid status'));
        exit;
    }
    foreach ($orders as &$o) {
        if (in_array($o['order_number'] ?? '', $order_numbers, true)) {
            $o['status'] = $new_status;
            $o['updated_at'] = date('Y-m-d H:i:s');
        }
    }
    unset($o);
    file_put_contents($orders_file, json_encode($orders, JSON_PRETTY_PRINT));
    $entry = date('Y-m-d H:i:s') . " | bulk_update | orders=" . implode(',', $order_numbers) . " | new_status=" . $new_status . "\n";
    file_put_contents($audit_file, $entry, FILE_APPEND | LOCK_EX);
    header('Location: dashboard.php?msg=' . urlencode('Bulk update applied'));
    exit;
}

// Handle admin logout (optional)
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Clear only admin flag (keep other session data if desired)
    unset($_SESSION['is_admin']);
    header('Location: login.php');
    exit;
}

// Helper to pretty-print items
function format_items($items) {
    $out = '';
    if (!is_array($items)) return '';
    foreach ($items as $it) {
        $name = isset($it['name']) ? $it['name'] : 'Item';
        $qty = isset($it['quantity']) ? $it['quantity'] : 1;
        $out .= htmlspecialchars($name) . ' x' . intval($qty) . '<br>';
    }
    return $out;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Palette matching index.php (warm browns/oranges) */
        body { padding:20px; background: linear-gradient(180deg,#fff,#f7f6f5); }
        .table-wrap { overflow:auto; }

        .page-header {
            background: linear-gradient(135deg,#8B4513,#D2691E);
            color: #fff; padding:16px; border-radius:12px; margin-bottom:18px;
        }
        .page-header h3 { margin:0; font-weight:700; }

        .card.p-3 { border-radius:12px; box-shadow:0 8px 24px rgba(139,69,19,0.08); }

        .btn-primary { background: linear-gradient(45deg,#8B4513,#D2691E); border:none; }
        .btn-primary:hover { transform: translateY(-2px); }

        .btn-outline-primary { border-color:#8B4513; color:#8B4513; }

        .fs-3 { color:#4b2f1a; }

        .status-badge { padding:6px 10px; border-radius:999px; color:#fff; font-weight:600; }
        .status-pending { background:#f0ad4e; }
        .status-processing { background:#d2691e; }
        .status-completed { background:#28a745; }
        .status-cancelled { background:#6c757d; }
    </style>
</head>
<body>
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(45deg,#8B4513,#D2691E);display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;box-shadow:0 8px 18px rgba(0,0,0,0.12);">
                <i class="fas fa-coffee"></i>
            </div>
            <div>
                <h3 style="margin:0;">ZapBrew Admin</h3>
                <small style="opacity:0.9;color:rgba(255,255,255,0.9);">Order management & reports</small>
            </div>
        </div>
        <div>
            <a class="btn btn-light text-dark me-2" href="?action=logout">Logout</a>
            <a class="btn btn-light text-dark me-2" href="../index.php">View site</a>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Stats cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Total Orders</h5>
                        <div class="fs-3 fw-bold"><?php echo $total_orders; ?></div>
                    </div>
                    <div class="text-muted"><i class="fas fa-list fs-1"></i></div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card p-3">
                <h6 class="mb-1">Pending</h6>
                <div class="fs-4 fw-semibold"><?php echo $status_counts['pending'] ?? 0; ?></div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card p-3">
                <h6 class="mb-1">Processing</h6>
                <div class="fs-4 fw-semibold"><?php echo $status_counts['processing'] ?? 0; ?></div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card p-3">
                <h6 class="mb-1">Completed</h6>
                <div class="fs-4 fw-semibold"><?php echo $status_counts['completed'] ?? 0; ?></div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
        <div class="d-flex gap-2 w-100">
            <input id="searchInput" class="form-control" placeholder="Search by order number, customer, contact or address">
            <select id="statusFilter" class="form-select" style="max-width:200px;">
                <option value="">All statuses</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <div class="ms-auto d-flex gap-2">
                <select id="bulkStatus" class="form-select form-select-sm" style="max-width:180px;">
                    <option value="">Bulk action</option>
                    <option value="pending">Mark Pending</option>
                    <option value="processing">Mark Processing</option>
                    <option value="completed">Mark Completed</option>
                    <option value="cancelled">Mark Cancelled</option>
                </select>
                <button id="bulkApply" class="btn btn-sm btn-warning">Apply to selected</button>
            </div>
        </div>
    </div>

    <div class="table-wrap">
        <table id="ordersTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width:36px"><input id="selectAll" type="checkbox"></th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Payment</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="10" class="text-center text-muted">No orders found</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><input class="select-order" type="checkbox" value="<?php echo htmlspecialchars($o['order_number'] ?? ''); ?>"></td>
                            <td><?php echo htmlspecialchars($o['order_number'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($o['customer_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($o['contact_number'] ?? ''); ?></td>
                            <td style="max-width:200px; word-wrap:break-word"><?php echo htmlspecialchars($o['delivery_address'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($o['payment_method'] ?? ''); ?></td>
                            <td><?php echo format_items($o['items'] ?? []); ?></td>
                            <td>₱<?php echo number_format($o['total'] ?? 0, 2); ?></td>
                            <td><?php echo htmlspecialchars($o['order_date'] ?? ''); ?></td>
                            <td class="order-status">
                                <?php $st = $o['status'] ?? 'pending';
                                    $cls = 'status-pending';
                                    if ($st === 'processing') $cls = 'status-processing';
                                    if ($st === 'completed') $cls = 'status-completed';
                                    if ($st === 'cancelled') $cls = 'status-cancelled';
                                ?>
                                <span class="status-badge <?php echo $cls; ?>"><?php echo ucfirst(htmlspecialchars($st)); ?></span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form method="post" class="d-flex gap-2 update-form">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="order_number" value="<?php echo htmlspecialchars($o['order_number'] ?? ''); ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['admin_csrf'] ?? ''); ?>">
                                        <select name="status" class="form-select form-select-sm">
                                        <?php
                                        $states = ['pending','processing','completed','cancelled'];
                                        $cur = $o['status'] ?? 'pending';
                                        foreach ($states as $s) {
                                            $sel = $s === $cur ? 'selected' : '';
                                            echo "<option value=\"" . htmlspecialchars($s) . "\" $sel>" . ucfirst($s) . "</option>\n";
                                        }
                                        ?>
                                        </select>
                                        <button class="btn btn-sm btn-primary">Update</button>
                                    </form>

                                    <button class="btn btn-sm btn-outline-secondary view-btn" 
                                        data-order='<?php echo json_encode($o, JSON_HEX_APOS|JSON_HEX_QUOT); ?>'>View</button>

                                    <form method="post" class="delete-form" onsubmit="return confirm('Delete order <?php echo htmlspecialchars($o['order_number'] ?? ''); ?>?');">
                                        <input type="hidden" name="action" value="delete_order">
                                        <input type="hidden" name="order_number" value="<?php echo htmlspecialchars($o['order_number'] ?? ''); ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['admin_csrf'] ?? ''); ?>">
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function(){
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const table = document.getElementById('ordersTable');
            const tbody = table.querySelector('tbody');
            const selectAll = document.getElementById('selectAll');
            const bulkApply = document.getElementById('bulkApply');
            const bulkStatus = document.getElementById('bulkStatus');
            const adminCsrf = '<?php echo htmlspecialchars($_SESSION['admin_csrf'] ?? ''); ?>';

            function normalize(s){ return (s||'').toString().toLowerCase(); }

            function applyFilters(){
                const q = normalize(searchInput.value.trim());
                const status = statusFilter.value;
                Array.from(tbody.rows).forEach(row => {
                    // gather searchable text
                    const cells = row.cells;
                    if (!cells || cells.length < 1) return;
                    const orderNo = normalize(cells[0].innerText);
                    const customer = normalize(cells[1].innerText);
                    const contact = normalize(cells[2].innerText);
                    const address = normalize(cells[3].innerText);
                    const stat = normalize(cells[8].innerText);

                    let matchesQuery = true;
                    if (q) {
                        matchesQuery = orderNo.includes(q) || customer.includes(q) || contact.includes(q) || address.includes(q);
                    }
                    let matchesStatus = true;
                    if (status) matchesStatus = stat === status;

                    row.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
                });
            }

            if (searchInput) searchInput.addEventListener('input', applyFilters);
            if (statusFilter) statusFilter.addEventListener('change', applyFilters);

            // View modal
            const viewButtons = document.querySelectorAll('.view-btn');
            const orderModalEl = document.getElementById('orderModal');
            const orderDetails = document.getElementById('orderDetails');
            let bsModal = orderModalEl ? new bootstrap.Modal(orderModalEl) : null;

            viewButtons.forEach(btn => {
                btn.addEventListener('click', function(){
                    const raw = this.getAttribute('data-order');
                    try {
                        const data = JSON.parse(raw);
                        let html = '<dl class="row">';
                        html += '<dt class="col-sm-3">Order #</dt><dd class="col-sm-9">' + (data.order_number||'') + '</dd>';
                        html += '<dt class="col-sm-3">Customer</dt><dd class="col-sm-9">' + (data.customer_name||'') + '</dd>';
                        html += '<dt class="col-sm-3">Contact</dt><dd class="col-sm-9">' + (data.contact_number||'') + '</dd>';
                        html += '<dt class="col-sm-3">Address</dt><dd class="col-sm-9">' + (data.delivery_address||'') + '</dd>';
                        html += '<dt class="col-sm-3">Payment</dt><dd class="col-sm-9">' + (data.payment_method||'') + '</dd>';
                        html += '<dt class="col-sm-3">Total</dt><dd class="col-sm-9">₱' + (parseFloat(data.total)||0).toFixed(2) + '</dd>';
                        html += '<dt class="col-sm-3">Order Date</dt><dd class="col-sm-9">' + (data.order_date||'') + '</dd>';
                        html += '<dt class="col-sm-3">Status</dt><dd class="col-sm-9">' + (data.status||'') + '</dd>';
                        html += '<dt class="col-sm-3">Items</dt><dd class="col-sm-9">';
                        if (Array.isArray(data.items)){
                            html += '<ul>' + data.items.map(i => '<li>' + (i.name||'') + ' x' + (i.quantity||1) + (i.size?(' ('+i.size+')'):'') + '</li>').join('') + '</ul>';
                        }
                        html += '</dd>';
                        if (data.updated_at) html += '<dt class="col-sm-3">Updated</dt><dd class="col-sm-9">' + data.updated_at + '</dd>';
                        html += '</dl>';
                        orderDetails.innerHTML = html;
                        if (bsModal) bsModal.show();
                    } catch(e){
                        orderDetails.textContent = 'Unable to parse order details.';
                        if (bsModal) bsModal.show();
                    }
                });
            });

            // Select all toggle
            if (selectAll) {
                selectAll.addEventListener('change', function(){
                    const checked = !!this.checked;
                    document.querySelectorAll('.select-order').forEach(cb => cb.checked = checked);
                });
            }

            // Bulk apply
            if (bulkApply) {
                bulkApply.addEventListener('click', function(){
                    const status = bulkStatus.value;
                    if (!status) { alert('Select a bulk action first'); return; }
                    const checked = Array.from(document.querySelectorAll('.select-order:checked')).map(c=>c.value);
                    if (!checked.length) { alert('Select at least one order'); return; }

                    // Create and submit a form
                    const f = document.createElement('form');
                    f.method = 'POST';
                    f.action = '';
                    // action
                    const a = document.createElement('input'); a.type='hidden'; a.name='action'; a.value='bulk_update'; f.appendChild(a);
                    // csrf
                    const c = document.createElement('input'); c.type='hidden'; c.name='csrf_token'; c.value=adminCsrf; f.appendChild(c);
                    // status
                    const s = document.createElement('input'); s.type='hidden'; s.name='status'; s.value=status; f.appendChild(s);
                    // selected orders
                    checked.forEach(v=>{
                        const i = document.createElement('input'); i.type='hidden'; i.name='order_numbers[]'; i.value = v; f.appendChild(i);
                    });
                    document.body.appendChild(f);
                    f.submit();
                });
            }

            // initial filter
            applyFilters();
        })();
    </script>

</body>
</html>
