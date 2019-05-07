<?php

namespace Deployer;

set('sparse_dirs', function() {
    return [];
});

desc('Update code');
task('deploy:update_code', function () {
    $repository = trim(get('repository'));
    $branch = get('branch');
    $git = get('bin/git');
    $gitCache = get('git_cache');
    $depth = $gitCache ? '' : '--depth 1';
    $options = [
        'tty' => get('git_tty', false),
    ];

    $sparseDirs = get('sparse_dirs');
    if (is_array($sparseDirs)) {
        $sparseDirs = [$sparseDirs];
    }
    if (empty($sparseDirs)) {
        throw new \RuntimeException("`sparse_dirs` parameter should be define");
    }

    run("mkdir -p {{release_path}}", $options);
    run("cd {{release_path}}", $options);

    run("git init 2>&1", $options);
    run("git config core.sparseCheckout true 2>&1", $options);
    run("git remote add origin $repository 2>&1", $options);
    run("git fetch origin -q $depth 2>&1", $options);

    foreach ($sparseDirs as $directory) {
        run("echo \"$directory\" > .git/info/sparse-checkout 2>&1", $options);
    }

    run("git checkout $branch 2>&1", $options);

    if (!empty($revision)) {
        run("cd {{release_path}} && $git checkout $revision");
    }
});
