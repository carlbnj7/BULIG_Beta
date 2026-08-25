<?php
/**
 * TEMPORARY DIAGNOSTIC — delete this file once login is working again.
 * Open this in your browser (e.g. http://localhost:8000/db_test.php)
 * to see the *real* database error instead of the generic message.
 */

declare(strict_types=1);
require_once __DIR__ . '/config/database.php';

header('Content-Type: text/plain');

echo "Testing database connection...\n\n";

try {
    $pdo = get_db_connection();
    echo "✅ Connected to the database successfully.\n\n";

    // Check the tables exist
    foreach (['pupils', 'teachers', 'pupil_progress'] as $table) {
        try {
            $count = $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
            echo "✅ Table `{$table}` exists — {$count} row(s).\n";
        } catch (Throwable $e) {
            echo "❌ Table `{$table}` problem: " . $e->getMessage() . "\n";
        }
    }

    echo "\nLooking for the demo pupil (20232223)...\n";
    $stmt = $pdo->prepare('SELECT student_id, password_hash FROM pupils WHERE student_id = :id');
    $stmt->execute(['id' => '20232223']);
    $pupil = $stmt->fetch();

    if (!$pupil) {
        echo "❌ No pupil found with student_id = 20232223. The demo row was never inserted.\n";
    } else {
        echo "✅ Found pupil. Stored hash: {$pupil['password_hash']}\n";
        $ok = password_verify('Pupil@2026', $pupil['password_hash']);
        echo $ok
            ? "✅ password_verify() PASSES for 'Pupil@2026'.\n"
            : "❌ password_verify() FAILS for 'Pupil@2026' — the hash doesn't match this password.\n";
    }

} catch (Throwable $e) {
    echo "❌ Could NOT connect to the database.\n\n";
    echo "Error message: " . $e->getMessage() . "\n\n";
    echo "This means the DB_HOST / DB_NAME / DB_USER / DB_PASS values in\n";
    echo "config/database.php don't match your actual MySQL setup.\n";
}
