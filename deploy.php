<?php /** @noinspection ALL */

/**
 * Конфигурация: /deploy-config.php в корне проекта с содержимым
  return [
      'repository_user' => '',
      'repository_password' => '',
      'test_domain' => '',
      'test_port' => ,
      'test_server_user' => '',
      'test_server_password' => '',
      'test_deploy_path' => '',
      'prod_domain' => '',
      'prod_port' => ,
      'prod_server_user' => '',
      'prod_server_password' => '',
      'prod_deploy_path' => '',
    ];
 *
 * Деплой:
 * Тестовый сервер: vendor/bin/dep deploy test
 * Продакшен: vendor/bin/dep deploy prod
 */
require 'vendor/autoload.php';
require 'vendor/deployer/deployer/recipe/yii2-app-basic.php';
$config = require('deploy-config.php');
extract($config);

// Тестовый сервер
server('test', $test_domain, $test_port)
    ->user($test_server_user)
    ->password($test_server_password)
    ->stage('test')
    ->env('deploy_path', $test_deploy_path);

// Продакшен сервер
server('prod', $prod_domain, $prod_port)
    ->user($prod_server_user)
    ->password($prod_server_password)
    ->stage('prod')
    ->env('deploy_path', $prod_deploy_path);

// Репозиторий проекта
set('repository', "https://github.com/kozlovsv/onlinetest.git");

// Расшарить файлы
set('shared_files', array_merge(get('shared_files'), [
    'config/db.php',
    'config/mail.php',
    'config/secure.ini',
]));


// Выставить права на директории
task('deploy:permissions_dirs', function () {
    cd('{{release_path}}');
    run('chmod 0777 web/assets');
    run('chmod 0777 runtime');
});

// Продакшн окружение
task('deploy:env_prod', function () {
    cd('{{release_path}}');
    run('rm web/index.php');
    run('mv web/index-prod.php web/index.php');
    run('rm yii');
    run('mv yii-prod yii');
});

// Миграция для прав/ролей
task('deploy:rbac_update', function () {
    cd('{{release_path}}');
    run('php yii migrate up --migrationPath=@yii/rbac/migrations --interactive=0');
});

// Миграция для логов
task('deploy:dblog_update', function () {
    cd('{{release_path}}');
    run('php yii migrate up --migrationPath=@yii/log/migrations/ --interactive=0');
});

// Очистка кэша
task('deploy:cache_clear', function () {
    cd('{{release_path}}');
    run('php yii cache/flush-all');
});

task('deploy:custom', [
    'deploy:permissions_dirs',
]);

task('reload:php-fpm', function () {
    run('sudo service php-fpm reload');
});

after('deploy', 'reload:php-fpm');
after('rollback', 'reload:php-fpm');

// Запустить задачи
after('deploy:update_code', 'deploy:env_prod');
after('deploy:vendors', 'deploy:rbac_update');
after('deploy:vendors', 'deploy:dblog_update');
after('deploy:run_migrations', 'deploy:custom');
after('deploy:run_migrations', 'deploy:cache_clear');
after('deploy:run_migrations', 'deploy:dblog_update');