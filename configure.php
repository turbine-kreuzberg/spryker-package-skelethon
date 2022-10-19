#!/usr/bin/env php
<?php

function ask(string $question, string $default = ''): string
{
    $answer = readline($question.($default ? " ({$default})" : null).': ');

    if (! $answer) {
        return $default;
    }

    return $answer;
}

function confirm(string $question, bool $default = false): bool
{
    $answer = ask($question.' ('.($default ? 'Y/n' : 'y/N').')');

    if (! $answer) {
        return $default;
    }

    return strtolower($answer) === 'y';
}

function writeln(string $line): void
{
    echo $line.PHP_EOL;
}

function run(string $command): string
{
    return trim((string) shell_exec($command));
}

function str_after(string $subject, string $search): string
{
    $pos = strrpos($subject, $search);

    if ($pos === false) {
        return $subject;
    }

    return substr($subject, $pos + strlen($search));
}

function slugify(string $subject): string
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $subject));
}

function title_case(string $subject): string
{
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $subject)));
}

function title_snake(string $subject, string $replace = '_'): string
{
    return str_replace(['-', '_'], $replace, $subject);
}

function replace_in_file(string $file, array $replacements): void
{
    $contents = file_get_contents($file);

    file_put_contents(
        $file,
        str_replace(
            array_keys($replacements),
            array_values($replacements),
            $contents
        )
    );
}

function remove_prefix(string $prefix, string $content): string
{
    if (str_starts_with($content, $prefix)) {
        return substr($content, strlen($prefix));
    }

    return $content;
}

function remove_readme_paragraphs(string $file): void
{
    $contents = file_get_contents($file);

    file_put_contents(
        $file,
        preg_replace('/<!--delete-->.*<!--\/delete-->/s', '', $contents) ?: $contents
    );
}

function safeUnlink(string $filename)
{
    if (file_exists($filename) && is_file($filename)) {
        unlink($filename);
    }
}

function determineSeparator(string $path): string
{
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}

function replaceForWindows(): array
{
    return preg_split('/\\r\\n|\\r|\\n/', run('dir /S /B * | findstr /v /i .git\ | findstr /v /i vendor | findstr /v /i '.basename(__FILE__).' | findstr /r /i /M /F:/ ":author :vendor :package VendorName skeleton migration_table_name vendor_name vendor_slug author@domain.com"'));
}

function replaceForAllOtherOSes(): array
{
    return explode(PHP_EOL, run('grep -E -r -l -i ":author|:vendor|:package|VendorName|skeleton|migration_table_name|vendor_name|vendor_slug|author@domain.com" --exclude-dir=vendor ./* ./.github/* | grep -v '.basename(__FILE__)));
}

$gitName = run('git config user.name');
$authorName = ask('Author name', $gitName);

$gitEmail = run('git config user.email');
$authorEmail = ask('Author email', $gitEmail);

$usernameGuess = explode(':', run('git config remote.origin.url'))[1] ?? '';
$usernameGuess = dirname($usernameGuess);
$usernameGuess = basename($usernameGuess);
$authorUsername = ask('Author username', $usernameGuess);

$vendorName = ask('Vendor name', $authorUsername);
$vendorSlug = slugify($vendorName);
$vendorNamespace = title_case($vendorName);
$vendorNamespace = ask('Vendor namespace', $vendorNamespace);

$currentDirectory = getcwd();
$folderName = basename($currentDirectory);

$packageName = ask('Package name', title_case($folderName));
var_dump($packageName);
$packageSlug = slugify($packageName);
$packageSlugWithoutPrefix = remove_prefix('spryker-', $packageSlug);

$description = ask('Package description', "This is my package {$packageSlug}");

$useDependabot = confirm('Enable Dependabot?', true);

writeln('------');
writeln("Author     : {$authorName} ({$authorUsername}, {$authorEmail})");
writeln("Vendor     : {$vendorName} ({$vendorSlug})");
writeln("Package    : {$packageSlug} <{$description}>");
writeln("Package Name  : {$packageName}");
writeln('---');
writeln('Packages & Utilities');
writeln('Use Dependabot       : '.($useDependabot ? 'yes' : 'no'));
writeln('------');

writeln('This script will replace the above values in all relevant files in the project directory.');

if (! confirm('Modify files?', true)) {
    exit(1);
}

$renames = [
    // Client
    determineSeparator('src/VendorName/Client/PackageName/PackageNameClient.php') => determineSeparator(sprintf('src/VendorName/Client/PackageName/%sClient.php', $packageName)),
    determineSeparator('src/VendorName/Client/PackageName/PackageNameClientInterface.php') => determineSeparator(sprintf('src/VendorName/Client/PackageName/%sClientInterface.php', $packageName)),
    determineSeparator('src/VendorName/Client/PackageName') => determineSeparator(sprintf('src/VendorName/Client/%s/', $packageName)),

    // Service
    determineSeparator('src/VendorName/Service/PackageName/PackageNameService.php') => determineSeparator(sprintf('src/VendorName/Service/PackageName/%sService.php', $packageName)),
    determineSeparator('src/VendorName/Service/PackageName/PackageNameServiceInterface.php') => determineSeparator(sprintf('src/VendorName/Service/PackageName/%sServiceInterface.php', $packageName)),
    determineSeparator('src/VendorName/Service/PackageName') => determineSeparator(sprintf('src/VendorName/Service/%s/', $packageName)),

    // Shared
    determineSeparator('src/VendorName/Shared/PackageName/Transfer/package_name.transfer.xml') => determineSeparator(sprintf('src/VendorName/Shared/PackageName/Transfer/%s.transfer.xml', title_snake($packageSlug))),
    determineSeparator('src/VendorName/Shared/PackageName') => determineSeparator(sprintf('src/VendorName/Shared/%s/', $packageName)),

    // Zed
    determineSeparator('src/VendorName/Zed/PackageName/Business/PackageNameFacade.php') => determineSeparator(sprintf('src/VendorName/Zed/PackageName/Business/%sFacade.php', $packageName)),
    determineSeparator('src/VendorName/Zed/PackageName/Business/PackageNameFacadeInterface.php') => determineSeparator(sprintf('src/VendorName/Zed/PackageName/Business/%sFacadeInterface.php', $packageName)),
    determineSeparator('src/VendorName/Zed/PackageName') => determineSeparator(sprintf('src/VendorName/Zed/%s/', $packageName)),

    // Source
    determineSeparator('src/VendorName/') => determineSeparator(sprintf('src/%s/', $vendorNamespace)),
];

foreach ($renames as $old => $new) {
    rename($old, $new);
}

$files = (str_starts_with(strtoupper(PHP_OS), 'WIN') ? replaceForWindows() : replaceForAllOtherOSes());

foreach ($files as $file) {
    replace_in_file($file, [
        ':author_name' => $authorName,
        ':author_username' => $authorUsername,
        'author@domain.com' => $authorEmail,

        ':vendor_name' => $vendorName,
        ':vendor_slug' => $vendorSlug,
        'VendorName' => $vendorNamespace,

        ':package_name' => $packageName,
        'PackageName' => $packageName,
        ':package_description' => $description,
        ':package_slug' => $packageSlug,
        ':package_slug_without_prefix' => $packageSlugWithoutPrefix,
    ]);

    match (true) {
        str_contains($file, 'README.md') => remove_readme_paragraphs($file),
        default => [],
    };
}



if (! $useDependabot) {
    safeUnlink(__DIR__.'/.github/dependabot.yml');
    // safeUnlink(__DIR__.'/.github/workflows/dependabot-auto-merge.yml');
}

confirm('Execute `composer install` and run tests?') && run('composer install && composer test');

confirm('Let this script delete itself?', true) && unlink(__FILE__);