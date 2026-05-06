<section class="py-5 text-white bg-success rounded-3 shadow-sm">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold">Welcome to <?php echo htmlspecialchars(siteTitle()); ?></h1>
                <p class="lead mb-4"><?php echo htmlspecialchars(siteTagline()); ?></p>
                <p>Ibtsub Hub is a practical, physical training center for computer literacy and web development. Our classrooms and mentorship model are built for students who want hands-on experience.</p>
                <a href="index.php?page=apply" class="btn btn-light btn-lg text-success">Apply Now</a>
            </div>
            <div class="col-lg-5 text-center">
                <img src="/assets/img/hero.svg" alt="Training center" class="img-fluid rounded shadow" />
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="row g-4">
        <?php foreach ($courses as $course): ?>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                        <p class="fw-bold">Price: ₦<?php echo number_format($course['price']); ?></p>
                        <a href="index.php?page=courses" class="btn btn-success btn-sm">View Courses</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
