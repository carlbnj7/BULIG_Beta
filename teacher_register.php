<?php
/**
 * BULIG — Teacher self-registration has been disabled.
 * Account hierarchy is Admin -> Teachers -> Pupils: only an Admin can
 * create a teacher account now. This page is kept (rather than deleted)
 * so any old bookmark/link lands somewhere friendly instead of a 404.
 */
declare(strict_types=1);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BULIG | Teacher Accounts</title>
<link rel="icon" href="assets/bulig-logo.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/manage.css">
</head>
<body>
<div class="scene">
    <main class="auth-card" id="authCard">
        <div class="brand">
            <img src="assets/bulig-logo.png" alt="BULIG" class="brand-logo">
            <p class="brand-tagline">Division of Bukidnon &middot; Reading Intervention Gateway</p>
        </div>
        <div class="torch-divider" aria-hidden="true"><span class="torch-flame"></span></div>

        <h1 class="reg-title">🍎 Teacher Accounts</h1>
        <p style="text-align:center; color:var(--ink-soft); font-weight:700; font-size:14px; line-height:1.6; margin:0 0 20px;">
            Teacher accounts are created by a school administrator.<br>
            Please contact your Admin to get set up.
        </p>

        <a href="index.php?type=teacher" class="btn-submit btn-teacher" style="text-decoration:none; display:flex;">
            <span class="btn-label">← Back to Sign In</span>
        </a>
    </main>
</div>
</body>
</html>
