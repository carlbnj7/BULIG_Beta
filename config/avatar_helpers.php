<?php
/**
 * BULIG — Shared profile-picture helpers, used by pupil/teacher/admin
 * profile pages, upload handlers, and every dashboard header/sidebar
 * that shows a person's avatar.
 *
 * Storage: uploads/avatars/<file>, filename stored in `avatar_file`
 * (pupils / teachers / admins). Never trusts the browser's filename or
 * declared MIME type — validates real image bytes with getimagesize()
 * and re-encodes to a fresh JPEG, so nothing except genuine image data
 * ever gets written to disk (this is also why uploads/avatars/ has its
 * own .htaccess refusing to execute anything as a script — belt and
 * suspenders).
 */
declare(strict_types=1);

const BULIG_AVATAR_MAX_BYTES = 3 * 1024 * 1024; // 3 MB
const BULIG_AVATAR_DIR       = __DIR__ . '/../uploads/avatars';
const BULIG_AVATAR_MAX_DIM   = 512; // longest side, px — plenty for a small circular avatar

/**
 * Renders the little circular avatar used everywhere (sidebar, dashboard
 * header, profile page): a real photo if one's been uploaded, otherwise
 * the existing letter-initial badge — so every page keeps working for
 * accounts that never uploaded a photo.
 *
 * @param string|null $avatarFile  Value of the `avatar_file` DB column.
 * @param string      $roleClass   'a-pupil' | 'a-teacher' | 'a-admin' (existing CSS).
 * @param string      $initial     Single-letter fallback.
 * @param string      $pathPrefix  '../' from a page one folder deep, '' from the root.
 * @param string      $extraClass  Optional extra class, e.g. 'avatar-lg'.
 */
function bulig_avatar_html(?string $avatarFile, string $roleClass, string $initial, string $pathPrefix = '../', string $extraClass = ''): string
{
    $cls = trim('avatar ' . $roleClass . ' ' . $extraClass);
    if ($avatarFile) {
        $src = $pathPrefix . 'uploads/avatars/' . rawurlencode($avatarFile);
        return '<img src="' . htmlspecialchars($src, ENT_QUOTES) . '" alt="Profile photo" class="' . htmlspecialchars($cls, ENT_QUOTES) . ' avatar-photo">';
    }
    return '<div class="' . htmlspecialchars($cls, ENT_QUOTES) . '">' . htmlspecialchars($initial, ENT_QUOTES) . '</div>';
}

/**
 * Validates + saves an uploaded avatar from $_FILES['avatar'].
 * Returns the new filename on success, or throws a RuntimeException with
 * a human-readable message on failure (caller redirects with it).
 */
function bulig_save_avatar_upload(array $file, string $prefix, int $ownerId): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        throw new RuntimeException('Please choose an image first.');
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed — please try again.');
    }
    if ($file['size'] > BULIG_AVATAR_MAX_BYTES) {
        throw new RuntimeException('That image is too large (max 3 MB).');
    }

    // Validate it's a real image by decoding it, not by trusting the
    // extension or the browser-supplied Content-Type.
    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        throw new RuntimeException('That file is not a valid image.');
    }
    [$width, $height, $type] = $info;

    $image = match ($type) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($file['tmp_name']),
        IMAGETYPE_PNG  => imagecreatefrompng($file['tmp_name']),
        IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($file['tmp_name']) : false,
        default        => false,
    };
    if ($image === false) {
        throw new RuntimeException('Please upload a JPG, PNG, or WEBP image.');
    }

    // Downscale to a sane max dimension and always re-encode as JPEG —
    // this both normalizes the file and strips anything but pixel data.
    $scale = min(1.0, BULIG_AVATAR_MAX_DIM / max($width, $height));
    $newW  = max(1, (int) round($width * $scale));
    $newH  = max(1, (int) round($height * $scale));

    $canvas = imagecreatetruecolor($newW, $newH);
    $white  = imagecolorallocate($canvas, 255, 255, 255);
    imagefill($canvas, 0, 0, $white); // flatten any transparency onto white
    imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newW, $newH, $width, $height);
    imagedestroy($image);

    if (!is_dir(BULIG_AVATAR_DIR)) {
        mkdir(BULIG_AVATAR_DIR, 0755, true);
    }

    $filename = $prefix . '_' . $ownerId . '_' . time() . '.jpg';
    $ok = imagejpeg($canvas, BULIG_AVATAR_DIR . '/' . $filename, 85);
    imagedestroy($canvas);

    if (!$ok) {
        throw new RuntimeException('Could not save the image — please try again.');
    }
    return $filename;
}

/** Deletes a previous avatar file from disk, if any (best-effort). */
function bulig_delete_avatar_file(?string $avatarFile): void
{
    if (!$avatarFile) {
        return;
    }
    $path = BULIG_AVATAR_DIR . '/' . basename($avatarFile);
    if (is_file($path)) {
        @unlink($path);
    }
}
