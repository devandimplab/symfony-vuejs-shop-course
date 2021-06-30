<?php
namespace Deployer;

require 'recipe/symfony.php';

set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('bin/console', '{{bin/php}} {{release_path}}/bin/console');

// Project name
set('application', 'Ranked Choice');

// Project repository
set('repository', 'git@github.com:devandimplab/symfony-vuejs-shop-course.git');

set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts');

set('keep_releases', 3);

// Shared files/dirs between deploys 
add('shared_files', ['.env.local']);
add('shared_dirs', ['var/log', 'public/uploads']);

// Writable dirs by web server
add('writable_dirs', ['var', 'public/uploads']);

set('allow_anonymous_stats', false);

// Hosts
host('35.231.176.169')
    ->hostname('35.231.176.169')
    ->port(22)
    ->user('developer')
    ->identityFile('~/.ssh/id_rsa.pub')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->stage('production')
    ->set('branch', 'main')
    ->set('deploy_path', '/var/www/ranked-choice.com');

task('pwd', function (): void {
    $result = run('pwd');
    writeln("Current dir: {$result}");
});

set('env', function () {
   return [
       'APP_ENV' => 'prod',
       'DATABASE_URL' => "postgresql://rc_admin:C4hkH116LfiGqr5p@34.139.24.190:5432/ranked_choice_db?serverVersion=12.5&charset=utf8",
       'COMPOSER_MEMORY_LIMIT' => '512M'
   ];
});
    
// Tasks

task('deploy:build:assets', function () {
    /*
    run('npm install');
    run('npm run build');
    */
});

task('deploy:build_local_assets', function () {
    upload('./public/build', '{{release_path}}/public/.');
    upload('./public/bundles', '{{release_path}}/public/.');
});

after('deploy:update_code', 'deploy:build_local_assets');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy', 'success');

