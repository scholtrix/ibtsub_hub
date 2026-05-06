<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Course Catalog</h3>
    <a href="index.php?page=admin_course_edit" class="btn btn-success">Add New Course</a>
</div>
<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>Title</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($courses as $course): ?>
            <tr>
                <td><?php echo htmlspecialchars($course['title']); ?></td>
                <td>₦<?php echo number_format($course['price']); ?></td>
                <td><span class="badge bg-<?php echo $course['is_active'] ? 'success' : 'secondary'; ?>"><?php echo $course['is_active'] ? 'Active' : 'Disabled'; ?></span></td>
                <td>
                    <a href="index.php?page=admin_course_edit&id=<?php echo $course['id']; ?>" class="btn btn-sm btn-success">Edit</a>
                    <form method="post" action="index.php?page=admin_course_delete" class="d-inline-block" onsubmit="return confirm('Delete this course?');">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>" />
                        <input type="hidden" name="id" value="<?php echo $course['id']; ?>" />
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
