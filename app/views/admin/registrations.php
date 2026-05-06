<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Registration Management</h3>
    <div>
        <a href="index.php?page=admin_registrations&status=Pending" class="btn btn-outline-success btn-sm me-2">Pending</a>
        <a href="index.php?page=admin_registrations&status=Approved" class="btn btn-outline-success btn-sm me-2">Approved</a>
        <a href="index.php?page=admin_registrations&status=Rejected" class="btn btn-outline-success btn-sm">Rejected</a>
    </div>
</div>
<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Student</th>
            <th>Course</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registrations as $registration): ?>
            <tr>
                <td><?php echo htmlspecialchars($registration['student_name']); ?></td>
                <td><?php echo htmlspecialchars($registration['course_title']); ?></td>
                <td><span class="badge bg-<?php echo $registration['payment_status'] === 'Paid' ? 'success' : 'secondary'; ?>"><?php echo htmlspecialchars($registration['payment_status']); ?></span></td>
                <td><span class="badge bg-<?php echo $registration['status'] === 'Approved' ? 'success' : ($registration['status'] === 'Rejected' ? 'danger' : 'warning'); ?>"><?php echo htmlspecialchars($registration['status']); ?></span></td>
                <td>
                    <form method="post" class="d-inline-flex align-items-center">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>" />
                        <input type="hidden" name="id" value="<?php echo $registration['id']; ?>" />
                        <select name="status" class="form-select form-select-sm me-2">
                            <option value="Pending" <?php echo $registration['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Approved" <?php echo $registration['status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="Rejected" <?php echo $registration['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
