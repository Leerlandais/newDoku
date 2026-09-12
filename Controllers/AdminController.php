<?php

namespace Controllers;

use Controllers\Abstract\AbstractController;
use Factory\ManagerFactory;
use model\Manager\ConnectionManager;
use model\Manager\DokuManager;
use Twig\Environment;

class AdminController extends Abstract\AbstractController
{
    private DokuManager $dokuManager;
    private ConnectionManager $connectionManager;
    public function __construct(Environment $twig, ManagerFactory $managerFactory)
    {
        parent::__construct($twig, $managerFactory);
        $this->dokuManager = $this->getManager(DokuManager::class);
        $this->connectionManager = $this->getManager(ConnectionManager::class);
    }
    public function admin() : void
    {
        global $sessionRole, $systemMessage;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        echo $this->twig->render("private/private.admin.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
        ]);
    }

    public function addSetter() : void
    {
        global $sessionRole, $systemMessage;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        $setters = $this->dokuManager->getAllSetters();

        if(isset($_POST["unset:addSetter"])) {
            // $this->verifyCsrfToken($_POST['csrf:csrf_token']);
            $cleanedData = $this->preparePostData($_POST);
            $addSetter = $this->dokuManager->addSetter($cleanedData);
            $_SESSION["systemMessage"] = $addSetter ? "Setter added" : "Setter not added";
            header("Location: ?route=addSetter");
            exit();
        }
        echo $this->twig->render("private/private.addSetter.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "setters" => $setters
        ]);
    }

    public function addVariant() : void
    {
        global $sessionRole, $systemMessage;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);

        $variants = $this->dokuManager->getAllVariants();

        if(isset($_POST["unset:addVariant"])) {
            $cleanedData = $this->preparePostData($_POST);
            $addVariant = $this->dokuManager->addVariant($cleanedData);
            $_SESSION["systemMessage"] = $addVariant ? "Variant added" : "Variant not added";
            header("Location: ?route=addVariant");
            exit();
        }
        echo $this->twig->render("private/private.addVariant.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "variants" => $variants
        ]);
    }

    public function addSudoku() : void
    {
        global $sessionRole, $systemMessage;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        $setters = $this->dokuManager->getAllSetters();
        $variants = $this->dokuManager->getAllVariants();

        if(isset($_POST["unset:addSudoku"])) {
            // $this->verifyCsrfToken($_POST['csrf:csrf_token']);
            $dokuVariants = $_POST["int:variant"];
            unset($_POST["int:variant"]);
            $cleanedData = $this->preparePostData($_POST);
            $addSoduku = $this->dokuManager->addSudoku($cleanedData);
            if($addSoduku) {
                $dokuVariants["doku_id"] = $addSoduku;
                $this->dokuManager->addSudokuVariantsToLatestPuzzle($dokuVariants);
            }
            $_SESSION["systemMessage"] = $addSoduku ? "Sudoku added" : "Sudoku not added";
            header("Location: ?route=addSudoku");
            exit();

        }
        echo $this->twig->render("private/private.addSudoku.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "setters" => $setters,
            "variants" => $variants
        ]);
    }

    public function viewLogs() : void
    {
        global $sessionRole, $systemMessage;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);

        $logs = $this->connectionManager->getAllLogs();

        echo $this->twig->render("private/private.viewLogs.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "logs" => $logs
        ]);
    }


}