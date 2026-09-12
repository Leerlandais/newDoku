<?php

namespace Controllers;

use Controllers\Abstract\AbstractController;
use Factory\ManagerFactory;
use model\Manager\DokuManager;
use Twig\Environment;

class GeneralController extends Abstract\AbstractController
{
    private DokuManager $dokuManager;

    public function __construct(Environment $twig, ManagerFactory $managerFactory)
    {
        parent::__construct($twig, $managerFactory);
        $this->dokuManager = $this->getManager(DokuManager::class);
    }
    public function viewAll() : void
    {
        global $sessionRole, $systemMessage;
        $puzzles = $this->dokuManager->getAllPuzzles();
        echo $this->twig->render("public/public.viewAll.html.twig", [
            "sessionRole" => $sessionRole,
            "systemMessage" => $systemMessage,
            "puzzles" => $puzzles
        ]);
    }

    public function findDoku() : void
    {
        global $sessionRole, $systemMessage;

        $setters = $this->dokuManager->getAllSetters();
        $variants = $this->dokuManager->getAllVariants();

        if(isset($_POST["unset:searchPuzzles"])) {
           header("Location: ?route=findDoku");
           exit();
        }

        echo $this->twig->render("public/public.searchPuzzles.html.twig", [
            "sessionRole" => $sessionRole,
            "systemMessage" => $systemMessage,
            "csrfToken" => $this->csrfToken,
            "setters" => $setters,
            "variants" => $variants
        ]);
    }
}