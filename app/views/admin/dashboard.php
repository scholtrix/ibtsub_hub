<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-success">
            <div class="card-body">
                <h5>Total Students</h5>
                <p class="display-6 text-success"><?php echo $students; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-success">
            <div class="card-body">
                <h5>Total Courses</h5>
                <p class="display-6 text-success"><?php echo $courses; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-success">
            <div class="card-body">
                <h5>Total Registrations</h5>
                <p class="display-6 text-success"><?php echo $registrations; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Registrations per Course</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($courseCounts as $count): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($count['title']); ?>
                            <span class="badge bg-success rounded-pill"><?php echo $count['count']; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Recent Students</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach (array_slice($latestStudents, 0, 5) as $student): ?>
                        <li class="list-group-item">
                            <strong><?php echo htmlspecialchars($student['full_name']); ?></strong><br>
                            <?php echo htmlspecialchars($student['email']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
