<div class="card shadow-sm">
    <div class="card-body">
        <h3>Website Settings</h3>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>" />
            <div class="mb-3">
                <label class="form-label">Site Title</label>
                <input type="text" name="site_title" class="form-control" value="<?php echo htmlspecialchars($siteTitle); ?>" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Site Tagline</label>
                <input type="text" name="site_tagline" class="form-control" value="<?php echo htmlspecialchars($siteTagline); ?>" />
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Email</label>
                <input type="email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($contactEmail); ?>" required />
            </div>
            <hr />
            <div class="mb-3">
                <label class="form-label">Paystack Public Key</label>
                <input type="text" name="paystack_public_key" class="form-control" value="<?php echo htmlspecialchars($paystackPublic); ?>" />
            </div>
            <div class="mb-3">
                <label class="form-label">Paystack Secret Key</label>
                <input type="password" name="paystack_secret_key" class="form-control" value="<?php echo htmlspecialchars($paystackSecret); ?>" />
                <div class="form-text">These keys allow Paystack payment verification from the admin portal.</div>
            </div>
            <button class="btn btn-success">Save Settings</button>
        </form>
    </div>
</div>
