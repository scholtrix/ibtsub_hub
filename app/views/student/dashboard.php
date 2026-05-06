<section class="py-5">
    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-3">Welcome, <?php echo htmlspecialchars($student['full_name']); ?></h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
                            <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($student['address'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Course:</strong> <?php echo htmlspecialchars($registration['course_title']); ?></p>
                            <p><strong>Registration Status:</strong> <span class="badge bg-<?php echo $registration['status'] === 'Approved' ? 'success' : ($registration['status'] === 'Rejected' ? 'danger' : 'warning'); ?>"><?php echo htmlspecialchars($registration['status']); ?></span></p>
                            <p><strong>Payment Status:</strong> <span class="badge bg-<?php echo $registration['payment_status'] === 'Paid' ? 'success' : 'secondary'; ?>"><?php echo htmlspecialchars($registration['payment_status']); ?></span></p>
                            <p><strong>Course Fee:</strong> ₦<?php echo number_format($registration['course_price']); ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="index.php?page=student_edit" class="btn btn-success me-2">Edit Profile</a>
                        <a href="index.php?page=student_logout" class="btn btn-outline-secondary">Logout</a>
                        <?php if ($registration['payment_status'] !== 'Paid' && !empty($paystackKey)): ?>
                            <button class="btn btn-primary ms-2" id="payButton" data-email="<?php echo htmlspecialchars($student['email']); ?>" data-amount="<?php echo $registration['course_price'] * 100; ?>" data-ref="REG<?php echo time(); ?>" data-registration-id="<?php echo $registration['id']; ?>">Pay with Paystack</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Passport</h5>
                    <?php if (!empty($student['passport_photo'])): ?>
                        <img src="<?php echo UPLOAD_URL . '/' . htmlspecialchars($student['passport_photo']); ?>" alt="Passport photo" class="img-fluid rounded mt-3" />
                    <?php else: ?>
                        <div class="text-muted mt-3">No photo uploaded.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($_SESSION['flash']['success'])): ?>
    <script>window.onload = function() { alert('<?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?>'); };</script>
<?php endif; ?>

<?php if (!empty($_SESSION['flash']['error'])): ?>
    <script>window.onload = function() { alert('<?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?>'); };</script>
<?php endif; ?>

<?php if (!empty($paystackKey)): ?>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const payButton = document.getElementById('payButton');
            if (!payButton) return;
            payButton.addEventListener('click', function () {
                const email = this.dataset.email;
                const amount = parseInt(this.dataset.amount, 10);
                const reference = this.dataset.ref;
                const registrationId = this.dataset.registrationId;
                if (!window.PaystackPop) {
                    return alert('Paystack is not available.');
                }
                const handler = PaystackPop.setup({
                    key: '<?php echo htmlspecialchars($paystackKey); ?>',
                    email: email,
                    amount: amount,
                    ref: reference,
                    callback: function (response) {
                        const form = document.createElement('form');
                        form.method = 'post';
                        form.action = 'index.php?page=student_verify';
                        form.innerHTML = '<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">' +
                            '<input type="hidden" name="reference" value="' + response.reference + '">' +
                            '<input type="hidden" name="registration_id" value="' + registrationId + '">';
                        document.body.appendChild(form);
                        form.submit();
                    },
                    onClose: function () {}
                });
                handler.openIframe();
            });
        });
    </script>
<?php endif; ?>
