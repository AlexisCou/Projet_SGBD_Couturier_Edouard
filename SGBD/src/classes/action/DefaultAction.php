<?php

namespace SGBD\action;

class DefaultAction extends Action {

    public function executeGet(): string {
        $page = '<h1><strong>Bienvenue sur Projet SGBD !</strong></h1>';
        return $page;
    }

    protected function executePost(): string {
        return $this->executeGet();
    }
}