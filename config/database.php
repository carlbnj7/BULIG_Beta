<?php
/**
 * BULIG - Database Configuration
 * Bukidnon's Unified Literacy and Intervention Gateway
 *
 * Central PDO connection used by every authentication script.
 * Replace the constants below with your actual MySQL/MariaDB credentials
 * (never commit real production credentials to source control).
 */

declare(strict_types=1);

// ---- Connection settings -------------------------------------------------
define('DB_HOST', getenv('BULIG_DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('BULIG_DB_NAME') ?: 'bulig_db');
define('DB_USER', getenv('BULIG_DB_USER') ?: 'root');
define('DB_PASS', getenv('BULIG_DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Returns a shared PDO instance (lazy singleton).
 * Throws a PDOException on failure — callers should catch it and show
 * a friendly, non-technical message to the user (never echo the raw error).
 */
function get_db_connection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false, // real prepared statements
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    return $pdo;
}
