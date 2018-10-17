<?php

namespace Deployer;

set('release_deploy_dir', '');

desc('Creating symlink to release');
task('deploy:symlink', function () {
    $releaseDeployDir = get('release_deploy_dir');

    if (get('use_atomic_symlink')) {
        $releasePath = '{{deploy_path}}/release';
        if ('' !== $releaseDeployDir) {
            $releasePath = sprintf('%s/%s', $releasePath, $releaseDeployDir);
        }

        run(sprintf("mv -T %s {{deploy_path}}/current", $releasePath));
    } else {
        $releasePath = '{{release_path}}';
        if ('' !== $releaseDeployDir) {
            $releasePath = sprintf('%s/%s', $releasePath, $releaseDeployDir);
        }

        run(sprintf("cd {{deploy_path}} && {{bin/symlink}} %s current", $releasePath));
        run("cd {{deploy_path}} && rm release");
    }
});
