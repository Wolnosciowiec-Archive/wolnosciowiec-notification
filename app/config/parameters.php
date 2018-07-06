<?php declare(strict_types=1);

if (!function_exists('envOrDefault')) {
    function envOrDefault (string $envName, $default = '')
    {
        if (isset($_ENV[$envName])) {
            return $_ENV[$envName];
        }

        return trim(isset($_SERVER[$envName]) ? $_SERVER[$envName] : $default, '"\'');
    }
}

$parameters = [
    'database_driver' => envOrDefault('NOTIFY_DB_DRIVER', 'pdo_sqlite'),
    'database_host'   => envOrDefault('NOTIFY_DB_HOST', '127.0.0.1'),
    'database_port'   => envOrDefault('NOTIFY_DB_PORT', '~'),
    'database_name'   => envOrDefault('NOTIFY_DB_NAME', 'notification'),
    'database_user'   => envOrDefault('NOTIFY_DB_USER', 'root'),
    'database_password'   => envOrDefault('NOTIFY_DB_PASSWORD', ''),
    'database_path'   => envOrDefault('NOTIFY_DB_PATH', '%kernel.root_dir%/notification.sqlite3'),
    'mailer_transport' => envOrDefault('NOTIFY_MAILER_TRANSPORT', 'smtp'),
    'mailer_host'      => envOrDefault('NOTIFY_MAILER_HOST', 'mail'),
    'mailer_port'      => envOrDefault('NOTIFY_MAILER_PORT', 25),
    'mailer_user'      => envOrDefault('NOTIFY_MAILER_USER', 'root@localhost'),
    'mailer_password'  => envOrDefault('NOTIFY_MAILER_PASSWD', '~'),
    'mailer_encryption' => envOrDefault('NOTIFY_MAILER_ENCRYPTION', 'tls'),
    'mailer_sender_address' => envOrDefault('NOTIFY_MAILER_SENDER_ADDRESS', 'anarchist-notifier@localhost'),
    'secret'                => envOrDefault('NOTIFY_SECRET', 'ThisTokenIsNotSoSecretChangeIt'),
    'default_api_user'      => envOrDefault('NOTIFY_DEFAULT_API_USER', 'apiuser'),
    'default_api_key'       => envOrDefault('NOTIFY_DEFAULT_API_KEY', '')
];

foreach ($parameters as $name => $value) {
    $container->setParameter($name, $value);
}
