<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';

$user = getCurrentUser();
if (!$user) {
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit - 66ZZ Gaming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card bg-dark text-white">
            <div class="card-header">
                <h4>
                    <a href="/myaccount" class="btn btn-sm btn-outline-light me-2"><i class="fas fa-arrow-left"></i></a>
                    <i class="fas fa-plus text-success"></i>
                    Deposit Money
                </h4>
            </div>
            <div class="card-body">
                <form id="depositForm">
                    <div class="mb-3">
                        <label class="form-label">Amount (৳)</label>
                        <input type="number" class="form-control" name="amount" min="100" max="50000" required placeholder="Enter amount">
                        <div class="form-text">Minimum: ৳100, Maximum: ৳50,000</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-control" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="quick-amounts">
                        <label class="form-label">Quick Select:</label>
                        <div class="quick-amount-buttons">
                            <button type="button" class="btn btn-outline-primary" onclick="setDepositAmount(500)">৳500</button>
                            <button type="button" class="btn btn-outline-primary" onclick="setDepositAmount(1000)">৳1,000</button>
                            <button type="button" class="btn btn-outline-primary" onclick="setDepositAmount(2000)">৳2,000</button>
                            <button type="button" class="btn btn-outline-primary" onclick="setDepositAmount(5000)">৳5,000</button>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-credit-card"></i>
                            Proceed to Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setDepositAmount(amount) {
            document.querySelector('#depositForm input[name="amount"]').value = amount;
        }

        document.getElementById('depositForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const amount = formData.get('amount');
            const paymentMethod = formData.get('payment_method');
            
            // In a real app, this would be an API call
            console.log(`Depositing ${amount} via ${paymentMethod}`);
            alert('Deposit request sent successfully!');
        });
    </script>
</body>
</html>