<?php
declare(strict_types=1);
session_start();

// If someone is already logged in, send them straight to their dashboard.
if (!empty($_SESSION['user_type'])) {
    header('Location: ' . ($_SESSION['user_type'] === 'teacher' ? 'teacher/dashboard.php' : 'pupil/dashboard.php'));
    exit;
}

// Which tab should be active on load? (?type=teacher after a failed teacher login, etc.)
$activeType = ($_GET['type'] ?? 'pupil') === 'teacher' ? 'teacher' : 'pupil';

// Surface a friendly error coming back from pupil_login.php / teacher_login.php
$errors = [
    'missing'  => 'Please fill in both fields before signing in.',
    'invalid'  => 'That ID and password don\'t match our records. Please try again.',
    'server'   => 'Something went wrong on our end. Please try again in a moment.',
];
$errorCode = $_GET['error'] ?? null;
$errorMessage = $errors[$errorCode] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BULIG | Sign In</title>
<meta name="description" content="Sign in to BULIG - Bukidnon's Unified Literacy and Intervention Gateway">
<link rel="icon" href="assets/bulig-logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="scene">

    <div class="floaters" aria-hidden="true">
        <span class="floater f-book">📖</span>
        <span class="floater f-star">✦</span>
        <span class="floater f-pencil">✎</span>
        <span class="floater f-star2">✦</span>
        <span class="deco d-cloud" style="top:8%; left:20%;">☁️</span>
        <span class="deco d-letter" style="top:66%; left:6%;">Aa</span>
        <span class="deco d-star" style="top:34%; right:6%;">✦</span>
        <span class="deco d-cloud" style="bottom:10%; right:22%; animation-delay:1.6s;">☁️</span>
    </div>

    <main class="auth-card" id="authCard">

        <div class="brand">
            <img src="assets/bulig-logo.png" alt="BULIG - Bukidnon's Unified Literacy and Intervention Gateway" class="brand-logo">
            <p class="brand-tagline">Division of Bukidnon &middot; Reading Intervention Gateway</p>
        </div>

        <div class="torch-divider" aria-hidden="true">
            <span class="torch-flame"></span>
        </div>

        <div class="tabs" role="tablist" aria-label="Choose account type">
            <button type="button" class="tab <?= $activeType === 'pupil' ? 'is-active' : '' ?>"
                    id="tabPupil" role="tab" aria-selected="<?= $activeType === 'pupil' ? 'true' : 'false' ?>"
                    aria-controls="panelPupil" data-target="pupil">
                <span class="tab-icon">🧒</span> Pupil
            </button>
            <button type="button" class="tab <?= $activeType === 'teacher' ? 'is-active' : '' ?>"
                    id="tabTeacher" role="tab" aria-selected="<?= $activeType === 'teacher' ? 'true' : 'false' ?>"
                    aria-controls="panelTeacher" data-target="teacher">
                <span class="tab-icon">🍎</span> Teacher
            </button>
            <span class="tab-thumb" id="tabThumb" aria-hidden="true"></span>
        </div>

        <?php if ($errorMessage): ?>
            <p class="form-alert" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES) ?></p>
        <?php endif; ?>

        <div class="panels">

            <!-- PUPIL PANEL -->
            <section class="panel <?= $activeType === 'pupil' ? 'is-active' : '' ?>" id="panelPupil" role="tabpanel" aria-labelledby="tabPupil">
                <form action="pupil_login.php" method="post" class="login-form" novalidate>
                    <p class="panel-greeting">Hi there! Ready to read and play today?</p>

                    <label class="field">
                        <span class="field-label">Student ID</span>
                        <span class="field-control">
                            <span class="field-icon" aria-hidden="true">🆔</span>
                            <input type="text" name="student_id" placeholder="e.g. 20232223"
                                   inputmode="numeric" pattern="[0-9]*" maxlength="15"
                                   autocomplete="username" required class="js-numeric">
                        </span>
                        <span class="field-hint">Numbers only — no letters or dashes</span>
                    </label>

                    <label class="field">
                        <span class="field-label">Password</span>
                        <span class="field-control">
                            <span class="field-icon" aria-hidden="true">🔒</span>
                            <input type="password" name="password" placeholder="Enter your password"
                                   autocomplete="current-password" required class="js-password">
                            <button type="button" class="toggle-pw" aria-label="Show password">👁️</button>
                        </span>
                    </label>

                    <button type="submit" class="btn-submit btn-pupil">
                        <span class="btn-label">Let's Go!</span>
                        <span class="btn-spinner" aria-hidden="true"></span>
                    </button>
                </form>
            </section>

            <!-- TEACHER PANEL -->
            <section class="panel <?= $activeType === 'teacher' ? 'is-active' : '' ?>" id="panelTeacher" role="tabpanel" aria-labelledby="tabTeacher">
                <form action="teacher_login.php" method="post" class="login-form" novalidate>
                    <p class="panel-greeting">Welcome back! Sign in to your class dashboard.</p>

                    <label class="field">
                        <span class="field-label">Teacher ID</span>
                        <span class="field-control">
                            <span class="field-icon" aria-hidden="true">🆔</span>
                            <input type="text" name="teacher_id" placeholder="e.g. T-2026-045"
                                   autocomplete="username" required>
                        </span>
                    </label>

                    <label class="field">
                        <span class="field-label">Password</span>
                        <span class="field-control">
                            <span class="field-icon" aria-hidden="true">🔒</span>
                            <input type="password" name="password" placeholder="Enter your password"
                                   autocomplete="current-password" required class="js-password">
                            <button type="button" class="toggle-pw" aria-label="Show password">👁️</button>
                        </span>
                    </label>

                    <button type="submit" class="btn-submit btn-teacher">
                        <span class="btn-label">Sign In</span>
                        <span class="btn-spinner" aria-hidden="true"></span>
                    </button>
                </form>
            </section>

        </div>

    </main>

</div>

<footer class="site-footer">
    <img src="assets/footer.png" alt="Division of Bukidnon and BULIG partner seals" class="footer-strip">
</footer>

<script src="js/login.js"></script>
</body>
</html>
