<?php

namespace Woeplanet\Resolver;

class Config {
    private $config = NULL;

    public function __construct() {
        $defaults = [
            'displayErrorDetails' => true,
            'logger' => [
                'name' => 'woeplanet-id-service',
                'path' => dirname(__FILE__) . '/../../logs/id-' . date('Y-m-d') . '.log',
                'log.severity' => \Monolog\Logger::DEBUG
            ]
        ];

        $site_settings = [];
        switch ($_SERVER['HTTP_HOST']) {
            case 'id.woeplanet.org':
                $site_settings = [
                    'logger' => [
                        'log.severity' => \Monolog\Logger::INFO
                    ],
                    'root-uri' => 'https://data.woeplanet.org'
                ];
                break;

            case 'id.woeplanet.test':
                $site_settings = [
                    'logger' => [
                        'log.severity' => \Monolog\Logger::DEBUG
                    ],
                    'root-uri' => 'http://data.woeplanet.test'
                ];
                break;

            default:
                break;
        }

        $this->config = array_replace_recursive($defaults, $site_settings);

        $site_config_file = dirname(__FILE__) . '/../../site-config.php';
        if (is_readable($site_config_file)) {
            $site_config = require_once($site_config_file);
            $this->config = array_replace_recursive($this->config, $site_config);
        }
    }

    public function get_config() {
        return $this->config;
    }
}

?>
