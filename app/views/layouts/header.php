<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars(siteTitle()); ?> | <?php echo isset($settings['site_tagline']) ? htmlspecialchars($settings['site_tagline']) : 'Training Hub'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-B06jYtWvbKf3XNQ+nGi4BnwhIMbk3J0kgqP+4az7S7skmyC7FPafZBXYRhsL7L+I" crossorigin="anonymous" />
    <link rel="stylesheet" href="/assets/css/style.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=home"><?php echo htmlspecialchars(siteTitle()); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?page=home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=courses">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=contact">Contact</a></li>
                <li class="nav-item"><a class="nav-link btn btn-light btn-sm text-success ms-2" href="index.php?page=apply">Apply Now</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="py-5">
    <div class="container">