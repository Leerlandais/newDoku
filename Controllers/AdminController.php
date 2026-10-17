<?php

namespace Controllers;

use Controllers\Abstract\AbstractController;
use Factory\ManagerFactory;
use model\Manager\AdminManager;
use model\Manager\ConnectionManager;
use model\Manager\DokuManager;
use Twig\Environment;

class AdminController extends Abstract\AbstractController
{
    private DokuManager $dokuManager;
    private ConnectionManager $connectionManager;
    private AdminManager $adminManager;

    public function __construct(Environment $twig, ManagerFactory $managerFactory)
    {
        parent::__construct($twig, $managerFactory);
        $this->dokuManager = $this->getManager(DokuManager::class);
        $this->connectionManager = $this->getManager(ConnectionManager::class);
        $this->adminManager = $this->getManager(AdminManager::class);
    }

    public function admin(): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        echo $this->twig->render("private/private.admin.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "sessionExpiresAt" => $sessionExpiresAt
        ]);
    }

    public function addSetter(): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        $setters = $this->dokuManager->getAllSetters();

        if (isset($_POST["unset:addSetter"])) {
            $this->verifyCsrfToken($_POST['csrf:csrf_token']);
            $cleanedData = $this->preparePostData($_POST);
            $addSetter = $this->adminManager->addSetter($cleanedData);
          //  $_SESSION["systemMessage"] = $addSetter ? "Setter added" : "Setter not added";
            header("Location: ?route=addSudoku");
            exit();
        }
        echo $this->twig->render("private/private.addSetter.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "setters" => $setters,
            "sessionExpiresAt" => $sessionExpiresAt
        ]);
    }

    public function addVariant(): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);

        $variants = $this->dokuManager->getAllVariants();

        if (isset($_POST["unset:addVariant"])) {
            $this->verifyCsrfToken($_POST['csrf:csrf_token']);
            $cleanedData = $this->preparePostData($_POST);
            $addVariant = $this->adminManager->addVariant($cleanedData);
            $_SESSION["systemMessage"] = $addVariant ? "Variant added" : "Variant not added";
            header("Location: ?route=addSudoku");
            exit();
        }
        echo $this->twig->render("private/private.addVariant.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "variants" => $variants,
            "sessionExpiresAt" => $sessionExpiresAt
        ]);
    }

    public function addSudoku(): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        $setters = $this->dokuManager->getAllSetters();
        $variants = $this->dokuManager->getAllVariants();

        if (isset($_POST["unset:addSudoku"])) {
            $this->verifyCsrfToken($_POST['csrf:csrf_token']);
            $dokuVariants = $_POST["int:variant"];
            unset($_POST["int:variant"]);
            $cleanedData = $this->preparePostData($_POST);
            $addSoduku = $this->adminManager->addSudoku($cleanedData);
            if ($addSoduku) {
                $this->adminManager->incremementSetterCount($cleanedData["doku_setter_id"]);
                $dokuVariants["doku_id"] = $addSoduku;
                $this->adminManager->addSudokuVariantsToLatestPuzzle($dokuVariants);
            }
         //   $_SESSION["systemMessage"] = $addSoduku ? "Sudoku added" : "Sudoku not added";
            header("Location: ?route=showDetails&id=$addSoduku");
            exit();

        }
        echo $this->twig->render("private/private.addSudoku.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "setters" => $setters,
            "variants" => $variants,
            "sessionExpiresAt" => $sessionExpiresAt
        ]);
    }

    public function viewLogs(): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);

        $logs = $this->connectionManager->getAllLogs();
        foreach ($logs as &$log) {
            if (isset(explode(":", $log->getLogPageVisited())[1])) {

                    $log->setLogUrl(intval(explode(":", $log->getLogPageVisited())[1]));
            }else {
                $log->setLogUrl(null);
            }
        }
        $logCount = count($logs);
        echo $this->twig->render("private/private.viewLogs.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "logs" => $logs,
            "sessionExpiresAt" => $sessionExpiresAt,
            "logCount" => $logCount
        ]);
    }

    public function editVariant(): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        $variants = $this->dokuManager->getAllVariants();

        if (isset($_POST["unset:editVariant"])) {
            $this->verifyCsrfToken($_POST['csrf:csrf_token']);
            $cleanedData = $this->preparePostData($_POST);
            $editVariant = $this->adminManager->editVariant($cleanedData);
            $_SESSION["systemMessage"] = $editVariant ? "Variant edited" : "Variant not edited";
            header("Location: ?route=editVariant");
            exit();
        }
        echo $this->twig->render("private/private.editVariant.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "variants" => $variants,
            "sessionExpiresAt" => $sessionExpiresAt
        ]);
    }

    public function showLogs(array $getParams): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        $logs = $this->connectionManager->getAllLogs($this->simpleTrim($getParams["ip"]));
        $logCount = count($logs);

        echo $this->twig->render("private/private.viewLogs.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "logs" => $logs,
            "sessionExpiresAt" => $sessionExpiresAt,
            "logCount" => $logCount
        ]);
    }

}