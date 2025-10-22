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
            <?php foreach($paymentDetails as $p) {?>
                <?= $p['plan_name']?>
                <?= $p['amount']?>       
                <?= $p['end_date']?>
                <?= $p['status']?>
            <?php }?>
        </section>
        <section id="transaction_history">

        </section>
    </main>
</body>
</html>