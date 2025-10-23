<?php 
    // $paymentDetails = [
    //     [
    //         'subscription_id' => 'SUB-001',
    //         'plan_name' => 'Premium',
    //         'start_date' => '2024-10-01',
    //         'end_date' => '2024-11-01',
    //         'amount' => 2999,
    //         'payment_date' => '2024-10-23',
    //         'status' => 'pending'
    //     ],
    //     [
    //         'subscription_id' => 'SUB-002',
    //         'plan_name' => 'Premium',
    //         'start_date' => '2024-09-01',
    //         'end_date' => '2024-10-01',
    //         'amount' => 2999,
    //         'payment_date' => '2024-09-15',
    //         'status' => 'paid'
    //     ],
    //     [
    //         'subscription_id' => 'SUB-003',
    //         'plan_name' => 'Standard',
    //         'start_date' => '2024-08-01',
    //         'end_date' => '2024-09-01',
    //         'amount' => 1499,
    //         'payment_date' => '2024-08-20',
    //         'status' => 'paid'
    //     ]
    // ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment & Billing - Gymazing</title>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d3748 50%, #1a1a1a 100%);
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

        .payment-card {
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.1) 0%, rgba(30, 58, 138, 0.2) 100%);
            border: 1px solid rgba(29, 78, 216, 0.3);
            transition: all 0.3s ease;
        }

        .payment-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(29, 78, 216, 0.2);
            border-color: #1e40af;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            
            font-weight: 600;
        }

        .status-paid {
            background-color: rgba(34, 197, 94, 1);
            color: white;
        }

        .status-pending {
            background-color: rgba(251, 146, 60, 1);
            color: white;
        }

        .status-failed {
            background-color: rgba(239, 68, 68, 1);
            color: white;
        }

        .table-row {
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background-color: rgba(29, 78, 216, 0.05);
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

        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }

        .fadeIn {
            animation: fadeIn 0.5s ease;
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
    </style>
</head>
<body class="gradient-bg min-h-screen">
    
    <!-- Sidebar/Header -->
    <?php include_once '../views/layouts/navbar.php'?>

    <!-- Alerts Container -->
    <div id="alertContainer" class="fixed top-24 right-4 z-40 space-y-4"></div>

    <main class="min-h-screen pt-24 pb-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Header Section -->
            <div class="mb-8 fadeIn">
                <h1 class="text-4xl font-bold text-white mb-2">Payment & Billing</h1>
                <p class="text-gray-400">Manage your subscription and view payment history</p>
            </div>

            <!-- Current Plan Section -->
            <section id="current_payment" class="mb-12 fadeIn">
                            
                <?php if ($paymentDetails[0]): ?>
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 lg:p-10 shadow-2xl border border-blue-500">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <span class="status-badge text-lg <?= $paymentDetails[0]['status'] == 'paid' ? 'status-paid' : 'status-pending' ?> pulse-animation mb-3 inline-block"><?= $paymentDetails[0]['status'] ?></span>
                                <h2 class="text-3xl font-bold text-white mb-2">Current Plan: <?= $paymentDetails[0]['plan_name'] ?></h2>
                                <p class="text-blue-100">Subscription ID: <?= $paymentDetails[0]['subscription_id'] ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-blue-100 text-sm mb-1">Amount Due</p>
                                <p class="text-5xl font-bold text-white">₱<?= number_format($paymentDetails[0]['amount']) ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                                <p class="text-blue-500 text-sm mb-1">Start Date</p>
                                <p class="text-blue-500 font-semibold"><?= date('M d, Y', strtotime($paymentDetails[0]['start_date'])) ?></p>
                            </div>
                            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                                <p class="text-blue-500 text-sm mb-1">End Date</p>
                                <p class="text-blue-500 font-semibold"><?= date('M d, Y', strtotime($paymentDetails[0]['end_date'])) ?></p>
                            </div>
                            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                                <p class="text-blue-500 text-sm mb-1">Due Date</p>
                                <p class="text-blue-500 font-semibold"><?= date('M d, Y', strtotime($paymentDetails[0]['payment_date'])) ?></p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button id="btnPayNow" data-subscription-id="<?= $paymentDetails[0]['subscription_id'] ?>" data-amount="<?= $paymentDetails[0]['amount'] ?>" data-plan="<?= $paymentDetails[0]['plan_name'] ?>"
                                    class="flex-1 px-8 py-4 bg-white hover:bg-gray-100 text-blue-600 font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                💳 Pay Now
                            </button>
                            <button class="flex-1 px-8 py-4 border-2 border-white hover:bg-white hover:text-blue-600 text-white font-bold rounded-xl transition-all duration-300">
                                📄 Download Invoice
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-neutral-900 rounded-2xl p-8 text-center border border-gray-700">
                        <div class="text-6xl mb-4">✓</div>
                        <h3 class="text-2xl font-bold text-white mb-2">No Pending Payments</h3>
                        <p class="text-gray-400">You're all caught up! Your next payment will be processed automatically.</p>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Payment Methods Section -->
            <section class="mb-12 fadeIn">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">Payment Methods</h2>
                    <button id="btnAddPaymentMethod" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        + Add Payment Method
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Credit Card -->
                    <div class="payment-card rounded-xl p-6 border border-gray-700 cursor-pointer">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-green-600 text-white text-xs font-bold rounded-full">Primary</span>
                        </div>
                        <p class="text-white font-semibold text-lg mb-1">Visa •••• 4242</p>
                        <p class="text-gray-400 text-sm">Expires 12/25</p>
                    </div>

                    <!-- GCash -->
                    <div class="payment-card rounded-xl p-6 border border-gray-700 cursor-pointer">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">G</span>
                            </div>
                        </div>
                        <p class="text-white font-semibold text-lg mb-1">GCash</p>
                        <p class="text-gray-400 text-sm">+63 ••• •••• 1234</p>
                    </div>

                    <!-- PayMaya -->
                    <div class="payment-card rounded-xl p-6 border border-gray-700 cursor-pointer">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">P</span>
                            </div>
                        </div>
                        <p class="text-white font-semibold text-lg mb-1">PayMaya</p>
                        <p class="text-gray-400 text-sm">+63 ••• •••• 5678</p>
                    </div>
                </div>
            </section>

            <!-- Transaction History Section -->
            <section id="transaction_history" class="fadeIn">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">Transaction History</h2>
                    <button class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        <span>⬇</span> Export History
                    </button>
                </div>

                <div class="bg-neutral-900 rounded-xl border border-gray-700 shadow-lg overflow-hidden">
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-white">
                            <thead class="border-b border-gray-700 bg-gray-800">
                                <tr>
                                    <th class="text-left px-6 py-4 font-semibold">ID</th>
                                    <th class="text-left px-6 py-4 font-semibold">Plan Name</th>
                                    <th class="text-left px-6 py-4 font-semibold">Period</th>
                                    <th class="text-left px-6 py-4 font-semibold">Amount</th>
                                    <th class="text-left px-6 py-4 font-semibold">Payment Date</th>
                                    <th class="text-left px-6 py-4 font-semibold">Status</th>
                                    <th class="text-center px-6 py-4 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($paymentDetails as $payment) { ?>
                                    <tr class="table-row border-b border-gray-700">
                                        <td class="px-6 py-4 text-gray-300"><?= $payment['subscription_id'] ?></td>
                                        <td class="px-6 py-4 font-semibold"><?= $payment['plan_name'] ?></td>
                                        <td class="px-6 py-4 text-gray-300">
                                            <?= date('M d', strtotime($payment['start_date'])) ?> - <?= date('M d, Y', strtotime($payment['end_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 font-semibold">₱<?= number_format($payment['amount']) ?></td>
                                        <td class="px-6 py-4 text-gray-300"><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge status-<?= $payment['status'] ?>">
                                                <?= ucfirst($payment['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="btn-view-receipt text-blue-400 hover:text-blue-300 mr-3" data-id="<?= $payment['subscription_id'] ?>">
                                                View Receipt
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden p-4 space-y-4">
                        <?php foreach ($paymentDetails as $payment) { ?>
                            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="text-white font-semibold text-lg"><?= $payment['plan_name'] ?></p>
                                        <p class="text-gray-400 text-sm"><?= $payment['subscription_id'] ?></p>
                                    </div>
                                    <span class="status-badge status-<?= $payment['status'] ?>">
                                        <?= ucfirst($payment['status']) ?>
                                    </span>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Amount:</span>
                                        <span class="text-white font-semibold">₱<?= number_format($payment['amount']) ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Period:</span>
                                        <span class="text-white"><?= date('M d', strtotime($payment['start_date'])) ?> - <?= date('M d', strtotime($payment['end_date'])) ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Payment Date:</span>
                                        <span class="text-white"><?= date('M d, Y', strtotime($payment['payment_date'])) ?></span>
                                    </div>
                                </div>
                                <button class="btn-view-receipt w-full mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors" data-id="<?= $payment['subscription_id'] ?>">
                                    View Receipt
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-md w-full border border-gray-700">
            <button class="modal-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Complete Payment</h3>
            
            <!-- Payment Details -->
            <div class="bg-gray-800 rounded-lg p-6 mb-6 border border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-400">Plan</span>
                    <span class="text-white font-semibold" id="modalPlanName"></span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-400">Subscription ID</span>
                    <span class="text-white font-semibold" id="modalSubscriptionId"></span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-700">
                    <span class="text-white font-semibold">Total Amount</span>
                    <span class="text-3xl font-bold text-white" id="modalAmount"></span>
                </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="mb-6">
                <label class="block text-white font-semibold mb-3">Select Payment Method</label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600">
                        <input type="radio" name="payment_method" value="card" class="mr-3" checked>
                        <span class="text-white">Credit/Debit Card (Visa •••• 4242)</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600">
                        <input type="radio" name="payment_method" value="gcash" class="mr-3">
                        <span class="text-white">GCash</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600">
                        <input type="radio" name="payment_method" value="paymaya" class="mr-3">
                        <span class="text-white">PayMaya</span>
                    </label>
                </div>
            </div>

            <div id="paymentMessage" class="hidden mb-4"></div>

            <div class="flex space-x-4">
                <button class="modal-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button id="btnConfirmPayment" class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                    Confirm Payment
                </button>
            </div>
        </div>
    </div> <!-- Payment Modal -->
    <div id="paymentModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-md w-full border border-gray-700">
            <button class="modal-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Complete Payment</h3>
            
            <!-- Payment Details -->
            <div class="bg-gray-800 rounded-lg p-6 mb-6 border border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-400">Plan</span>
                    <span class="text-white font-semibold" id="modalPlanName"></span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-400">Subscription ID</span>
                    <span class="text-white font-semibold" id="modalSubscriptionId"></span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-700">
                    <span class="text-white font-semibold">Total Amount</span>
                    <span class="text-3xl font-bold text-white" id="modalAmount"></span>
                </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="mb-6">
                <label class="block text-white font-semibold mb-3">Select Payment Method</label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600">
                        <input type="radio" name="payment_method" value="card" class="mr-3" checked>
                        <span class="text-white">Credit/Debit Card (Visa •••• 4242)</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600">
                        <input type="radio" name="payment_method" value="gcash" class="mr-3">
                        <span class="text-white">GCash</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600">
                        <input type="radio" name="payment_method" value="paymaya" class="mr-3">
                        <span class="text-white">PayMaya</span>
                    </label>
                </div>
            </div>

            <div id="paymentMessage" class="hidden mb-4"></div>

            <div class="flex space-x-4">
                <button class="modal-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button id="btnConfirmPayment" class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                    Confirm Payment
                </button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // ===== OPEN PAYMENT MODAL =====
            $('#btnPayNow').on('click', function() {
                const subscriptionId = $(this).data('subscription-id');
                const amount = $(this).data('amount');
                const plan = $(this).data('plan');

                $('#modalSubscriptionId').text(subscriptionId);
                $('#modalPlanName').text(plan);
                $('#modalAmount').text('₱' + amount.toLocaleString());
                
                $('#paymentModal').addClass('show');
                $('body').css('overflow', 'hidden');
            });

            // ===== CLOSE MODAL =====
            $('.modal-close, .modal-cancel').on('click', function() {
                $(this).closest('.modal-backdrop').removeClass('show');
                $('body').css('overflow', 'auto');
                $('#paymentMessage').addClass('hidden');
            });

            $('#paymentModal').on('click', function(e) {
                if ($(e.target).is(this)) {
                    $(this).removeClass('show');
                    $('body').css('overflow', 'auto');
                }
            });

            // ===== CONFIRM PAYMENT =====
            $('#btnConfirmPayment').on('click', function() {
                const subscriptionId = $('#modalSubscriptionId').text();
                const paymentMethod = $('input[name="payment_method"]:checked').val();
                const amount = $('#modalAmount').text().replace('₱', '').replace(',', '');

                processPayment(subscriptionId, paymentMethod, amount);
            });

            // ===== VIEW RECEIPT =====
            $(document).on('click', '.btn-view-receipt', function() {
                const receiptId = $(this).data('id');
                showAlert('Opening receipt for: ' + receiptId, 'info');
                // Implement receipt viewing logic
            });

            // ===== ADD PAYMENT METHOD =====
            $('#btnAddPaymentMethod').on('click', function() {
                showAlert('Add payment method functionality - Implement your payment gateway', 'info');
            });

            // ===== HELPER FUNCTIONS =====
            function processPayment(subscriptionId, paymentMethod, amount) {
                const submitBtn = $('#btnConfirmPayment');
                const originalText = submitBtn.text();
                
                submitBtn.html('<span class="loading inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>').prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: 'process_payment.php',
                    data: {
                        subscription_id: subscriptionId,
                        payment_method: paymentMethod,
                        amount: amount
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showPaymentMessage('✓ Payment successful! Redirecting...', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            showPaymentMessage(response.message || 'Payment failed', 'error');
                            submitBtn.html(originalText).prop('disabled', false);
                        }
                    },
                    error: function() {
                        showPaymentMessage('An error occurred. Please try again.', 'error');
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            }

            function showPaymentMessage(message, type) {
                const messageDiv = $('#paymentMessage');
                const bgColor = type === 'error' ? 'bg-red-500' : 'bg-green-500';
                
                messageDiv.html(`
                    <div class="p-3 rounded-lg ${bgColor} text-white text-sm">
                        ${message}
                    </div>
                `).removeClass('hidden');

                if (type !== 'error') {
                    setTimeout(() => {
                        messageDiv.addClass('hidden');
                    }, 3000);
                }
            }

            function showAlert(message, type = 'info') {
                const alertClass = {
                    'success': 'bg-green-500',
                    'error': 'bg-red-500',
                    'warning': 'bg-yellow-500',
                    'info': 'bg-blue-500'
                }[type] || 'bg-blue-500';

                const alert = $(`
                    <div class="alert ${alertClass} text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between">
                        <span>${message}</span>
                        <button class="text-white hover:text-gray-200 ml-4">&times;</button>
                    </div>
                `);

                alert.find('button').on('click', function() {
                    alert.slideUp(300, function() {
                        $(this).remove();
                    });
                });

                $('#alertContainer').append(alert);

                if (type !== 'error') {
                    setTimeout(() => {
                        alert.slideUp(300, function() {
                            $(this).remove();
                        });
                    }, 4000);
                }
            }
        });
    </script>
</body>
</html>