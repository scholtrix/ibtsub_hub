<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Manage Students</h3>
    <div>
        <a href="index.php?page=admin_students&status=Pending" class="btn btn-outline-success btn-sm me-2">Pending</a>
        <a href="index.php?page=admin_students&status=Approved" class="btn btn-outline-success btn-sm me-2">Approved</a>
        <a href="index.php?page=admin_students&status=Rejected" class="btn btn-outline-success btn-sm">Rejected</a>
    </div>
</div>
<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Student</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
                <td><span class="badge bg-<?php echo $student['status'] === 'Approved' ? 'success' : ($student['status'] === 'Rejected' ? 'danger' : 'warning'); ?>"><?php echo htmlspecialchars($student['status']); ?></span></td>
                <td>
                    <a href="index.php?page=admin_student_edit&id=<?php echo $student['id']; ?>" class="btn btn-sm btn-success">Edit</a>
                    <form method="post" action="index.php?page=admin_student_delete" class="d-inline-block" onsubmit="return confirm('Delete this student?');">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>" />
                        <input type="hidden" name="id" value="<?php echo $student['id']; ?>" />
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
