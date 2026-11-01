<?php

namespace Controllers;

use Controllers\Abstract\AbstractController;
use Factory\ManagerFactory;
use model\Manager\AdminManager;
use model\Manager\ConnectionManager;
use model\Manager\DokuManager;
use model\Manager\SecurityManager;
use Twig\Environment;

class AdminController extends Abstract\AbstractController
{
    private DokuManager $dokuManager;
    private ConnectionManager $connectionManager;
    private AdminManager $adminManager;
    private SecurityManager $securityManager;

    public function __construct(Environment $twig, ManagerFactory $managerFactory)
    {
        parent::__construct($twig, $managerFactory);
        $this->dokuManager = $this->getManager(DokuManager::class);
        $this->connectionManager = $this->getManager(ConnectionManager::class);
        $this->adminManager = $this->getManager(AdminManager::class);
        $this->securityManager = $this->getManager(SecurityManager::class);
    }

    public function admin(): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->connectionManager->logConnection($ip, "Admin");
        $this->checkIpForBan($ip);
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
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->checkIpForBan($ip);
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
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->checkIpForBan($ip);
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
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->checkIpForBan($ip);
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
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->checkIpForBan($ip);
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
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->checkIpForBan($ip);
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
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->checkIpForBan($ip);
        $logs = $this->connectionManager->getAllLogs($this->simpleTrim($getParams["ip"]));
        $logCount = count($logs);

        if(isset($_POST["unset:banIp"])) {
            $cleanedData = $this->preparePostData($_POST);
         //   $this->verifyCsrfToken($_POST['csrf:csrf_token']);
            $_SESSION["systemMessage"] = $this->securityManager->banIp($cleanedData) ? "Ip banned" : "Ip not banned";
            header("Location: ?route=viewLogs");
            exit();
        }
        echo $this->twig->render("private/private.viewLogs.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "logs" => $logs,
            "sessionExpiresAt" => $sessionExpiresAt,
            "logCount" => $logCount,
            "banButton" => true,
            "csrfToken" => $this->csrfToken,
        ]);
    }

    public function getBanList(): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->checkIpForBan($ip);
        $bannedIps = $this->securityManager->getBannedIps();

        if(isset($_POST["unset:unBanIp"])) {
            $this->verifyCsrfToken($_POST['csrf:csrf_token']);
            $cleanedData = $this->preparePostData($_POST);
            $unban = $this->securityManager->unBanIp($cleanedData["ban_ip"]);
            $_SESSION["systemMessage"] = $unban ? "Ip unbanned" : "Ip not unbanned";
            header("Location: ?route=viewBanList");
            exit();
        }
        echo $this->twig->render("private/private.banList.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "bannedIps" => $bannedIps,
            "sessionExpiresAt" => $sessionExpiresAt,
            "csrfToken" => $this->csrfToken,
        ]);
    }

    public function editDoku(array $getParams): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        $this->checkPermissions("ROLE_ADMIN", $sessionRole);
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->checkIpForBan($ip);
        if(!isset($getParams["id"]) || !is_numeric($getParams["id"]))
        {
            header("Location: ?route=home");
            exit();
        }
        $id = $this->intClean($getParams["id"]);
        $puzzleNow = $this->dokuManager->getPuzzleById($id);
        $setters = $this->dokuManager->getAllSetters();
        $variants = $this->dokuManager->getAllVariants();

        if (isset($_POST["unset:editSudoku"])) {
           // $this->verifyCsrfToken($_POST['csrf:csrf_token']);
            $dokuVariants = $_POST["int:variant"];
            unset($_POST["int:variant"]);
            $cleanedData = $this->preparePostData($_POST);
            $puzzleId = $cleanedData["doku_id"];
                $dokuVariants["doku_id"] = $puzzleId;
            $editSoduku = $this->adminManager->editSudoku($cleanedData);
            if ($editSoduku) {
                $this->adminManager->removeCurrentVariantsFromPuzzle($puzzleId);
                $this->adminManager->addSudokuVariantsToLatestPuzzle($dokuVariants);
            }
               $_SESSION["systemMessage"] = $editSoduku ? "Sudoku edited" : "Sudoku not edited";
            header("Location: ?route=showDetails&id=$puzzleId");
            exit();
        }

        echo $this->twig->render("private/private.editDoku.html.twig", [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "puzzleNow" => $puzzleNow,
            "setters" => $setters,
            "variants" => $variants,
            "sessionExpiresAt" => $sessionExpiresAt,
        ]);
    }

}