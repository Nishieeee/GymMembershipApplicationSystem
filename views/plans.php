<?php 
    // Sample plan features - customize based on your database
    $plan_features = [
        1 => [
            "Unlimited gym access",
            "Basic equipment access",
            "Community support",
            "Mobile app access"
        ],
        2 => [
            "Unlimited gym access",
            "All equipment access",
            "Priority support",
            "Mobile app access",
            "1 free personal training session",
            "Group classes included"
        ],
        3 => [
            "24/7 gym access",
            "All equipment access",
            "Priority support",
            "Mobile app access",
            "Unlimited personal training",
            "Unlimited group classes",
            "Nutritionist consultation",
            "Performance tracking"
        ]
    ];

    $current_plan = isset($_SESSION['current_plan']) ? $_SESSION['current_plan'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans - Gymazing</title>
    <script src="../public/assets/js/tailwindcss/tailwindcss.js"></script>
    <script src="../public/assets/js/jquery/jquery-3.7.1.min.js"></script>
    <style>
       .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d3748 50%, #1a1a1a 100%);
        }

        .plan-card {
            transition: all 0.3s ease;
        }

        .plan-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .plan-card.featured {
            border: 2px solid #3b82f6;
            transform: scale(1.05);
        }

        .plan-card.featured:hover {
            transform: scale(1.05) translateY(-12px);
        }

        .badge-featured {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .feature-item {
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateX(8px);
        }

        .comparison-table {
            overflow-x: auto;
        }

        .btn-subscribe {
            position: relative;
            overflow: hidden;
        }

        .btn-subscribe::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-subscribe:active::after {
            width: 300px;
            height: 300px;
        }

        .faq-item {
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            opacity: 1;
            padding-top: 1rem;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: all 0.3s ease;
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

        .success-checkmark {
            animation: bounceIn 0.6s ease;
        }

        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
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
    </style>
</head>
<body class="gradient-bg min-h-screen">
    
    

    <!-- Alerts Container -->
    <div id="alertContainer" class="fixed top-24 right-4 z-40 space-y-4"></div>

    <!-- Main Content -->
    <main class="mt-20 pb-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <section class="py-12 lg:py-16">
                <div class="text-center mb-12">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4">
                        Choose Your <span class="text-blue-400">Perfect Plan</span>
                    </h1>
                    <p class="text-gray-400 text-lg lg:text-xl max-w-3xl mx-auto">
                        Select a membership plan that fits your fitness goals. Start your 3-day free trial today!
                    </p>
                </div>

                <!-- Plan Toggle (Annual vs Monthly) -->
                <div class="flex items-center justify-center mb-12">
                    <span class="text-white font-semibold mr-4 monthly-label">Monthly</span>
                    <button class="relative w-16 h-8 bg-gray-600 rounded-full focus:outline-none transition-colors" id="billingToggle">
                        <div class="toggle-slider absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition-transform"></div>
                    </button>
                    <span class="text-white font-semibold ml-4">
                        Annual 
                        <span class="annual-savings bg-yellow-500 text-gray-900 px-2 py-1 rounded text-sm ml-2 font-bold">Save 20%</span>
                    </span>
                </div>
            </section>

            <!-- Plans Grid -->
            <section class="mb-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10 plans-container">
                    <?php if(!empty($plans)) {
                        foreach($plans as $index => $plan) {
                            $is_featured = isset($plan['is_featured']) && $plan['is_featured'] == 1;
                            $features = $plan_features[$index + 1] ?? [];
                    ?>
                        <div class="plan-card <?= $is_featured ? 'featured' : '' ?> bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col" data-plan-id="<?= $plan['plan_id'] ?>" data-plan-name="<?= $plan['plan_name'] ?>" data-plan-price="<?= $plan['price'] ?>">
                            
                            <!-- Featured Badge -->
                            <?php if($is_featured) { ?>
                                <div class="badge-featured bg-gradient-to-r from-blue-600 to-blue-800 text-white px-4 py-2 text-center font-bold text-sm">
                                    ⭐ MOST POPULAR
                                </div>
                            <?php } else { ?>
                                <div class="h-8"></div>
                            <?php } ?>

                            <!-- Plan Header -->
                            <div class="px-8 py-6 text-center">
                                <h3 class="text-3xl font-bold text-gray-900 mb-2 plan-name"><?= $plan['plan_name'] ?></h3>
                                <p class="text-gray-600 text-sm mb-6"><?= isset($plan['tagline']) ? $plan['tagline'] : 'Perfect for you' ?></p>
                                
                                <!-- Price -->
                                <div class="mb-6">
                                    <div class="text-5xl font-bold text-gray-900 plan-price-display">
                                        ₱<span class="price-value"><?= number_format($plan['price']) ?></span>
                                        <span class="text-lg font-normal text-gray-600">/mo</span>
                                    </div>
                                    <p class="text-gray-500 text-sm mt-2"><!--First 3 days free, then -->charged monthly</p>
                                </div>

                                <!-- CTA Button -->
                                <button class="btn-subscribe btn-subscribe-plan w-full px-6 py-4 <?= $is_featured ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800' : 'bg-gray-900 hover:bg-gray-800' ?> text-white font-semibold rounded-xl transition-all duration-300 mb-4" data-plan-id="<?= $plan['plan_id'] ?>">
                                    <?= $current_plan == $plan['plan_id'] ? '✓ Current Plan' : 'Get Started' ?>
                                </button>
                                <p class="text-gray-600 text-xs">No credit card required for trial</p>
                            </div>

                            <!-- Features List -->
                            <div class="px-8 py-8 flex-1 border-t border-gray-200">
                                <h4 class="text-sm font-bold text-gray-900 mb-4 uppercase">What's Included:</h4>
                                <ul class="space-y-3">
                                    <?php foreach($features as $feature) { ?>
                                        <li class="feature-item flex items-start text-gray-700">
                                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span><?= $feature ?></span>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php }
                    } else {
                    ?>
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-400 text-lg">No plans available at the moment. Please check back later.</p>
                        </div>
                    <?php } ?>
                </div>
            </section>

            <!-- Comparison Table Section -->
            <section class="mb-16">
                <div class="bg-neutral-900 rounded-2xl p-8 border border-gray-700">
                    <h2 class="text-3xl font-bold text-white mb-8 text-center">Compare Plans</h2>
                    <div class="comparison-table">
                        <table class="w-full text-white">
                            <thead class="border-b border-gray-700">
                                <tr>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-400">Feature</th>
                                    <?php foreach($plans as $plan) { ?>
                                        <th class="text-center py-4 px-4 font-semibold"><?= $plan['plan_name'] ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-700">
                                    <td class="py-4 px-4 text-gray-400">Gym Access</td>
                                    <td class="text-center py-4 px-4">🕒 Daytime</td>
                                    <td class="text-center py-4 px-4">⏰ Extended</td>
                                    <td class="text-center py-4 px-4">🔓 24/7</td>
                                </tr>
                                <tr class="border-b border-gray-700">
                                    <td class="py-4 px-4 text-gray-400">Personal Training</td>
                                    <td class="text-center py-4 px-4">❌</td>
                                    <td class="text-center py-4 px-4">1 Session</td>
                                    <td class="text-center py-4 px-4">✓ Unlimited</td>
                                </tr>
                                <tr class="border-b border-gray-700">
                                    <td class="py-4 px-4 text-gray-400">Group Classes</td>
                                    <td class="text-center py-4 px-4">❌</td>
                                    <td class="text-center py-4 px-4">✓ Included</td>
                                    <td class="text-center py-4 px-4">✓ Unlimited</td>
                                </tr>
                                <tr class="border-b border-gray-700">
                                    <td class="py-4 px-4 text-gray-400">Nutrition Consultation</td>
                                    <td class="text-center py-4 px-4">❌</td>
                                    <td class="text-center py-4 px-4">❌</td>
                                    <td class="text-center py-4 px-4">✓ Yes</td>
                                </tr>
                                <tr class="border-b border-gray-700">
                                    <td class="py-4 px-4 text-gray-400">Priority Support</td>
                                    <td class="text-center py-4 px-4">❌</td>
                                    <td class="text-center py-4 px-4">✓ Yes</td>
                                    <td class="text-center py-4 px-4">✓ 24/7</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-4 text-gray-400">Performance Tracking</td>
                                    <td class="text-center py-4 px-4">❌</td>
                                    <td class="text-center py-4 px-4">❌</td>
                                    <td class="text-center py-4 px-4">✓ Advanced</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="mb-16">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-3xl font-bold text-white mb-8 text-center">Frequently Asked Questions</h2>
                    
                    <div class="space-y-4">
                        <!-- FAQ Item 1 -->
                        <div class="faq-item bg-neutral-900 rounded-lg border border-gray-700 overflow-hidden">
                            <button class="faq-toggle w-full px-6 py-4 flex items-center justify-between text-white hover:bg-gray-800 transition-colors">
                                <span class="font-semibold text-left">Can I change my plan anytime?</span>
                                <svg class="faq-icon w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </button>
                            <div class="faq-answer bg-gray-800 px-6 text-gray-300">
                                <p>Yes! You can upgrade, downgrade, or cancel your plan at any time. Changes take effect at your next billing cycle.</p>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="faq-item bg-neutral-900 rounded-lg border border-gray-700 overflow-hidden">
                            <button class="faq-toggle w-full px-6 py-4 flex items-center justify-between text-white hover:bg-gray-800 transition-colors">
                                <span class="font-semibold text-left">Is the 3-day trial free?</span>
                                <svg class="faq-icon w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </button>
                            <div class="faq-answer bg-gray-800 px-6 text-gray-300">
                                <p>Absolutely! Your first 3 days are completely free. No credit card charges during the trial period. Full access to all plan features.</p>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="faq-item bg-neutral-900 rounded-lg border border-gray-700 overflow-hidden">
                            <button class="faq-toggle w-full px-6 py-4 flex items-center justify-between text-white hover:bg-gray-800 transition-colors">
                                <span class="font-semibold text-left">What payment methods do you accept?</span>
                                <svg class="faq-icon w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </button>
                            <div class="faq-answer bg-gray-800 px-6 text-gray-300">
                                <p>We accept credit cards (Visa, Mastercard), debit cards, and mobile payment methods (GCash, PayMaya). All transactions are secure and encrypted.</p>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="faq-item bg-neutral-900 rounded-lg border border-gray-700 overflow-hidden">
                            <button class="faq-toggle w-full px-6 py-4 flex items-center justify-between text-white hover:bg-gray-800 transition-colors">
                                <span class="font-semibold text-left">Can I get a refund?</span>
                                <svg class="faq-icon w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </button>
                            <div class="faq-answer bg-gray-800 px-6 text-gray-300">
                                <p>The 3-day trial is free and if you're not satisfied, simply cancel before the trial ends with no charges. For paid periods, refunds are subject to our terms.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="mb-16">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-12 text-center text-white shadow-2xl">
                    <h2 class="text-3xl lg:text-4xl font-bold mb-4">Ready to Transform Your Fitness?</h2>
                    <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">Join thousands of members who have already achieved their fitness goals with Gymazing.</p>
                    <button class="scroll-to-plans px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                        Choose Your Plan Now
                    </button>
                </div>
            </section>
        </div>
    </main>

    <!-- Subscription Modal -->
    <div id="subscriptionModal" class="<?= $openModal ?? 'show'?> modal-backdrop fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-gray-900 rounded-2xl p-8 max-w-md w-full border border-gray-700">
            <button class="modal-close float-right text-gray-400 hover:text-white text-2xl mb-4">&times;</button>
            
            <h3 class="text-2xl font-bold text-white mb-4">Subscribe to <span id="modalPlanName" class="text-blue-400"></span></h3>
            
            <!-- Plan Details -->
            <div class="bg-gray-800 rounded-lg p-4 mb-6">
                <p class="text-gray-400 text-sm">Monthly Price</p>
                <p class="text-3xl font-bold text-white" id="modalPlanPrice"></p>
                <p class="text-gray-400 text-sm mt-2">First 3 days FREE</p>
            </div>

            <!-- Subscription Form -->
            <form id="subscriptionForm" class="space-y-4" action="index.php?controller=Subscribe&action=Subscribe" method="POST">
                <input type="hidden" id="modal_plan_id" name="plan_id">

                <div class="flex items-start">
                    <input type="checkbox" id="modal_terms" name="terms" required class="mt-1">
                    <label for="modal_terms" class="text-gray-300 text-sm ml-2">
                        I agree to the terms and conditions and understand the subscription will begin after the trial period.
                    </label>
                </div>

                <div id="formMessage" class="hidden"></div>

                <div class="flex space-x-4 mt-6">
                    <button type="button" class="modal-cancel flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <input type="submit" value="Confirm Subscription" id="submitBtn" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        
                </div>
            </form>
        </div>
    </div>
    <?php include_once __DIR__ . "/layouts/footer.php" ?>                                 
    <script src="../public/assets/js/plans.js"></script>
</body>
</html>