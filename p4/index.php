<!DOCTYPE html>
<html>
<head>
    <title>PhonePe Payment Integration</title>
</head>
<body>
    <form action="process_payment.php" method="post">
        <label for="amount">Amount:</label>
        <input type="text" name="amount" id="amount" required>
        <input type="hidden" name="merchant_id" value="YOUR_MERCHANT_ID">
        <input type="submit" value="Pay with PhonePe">
    </form>
</body>
</html>
