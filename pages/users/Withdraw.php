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
    <title>Withdraw - 66ZZ Gaming</title>
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
                    <i class="fas fa-minus text-warning"></i>
                    Withdraw Money
                </h4>
            </div>
            <div class="card-body">
                <form id="withdrawForm">
                    <div class="balance-info mb-3">
                        <div class="current-balance">
                            <strong>Available Balance: ৳<span id="availableBalance"><?= number_format($user['balance'], 2) ?></span></strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (৳)</label>
                        <input type="number" class="form-control" name="amount" min="100" max="<?= $user['balance'] ?>" required placeholder="Enter amount">
                        <div class="form-text">Minimum: ৳100, Maximum: ৳<?= number_format($user['balance'], 2) ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Withdrawal Method</label>
                        <select class="form-control" name="withdrawal_method" required>
                            <option value="">Select Withdrawal Method</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" class="form-control" name="account_number" required placeholder="Enter account number">
                    </div>
                    <div class="withdrawal-note">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Withdrawal processing time: 1-24 hours
                        </small>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-money-bill-wave"></i>
                            Request Withdrawal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('withdrawForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const amount = formData.get('amount');
            const withdrawalMethod = formData.get('withdrawal_method');
            
            // In a real app, this would be an API call
            console.log(`Withdrawing ${amount} via ${withdrawalMethod}`);
            alert('Withdrawal request submitted successfully!');
        });
    </script>
</body>
</html>