<?php

use TurbineKreuzberg\Shared\Sentry\SentryConstants;

/**
 * PackageName Configuration
 */
$config[SentryConstants::DSN] = '';

/**
 * PackageName User Tracking
 */
$config[SentryConstants::IGNORED_EXCEPTIONS] = [
    // Example:
    ErrorException::class,
];

$config[SentryConstants::APPLICATION_VERSION] = '1.0.0';
