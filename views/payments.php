<?php 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Gymazing</title>
</head>
<body>
    <main class="min-h-screen">
        <section id="current_payment">
            <?php
                $currentPlan = array_filter($paymentDetails, function($value) {
                    return $value['status'] == 'pending';
                });
            ?>
            <h1>Current Plan</h1>
            <?= $currentPlan[0]['subscription_id'] ?>
            <?= $currentPlan[0]['plan_name'] ?>
            <?= $currentPlan[0]['start_date'] ?>
            <?= $currentPlan[0]['end_date'] ?>
            <?= $currentPlan[0]['amount'] ?>
            <?= $currentPlan[0]['payment_date'] ?>
            <?= $currentPlan[0]['status'] ?>
            <br>
            <a href="">Pay Plan</a>
        </section>
        <section id="transaction_history">
            <h2>Transaction History</h2>
            <table border=1>
                <tr>
                    <th>ID</th>
                    <th>Plan Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Payment status</th>
                </tr>
                
                <?php foreach ($paymentDetails as $paymentDetail) { ?>
                    <tr>
                        <td><?= $paymentDetail['subscription_id'] ?></td>
                        <td><?= $paymentDetail['plan_name'] ?></td>
                        <td><?= $paymentDetail['start_date'] ?></td>
                        <td><?= $paymentDetail['end_date'] ?></td>
                        <td><?= $paymentDetail['amount'] ?></td>
                        <td><?= $paymentDetail['payment_date'] ?></td>
                        <td><?= $paymentDetail['status'] ?></td>
                    </tr>
                <?php } ?>
                
            </table>
        </section>
    </main>
</body>
</html>