<div class="card shadow-sm">
    <div class="card-body">
        <h3><?php echo $course ? 'Edit Course' : 'Add New Course'; ?></h3>
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
                <label class="form-label">Course Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($course['title'] ?? ''); ?>" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($course['slug'] ?? ''); ?>" placeholder="full-stack-web-development" />
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($course['price'] ?? ''); ?>" required />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Duration</label>
                    <input type="text" name="duration" class="form-control" value="<?php echo htmlspecialchars($course['duration'] ?? ''); ?>" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Active</label>
                    <select name="is_active" class="form-select">
                        <option value="1" <?php echo (!isset($course['is_active']) || $course['is_active'] == 1) ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo isset($course['is_active']) && $course['is_active'] == 0 ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success"><?php echo $course ? 'Update Course' : 'Create Course'; ?></button>
                <a href="index.php?page=admin_courses" class="btn btn-outline-secondary ms-2">Back</a>
            </div>
        </form>
    </div>
</div>
