<?php

<<<<<<< HEAD
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
=======
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
<<<<<<< HEAD
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
=======
    | This option defines the default log channel that is utilized to write
    | messages to your logs. The value provided here should match one of
    | the channels present in the list of "channels" configured below.
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
<<<<<<< HEAD
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
=======
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Laravel
    | utilizes the Monolog PHP logging library, which includes a variety
    | of powerful log handlers and formatters that you're free to use.
    |
    | Available drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog", "custom", "stack"
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
    |
    */

    'channels' => [
<<<<<<< HEAD
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
=======

        'stack' => [
            'driver' => 'stack',
            'channels' => explode(',', env('LOG_STACK', 'single')),
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
<<<<<<< HEAD
            'level' => 'debug',
=======
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
<<<<<<< HEAD
            'level' => 'debug',
            'days' => 14,
=======
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => env('LOG_DAILY_DAYS', 14),
            'replace_placeholders' => true,
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
<<<<<<< HEAD
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
=======
            'username' => env('LOG_SLACK_USERNAME', 'Laravel Log'),
            'emoji' => env('LOG_SLACK_EMOJI', ':boom:'),
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
        ],

        'papertrail' => [
            'driver' => 'monolog',
<<<<<<< HEAD
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
=======
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
        ],

        'stderr' => [
            'driver' => 'monolog',
<<<<<<< HEAD
=======
            'level' => env('LOG_LEVEL', 'debug'),
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
<<<<<<< HEAD
=======
            'processors' => [PsrLogMessageProcessor::class],
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
        ],

        'syslog' => [
            'driver' => 'syslog',
<<<<<<< HEAD
            'level' => 'debug',
=======
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => env('LOG_SYSLOG_FACILITY', LOG_USER),
            'replace_placeholders' => true,
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
        ],

        'errorlog' => [
            'driver' => 'errorlog',
<<<<<<< HEAD
            'level' => 'debug',
        ],
=======
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
    ],

];
