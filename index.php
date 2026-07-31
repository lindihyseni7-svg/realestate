<?php
/**
 * Root front controller for shared hosting.
 *
 * This standalone entry point avoids Composer/Twig/Eloquent so the app works
 * even when free hosting file managers skip vendor internals during upload.
 */
require __DIR__ . '/standalone.php';
