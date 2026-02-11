<?php

namespace SGBD\action;
abstract class action {

    protected ?string $http_method = null;

    protected ?string $hostname = null;

    protected ?string $script_name = null;

    public function __construct() {
        $this->http_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->hostname = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $this->script_name = $_SERVER['SCRIPT_NAME'] ?? '';
    }

    public function execute(): string {
        switch ($this->http_method) {
            case 'POST':
                return $this->executePost();
            case 'GET':
            default:
                return $this->executeGet();
        }
    }

    protected abstract function executeGet(): string;

    protected abstract function executePost(): string;

    public function __invoke(): string {
        return $this->execute();
    }
}
