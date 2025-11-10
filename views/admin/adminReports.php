<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | Gymazing</title>
    <script src= "../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d3748 50%, #1a1a1a 100%);
        }

        * {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #1e3a8a;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1e40af;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.1) 0%, rgba(30, 58, 138, 0.2) 100%);
            border: 1px solid rgba(29, 78, 216, 0.3);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(29, 78, 216, 0.2);
            border-color: #1e40af;
        }

        .table-row {
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background-color: rgba(29, 78, 216, 0.1);
        }

        .modal-backdrop {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            transform: scale(0.8);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal-backdrop.show .modal-content {
            transform: scale(1);
            opacity: 1;
        }

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button.active {
            border-bottom: 2px solid #3b82f6;
            color: #3b82f6;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .badge {
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background-color: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .status-inactive {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .status-pending {
            background-color: rgba(251, 146, 60, 0.2);
            color: #fb923c;
        }

        .plan-card {
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.1), rgba(30, 58, 138, 0.2));
            border: 1px solid rgba(29, 78, 216, 0.3);
            transition: all 0.3s ease;
        }

        .plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(29, 78, 216, 0.2);
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .alert {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        /* Print Styles */
        /* Print Styles - FIXED VERSION */
    @media print {
        /* Hide elements that shouldn't be printed */
        .no-print,
        button,
        select,
        input,
        nav,
        .modal-backdrop,
        #alertContainer {
            display: none !important;
        }
        
        /* Reset body background */
        body {
            background: darkblue !important;
            color: black !important;
        }
        
        /* Fix all card backgrounds */
        .stat-card, 
        .bg-gray-800, 
        .bg-gray-900,
        .bg-neutral-900,
        /* .gradient-bg {
            background: white !important;
            border: 1px solid #ccc !important;
            color: black !important;
        } */
        
        /* Fix all text colors */
        .text-white,
        .text-gray-400,
        .text-gray-300,
        .text-gray-500,
        h1, h2, h3, h4, h5, h6,
        p, span, div, td, th {
            color: black !important;
        }
        
        /* Keep colored text visible */
        .text-green-400,
        .text-green-500 {
            color: #22c55e !important;
        }
        
        .text-red-400,
        .text-red-500 {
            color: #ef4444 !important;
        }
        
        .text-blue-400,
        .text-blue-500 {
            color: #3b82f6 !important;
        }
        
        .text-orange-400,
        .text-orange-500 {
            color: #fb923c !important;
        }
        
        .text-purple-400,
        .text-purple-500 {
            color: #a855f7 !important;
        }
        
        .text-yellow-400,
        .text-yellow-500 {
            color: #eab308 !important;
        }
        
        .text-cyan-400,
        .text-cyan-500 {
            color: #06b6d4 !important;
        }
        
        /* Fix tables */
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        
        thead {
            background: #f3f4f6 !important;
            border-bottom: 2px solid #000 !important;
        }
        
        th, td {
            border: 1px solid #ccc !important;
            padding: 8px !important;
            color: black !important;
        }
        
        /* Fix borders */
        .border-gray-700,
        .border-gray-600 {
            border-color: #ccc !important;
        }
        
        /* Ensure charts are visible */
        canvas {
            max-width: 100% !important;
            height: auto !important;
            page-break-inside: avoid !important;
        }
        
        /* Fix grid layouts for print */
        .grid {
            display: block !important;
        }
        
        .grid > div {
            display: block !important;
            width: 100% !important;
            margin-bottom: 20px !important;
            page-break-inside: avoid !important;
        }
        
        /* Page break control */
        .stat-card,
        .bg-gray-800 {
            page-break-inside: avoid !important;
            margin-bottom: 15px !important;
        }
        
        /* Add print header */
        @page {
            margin: 1cm;
        }
        
        /* Fix rounded corners for print */
        .rounded-xl,
        .rounded-lg {
            border-radius: 8px !important;
        }
        
        /* Status badges */
        .status-badge {
            border: 1px solid !important;
            padding: 4px 12px !important;
        }
        
        .status-active {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border-color: #22c55e !important;
        }
        
        .status-inactive {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border-color: #ef4444 !important;
        }
        
        .status-pending {
            background-color: #fed7aa !important;
            color: #92400e !important;
            border-color: #fb923c !important;
        }
    }

    /* Print-specific classes you can add */
    .print-header {
        display: none;
    }

    @media print {
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        
        .print-header h1 {
            font-size: 24px;
            margin: 0;
            color: black !important;
        }
        
        .print-header p {
            font-size: 12px;
            margin: 5px 0;
            color: #666 !important;
        }
    }
    </style>
</head>
<body class="gradient-bg min-h-screen">
     <!-- Admin Navbar -->
    <?php include __DIR__ . "/layouts/adminnavbar.php" ?> 
    <!--  Alerts Container -->
    <div id="alertContainer" class="fixed top-24 right-4 z-40 space-y-4"></div>

    <main class="pb-12 mt-20">
        <div class="p-6">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-white">Reports & Analytics</h2>
                    <div class="flex space-x-2 no-print">
                        <select id="reportPeriod" class="px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="7">Last 7 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                            <option value="90">Last 90 Days</option>
                            <option value="365">Last Year</option>
                        </select>
                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                Export PDF
                        </button>
                    </div>
                </div>

                <!-- Enhanced KPI Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Revenue -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-4xl">💰</div>
                            <span class="text-green-400 text-sm font-semibold">+<?= number_format((($paymentStats['total_paid'] ?? 0) / max(($totalEarned['total_earned'] ?? 1), 1) * 100), 1) ?>%</span>
                        </div>
                        <p class="text-gray-400 text-sm font-medium mb-1">Total Revenue</p>
                        <p class="text-3xl font-bold text-white">₱<?= number_format($paymentStats['total_paid'] ?? 0, 2) ?></p>
                        <p class="text-gray-500 text-xs mt-2"><?= $paymentStats['paid_count'] ?? 0 ?> successful transactions</p>
                    </div>

                    <!-- Pending Payments -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-4xl">⏳</div>
                            <span class="text-orange-400 text-sm font-semibold"><?= $pendingPayments['pending_count'] ?> pending</span>
                        </div>
                        <p class="text-gray-400 text-sm font-medium mb-1">Pending Revenue</p>
                        <p class="text-3xl font-bold text-white">₱<?= number_format($pendingPayments['pending_amount'] ?? 0, 2) ?></p>
                        <p class="text-gray-500 text-xs mt-2">Expected this month</p>
                    </div>

                    <!-- Active Members -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-4xl">👥</div>
                            <span class="text-blue-400 text-sm font-semibold"><?= round(($activeInactiveCount['active_count'] / max(($memberCount['active_member_count'] ?? 1), 1)) * 100, 1) ?>%</span>
                        </div>
                        <p class="text-gray-400 text-sm font-medium mb-1">Active Members</p>
                        <p class="text-3xl font-bold text-white"><?= $activeInactiveCount['active_count'] ?? 0 ?></p>
                        <p class="text-gray-500 text-xs mt-2"><?= $activeInactiveCount['inactive_count'] ?? 0 ?> inactive</p>
                    </div>

                    <!-- Retention Rate -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-4xl">📈</div>
                            <span class="text-purple-400 text-sm font-semibold">Retention</span>
                        </div>
                        <p class="text-gray-400 text-sm font-medium mb-1">Retention Rate</p>
                        <p class="text-3xl font-bold text-white"><?= $retentionRate['rate'] ?>%</p>
                        <p class="text-gray-500 text-xs mt-2"><?= $retentionRate['active'] ?> of <?= $retentionRate['total'] ?> members</p>
                    </div>

                    <!-- Expiring Subscriptions -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-4xl">⚠️</div>
                            <span class="text-red-400 text-sm font-semibold">Urgent</span>
                        </div>
                        <p class="text-gray-400 text-sm font-medium mb-1">Expiring Soon</p>
                        <p class="text-3xl font-bold text-white"><?= $expiringSubscriptions['expiring_count'] ?? 0 ?></p>
                        <p class="text-gray-500 text-xs mt-2">Next 7 days</p>
                    </div>

                    <!-- Average Transaction -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-4xl">💳</div>
                            <span class="text-cyan-400 text-sm font-semibold">Avg</span>
                        </div>
                        <p class="text-gray-400 text-sm font-medium mb-1">Avg Transaction</p>
                        <p class="text-3xl font-bold text-white">₱<?= number_format(($paymentStats['total_paid'] ?? 0) / max(($paymentStats['paid_count'] ?? 1), 1), 2) ?></p>
                        <p class="text-gray-500 text-xs mt-2">Per transaction</p>
                    </div>

                    <!-- Total Plans -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-4xl">📋</div>
                            <span class="text-yellow-400 text-sm font-semibold"><?= count($activePlans) ?> active</span>
                        </div>
                        <p class="text-gray-400 text-sm font-medium mb-1">Membership Plans</p>
                        <p class="text-3xl font-bold text-white"><?= count($plans) ?></p>
                        <p class="text-gray-500 text-xs mt-2">Total available</p>
                    </div>

                    <!-- Payment Success Rate -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                               <div class="text-4xl">✅</div>
                            <span class="text-green-400 text-sm font-semibold">Success</span>
                        </div>
                        <p class="text-gray-400 text-sm font-medium mb-1">Success Rate</p>
                        <p class="text-3xl font-bold text-white"><?= round((($paymentStats['paid_count'] ?? 0) / max(($paymentStats['total_transactions'] ?? 1), 1)) * 100, 1) ?>%</p>
                        <p class="text-gray-500 text-xs mt-2"><?= $paymentStats['failed_count'] ?? 0 ?> failed</p>
                    </div>
                </div>

                <!-- Charts Row 1 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Revenue Trend Chart -->
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Revenue Trend (Last 12 Months)</h3>
                        <div style="position: relative; height: 300px;">
                            <canvas id="revenueTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Daily Revenue Chart -->
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Daily Revenue (Last 30 Days)</h3>
                        <div style="position: relative; height: 300px;">
                            <canvas id="dailyRevenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 2 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Revenue by Plan Chart -->
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Revenue by Membership Plan</h3>
                        <div style="position: relative; height: 300px;">
                            <canvas id="revenueByPlanChart" height="300"></canvas>
                        </div>
                    </div>

                    <!-- Members by Plan Chart -->
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Members by Plan</h3>
                        <div style="position: relative; height: 300px;">
                            <canvas id="membersByPlanChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 3 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Member Growth Chart -->
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Member Growth (Last 12 Months)</h3>
                        <div style="position: relative; height: 300px;">
                            <canvas id="memberGrowthChart" height="300"></canvas>
                        </div>
                    </div>

                        <!-- Payment Method Distribution -->
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Payment Methods</h3>
                        <div style="position: relative; height: 300px;">
                            <canvas id="paymentMethodChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 4 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Active vs Inactive Members -->
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Member Status Distribution</h3>
                        <div style="position: relative; height: 300px;">
                            <canvas id="memberStatusChart" height="300"></canvas>
                        </div>
                     </div>

                    <!-- Subscription Status -->
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Subscription Status</h3>
                        <div style="position: relative; height: 300px;">
                            <canvas id="subscriptionStatusChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Performers Table -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h3 class="text-xl font-bold text-white mb-4">Top Performing Plans</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-white">
                        <thead class="border-b border-gray-700">
                                <tr>
                                    <th class="text-left px-4 py-3 font-semibold">Plan Name</th>
                                    <th class="text-right px-4 py-3 font-semibold">Total Revenue</th>
                                    <th class="text-right px-4 py-3 font-semibold">Transactions</th>
                                    <th class="text-right px-4 py-3 font-semibold">Avg per Transaction</th>
                                </tr>
                        </thead>
                            <tbody>
                                <?php foreach($revenueByPlan as $plan): ?>
                                <tr class="border-b border-gray-700 hover:bg-gray-750">
                                    <td class="px-4 py-3"><?= htmlspecialchars($plan['plan_name']) ?></td>
                                    <td class="px-4 py-3 text-right text-green-400 font-semibold">₱<?= number_format($plan['total_revenue'], 2) ?></td>
                                    <td class="px-4 py-3 text-right"><?= $plan['payment_count'] ?></td>
                                    <td class="px-4 py-3 text-right">₱<?= number_format($plan['total_revenue'] / $plan['payment_count'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </main>
    <script src="../public/assets/js/admin/admin.js"></script>
    <!-- Add Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    <script>
    // Chart.js Configuration
    document.addEventListener('DOMContentLoaded', function() {
        // Common chart options with FIXED settings
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            color: '#9ca3af',
                            size: 12
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    bodySpacing: 4,
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        color: '#9ca3af',
                        font: { size: 11 }
                    },
                    grid: { 
                        color: 'rgba(255, 255, 255, 0.1)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: { 
                        color: '#9ca3af',
                        font: { size: 11 },
                        maxRotation: 45,
                        minRotation: 0
                    },
                    grid: { 
                        color: 'rgba(255, 255, 255, 0.1)',
                        drawBorder: false
                    }
                }
            },
            animation: {
                duration: 750
            }
        };

        // Doughnut/Pie chart options
        const pieOptions = {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { 
                        color: '#9ca3af',
                        padding: 15,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12
                }
            },
            animation: {
                duration: 750
            }
        };

        // Revenue Trend Chart (Last 12 Months)
        const revenueTrendData = <?= json_encode($last12MonthsRevenue) ?>;
        const ctx1 = document.getElementById('revenueTrendChart');
        if(ctx1) {
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: revenueTrendData.map(d => d.month_label),
                    datasets: [{
                        label: 'Monthly Revenue (₱)',
                        data: revenueTrendData.map(d => d.revenue),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: commonOptions
            });
        }

        // Daily Revenue Chart (Last 30 Days)
        const dailyRevenueData = <?= json_encode($dailyRevenue30Days) ?>;
        const ctx2 = document.getElementById('dailyRevenueChart');
        if(ctx2) {
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: dailyRevenueData.map(d => d.date_label),
                    datasets: [{
                        label: 'Daily Revenue (₱)',
                        data: dailyRevenueData.map(d => d.revenue),
                        backgroundColor: 'rgba(34, 197, 94, 0.6)',
                        borderColor: '#22c55e',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: commonOptions
            });
        }

        // Revenue by Plan Chart
        const revenueByPlanData = <?= json_encode($revenueByPlan) ?>;
        const ctx3 = document.getElementById('revenueByPlanChart');
        if(ctx3) {
            new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: revenueByPlanData.map(d => d.plan_name),
                    datasets: [{
                        data: revenueByPlanData.map(d => d.total_revenue),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#1f2937',
                        hoverOffset: 4
                    }]
                },
                options: pieOptions
            });
        }

        // Members by Plan Chart
        const membersByPlanData = <?= json_encode($membersByPlan) ?>;
        const ctx4 = document.getElementById('membersByPlanChart');
        if(ctx4) {
            new Chart(ctx4, {
                type: 'bar',
                data: {
                    labels: membersByPlanData.map(d => d.plan_name),
                    datasets: [{
                        label: 'Members',
                        data: membersByPlanData.map(d => d.member_count),
                        backgroundColor: 'rgba(168, 85, 247, 0.6)',
                        borderColor: '#a855f7',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: commonOptions  
                
            });
        }

        // Member Growth Chart
        const memberGrowthData = <?= json_encode($memberGrowth) ?>;
        const ctx5 = document.getElementById('memberGrowthChart');
        if(ctx5) {
            new Chart(ctx5, {
                type: 'line',
                data: {
                    labels: memberGrowthData.map(d => d.month_label),
                    datasets: [{
                        label: 'New Members',
                        data: memberGrowthData.map(d => d.new_members),
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: commonOptions
            });
        }

        // Payment Method Chart
        const paymentMethodData = <?= json_encode($paymentMethodStats) ?>;
        const ctx6 = document.getElementById('paymentMethodChart');
        if(ctx6) {
            new Chart(ctx6, {
                type: 'pie',
                data: {
                    labels: paymentMethodData.map(d => d.payment_method || 'Unknown'),
                    datasets: [{
                        data: paymentMethodData.map(d => d.total_amount),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#1f2937',
                        hoverOffset: 4
                    }]
                },
                options: pieOptions
            });
        }

        // Member Status Chart
        const activeInactiveData = <?= json_encode($activeInactiveCount) ?>;
        const ctx7 = document.getElementById('memberStatusChart');
        if(ctx7) {
            new Chart(ctx7, {
                type: 'doughnut',
                data: {
                    labels: ['Active Members', 'Inactive Members'],
                    datasets: [{
                        data: [activeInactiveData.active_count, activeInactiveData.inactive_count],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderWidth: 2,
                        borderColor: '#1f2937',
                        hoverOffset: 4
                    }]
                },
                options: pieOptions
            });
        }

        // Subscription Status Chart
        const subscriptionStatusData = <?= json_encode($subscriptionStatusBreakdown) ?>;
        const ctx8 = document.getElementById('subscriptionStatusChart');
        if(ctx8) {
            new Chart(ctx8, {
                type: 'bar',
                data: {
                    labels: subscriptionStatusData.map(d => d.status.charAt(0).toUpperCase() + d.status.slice(1)),
                    datasets: [{
                        label: 'Subscriptions',
                        data: subscriptionStatusData.map(d => d.count),
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.6)',
                            'rgba(251, 146, 60, 0.6)',
                            'rgba(239, 68, 68, 0.6)',
                            'rgba(59, 130, 246, 0.6)'
                        ],
                        borderColor: [
                            '#22c55e',
                            '#fb923c',
                            '#ef4444',
                            '#3b82f6'
                        ],
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    ...commonOptions,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12
                        }
                    }
                }
            });
        }
    });
    </script>
    <script src="../public/assets/js/admin/reports.js"></script>
</body>
</html>