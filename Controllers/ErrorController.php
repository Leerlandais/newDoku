<?php

namespace Controllers;

use Controllers\Abstract\AbstractController;

class ErrorController extends Abstract\AbstractController
{
    public function error404() : void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        echo $this->twig->render("err404.html.twig", [
            "sessionRole" => $sessionRole,
            "systemMessage" => $systemMessage,
            "sessionExpiresAt" => $sessionExpiresAt
        ]);
    }
}