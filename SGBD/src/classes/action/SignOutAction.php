<?php

namespace SGBD\action;

class SignOutAction extends Action
{
    protected function executeGet(): string {
        session_destroy();
        header('Location: index.php?action=default');
        return "Déconnexion réussie";
    }

    protected function executePost(): string {
        return $this->executeGet();
    }
}