<section class="py-5">
    <div class="mb-4">
        <h2>Available Courses</h2>
        <p class="text-muted">Choose one of our in-person training programs and apply today.</p>
    </div>
    <div class="row g-4">
        <?php foreach ($courses as $course): ?>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                        <p class="mb-1"><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></p>
                        <p class="fw-bold mb-3">₦<?php echo number_format($course['price']); ?></p>
                        <a href="index.php?page=apply&course_id=<?php echo $course['id']; ?>" class="btn btn-success">Apply for this course</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
