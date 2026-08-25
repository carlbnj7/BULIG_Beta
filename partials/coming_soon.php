<?php
/**
 * Reusable "coming soon" panel for nav destinations that are real pages
 * but don't have their feature built yet. Never a dead link — always a
 * genuine page explaining what's next.
 *
 * Expects: $csIcon, $csTitle, $csMessage
 */
?>
<div class="coming-soon">
    <span class="cs-icon"><?= $csIcon ?? '🛠️' ?></span>
    <h1><?= htmlspecialchars($csTitle ?? 'Coming Soon', ENT_QUOTES) ?></h1>
    <p><?= htmlspecialchars($csMessage ?? 'This feature is being built.', ENT_QUOTES) ?></p>
</div>
