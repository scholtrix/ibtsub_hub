<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin | <?php echo htmlspecialchars(siteTitle()); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-B06jYtWvbKf3XNQ+nGi4BnwhIMbk3J0kgqP+4az7S7skmyC7FPafZBXYRhsL7L+I" crossorigin="anonymous" />
    <link rel="stylesheet" href="/assets/css/style.css" />
</head>
<body>
<div class="min-vh-100 bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?page=admin_dashboard">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php?page=admin_dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=admin_students">Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=admin_courses">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=admin_registrations">Registrations</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=admin_settings">Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=admin_account">Admin Account</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="index.php?page=admin_logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-4">