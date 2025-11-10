<?php 
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment & Billing - Gymazing</title>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../public/assets/icons/fontawesome/css/all.min.css"></link>

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
            font-size: 0.75rem;
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
                <?php
                    $currentPlan = array_filter($paymentDetails, function($value) {
                        return $value['status'] == 'pending';
                    });
                    $currentPlan = reset($currentPlan); // Get first element
                ?>
                
                <?php if ($currentPlan): ?>
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 lg:p-10 shadow-2xl border border-blue-500">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <span class="status-badge status-pending pulse-animation mb-3 inline-block">Payment Due</span>
                                <h2 class="text-3xl font-bold text-white mb-2">Current Plan: <?= $currentPlan['plan_name'] ?></h2>
                                <p class="text-blue-100">Subscription ID: <?= $currentPlan['subscription_id'] ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-blue-100 text-sm mb-1">Amount Due</p>
                                <p class="text-5xl font-bold text-white">₱<?= number_format($currentPlan['amount']) ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                                <p class="text-blue-600 text-sm mb-1">Start Date</p>
                                <p class="text-blue-600 font-semibold"><?= date('M d, Y', strtotime($currentPlan['start_date'])) ?></p>
                            </div>
                            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                                <p class="text-blue-600 text-sm mb-1">End Date</p>
                                <p class="text-blue-600 font-semibold"><?= date('M d, Y', strtotime($currentPlan['end_date'])) ?></p>
                            </div>
                            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                                <p class="text-blue-600 text-sm mb-1">Due Date</p>
                                <p class="text-blue-600 font-semibold"><?= date('M d, Y', strtotime($currentPlan['payment_date'])) ?></p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button id="btnPayNow" data-subscription-id="<?= $currentPlan['subscription_id'] ?>" data-amount="<?= $currentPlan['amount'] ?>" data-plan="<?= $currentPlan['plan_name'] ?>"
                                    class="flex-1 px-8 py-4 bg-white hover:bg-gray-100 text-blue-600 font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fa-regular fa-credit-card text-yellow-400"></i> Pay Now
                            </button>
                            <button class="flex-1 px-8 py-4 border-2 border-white hover:bg-white hover:text-blue-600 text-white font-bold rounded-xl transition-all duration-300">
                                <i class="fa-regular fa-file-lines text-white"></i> Download Invoice
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
                        <i class="fa-regular fa-share-from-square"></i> Export History
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
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600 payment-method-option">
                        <input type="radio" name="payment_method" value="card" class="mr-3" checked>
                        <span class="text-white">Credit/Debit Card</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600 payment-method-option">
                        <input type="radio" name="payment_method" value="gcash" class="mr-3">
                        <span class="text-white">GCash</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600 payment-method-option">
                        <input type="radio" name="payment_method" value="paymaya" class="mr-3">
                        <span class="text-white">PayMaya</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition-colors border border-gray-600 payment-method-option">
                        <input type="radio" name="payment_method" value="bank" class="mr-3">
                        <span class="text-white">Bank Transfer</span>
                    </label>
                </div>
            </div>

            <div id="paymentMessage" class="hidden mb-4"></div>

            <div class="flex space-x-4">
                <button class="modal-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button id="btnProceedPayment" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    Proceed to Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Details Modal (Step 2) -->
    <div id="paymentDetailsModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-lg w-full border border-gray-700 max-h-[90vh] overflow-y-auto">
            <button class="payment-details-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Payment Information</h3>

            <!-- Payment Summary -->
            <div class="bg-gray-800 rounded-lg p-4 mb-6 border border-gray-700">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Amount to Pay:</span>
                    <span class="text-2xl font-bold text-white" id="detailsAmount"></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-gray-400">Payment Method:</span>
                    <span class="text-white font-semibold" id="detailsMethod"></span>
                </div>
            </div>

            <!-- Dynamic Payment Form -->
            <form id="paymentDetailsForm" class="space-y-4">
                 <input type="hidden" id="form_subscription_id" name="subscription_id">

                <input type="hidden" id="form_amount" name="amount">

                <input type="hidden" id="form_payment_method" name="payment_method">

                <!-- Credit/Debit Card Form -->
                <div id="cardForm" class="payment-form-section">
                    <div>
                        <label class="block text-white font-semibold mb-2">Cardholder Name</label>
                        <input type="text" name="cardholder_name" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Card Number</label>
                        <input type="text" name="card_number" maxlength="19" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">Expiry Date</label>
                            <input type="text" name="expiry_date" maxlength="5" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="MM/YY">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">CVV</label>
                            <input type="text" name="cvv" maxlength="4" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="123">
                        </div>
                    </div>
                </div>

                <!-- GCash Form -->
                <div id="gcashForm" class="payment-form-section hidden">
                    <div>
                        <label class="block text-white font-semibold mb-2">GCash Mobile Number</label>
                        <input type="tel" name="gcash_number" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="+63 9XX XXX XXXX">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Account Name</label>
                        <input type="text" name="gcash_name" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Juan Dela Cruz">
                    </div>
                    <div class="bg-blue-900 bg-opacity-30 border border-blue-600 rounded-lg p-4 mt-4">
                        <p class="text-blue-200 text-sm">
                            <strong>Note:</strong> You will be redirected to GCash app/website to complete the payment.
                        </p>
                    </div>
                </div>

                <!-- PayMaya Form -->
                <div id="paymayaForm" class="payment-form-section hidden">
                    <div>
                        <label class="block text-white font-semibold mb-2">PayMaya Mobile Number</label>
                        <input type="tel" name="paymaya_number" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="+63 9XX XXX XXXX">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Account Name</label>
                        <input type="text" name="paymaya_name" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Juan Dela Cruz">
                    </div>
                    <div class="bg-green-900 bg-opacity-30 border border-green-600 rounded-lg mt-4 p-4">
                        <p class="text-green-200 text-sm">
                            <strong>Note:</strong> You will be redirected to PayMaya app/website to complete the payment.
                        </p>
                    </div>
                </div>

                <!-- Bank Transfer Form -->
                <div id="bankForm" class="payment-form-section hidden">
                    <div class="bg-yellow-900 bg-opacity-30 border border-yellow-600 rounded-lg p-4 mb-4">
                        <p class="text-yellow-200 text-sm font-semibold mb-2">Bank Transfer Instructions:</p>
                        <p class="text-yellow-200 text-sm">1. Transfer to: Gymazing Fitness Center</p>
                        <p class="text-yellow-200 text-sm">2. Account: 1234-5678-9012</p>
                        <p class="text-yellow-200 text-sm">3. Bank: BDO/BPI/Metrobank</p>
                        <p class="text-yellow-200 text-sm">4. Upload proof of payment below</p>
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Account Holder Name</label>
                        <input type="text" name="bank_account_name" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your name">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Reference Number</label>
                        <input type="text" name="bank_reference" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Transaction reference">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Upload Proof of Payment</label>
                        <input type="file" name="payment_proof" accept="image/*" class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div id="detailsMessage" class="hidden"></div>

                <div class="flex space-x-4 mt-6">
                    <button type="button" class="payment-details-back flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        ← Back
                    </button>
                    <button type="submit" id="btnConfirmPayment" class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../public/assets/js/payments.js"></script>
</body>
</html>