<?php

namespace Controllers;


use Factory\ManagerFactory;
use model\Manager\ConnectionManager;
use model\Manager\DokuManager;
use Twig\Environment;


class ConnectionController extends Abstract\AbstractController
{
    private ConnectionManager $connectionManager;
    private DokuManager $dokuManager;

    public function __construct(Environment $twig, ManagerFactory $managerFactory)
    {
        /*
         * Instantiates Managers needed for this Controller
         * Avoids instantiation of unneeded Managers
         */
        parent::__construct($twig, $managerFactory);
        $this->connectionManager = $this->getManager(ConnectionManager::class);
        $this->dokuManager = $this->getManager(DokuManager::class);
    }

    public function logout()
    {
        $this->connectionManager->logoutUser();
        header("Location: ./");
        exit;
    }

    public function index(?array $getParams): void
    {
        global $systemMessage, $sessionRole, $sessionExpiresAt;
            $ip = $_SERVER['REMOTE_ADDR'];
            $this->checkIpForBan($ip);
        if (!isset($_SESSION["visit_logged"])) {
            $ip = $ip == "::1" ? "localhost" : $ip;
            $this->checkIpForBan($ip);
            if ($ip !== MAIN_IP) {
                $this->connectionManager->logConnection($ip, "Home");
                $_SESSION["visit_logged"] = true;
            }
        }
        $solvedPuzzles = $this->dokuManager->getLatestPuzzles();
        $totalTime = $this->dokuManager->getTotalTime(true);
        $currentDateOffset = $_GET["date"] ?? 0;
        echo $this->twig->render('public/public.index.html.twig', [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "puzzles" => $solvedPuzzles,
            "location" => "home",
            "totalTime" => $totalTime,
            "sessionExpiresAt" => $sessionExpiresAt,
            "currentDateOffset" => $currentDateOffset
        ]);
    }


    public function login(): void
    {
        global $systemMessage, $sessionRole, $sessionExpiresAt;
        if (!empty($sessionRole)) {
            header("Location: ./");
            exit();
        }
        $ip = $_SERVER['REMOTE_ADDR'];

        $ip = $ip == "::1" ? "localhost" : $ip;
        $this->checkIpForBan($ip);
        if ($ip !== MAIN_IP) {
            $this->connectionManager->logConnection($ip, "Login");
        }
        if ($this->connectionManager->checkForBanned)
            if (isset($_POST['unset:entryUser'])) {
                //  var_dump($_POST);
                //  die();
                //  $this->verifyCsrfToken($_POST['csrf:csrf_token']);
                $cleanPostData = $this->preparePostData($_POST);
                $userLogin = $this->connectionManager->loginUser($cleanPostData);
                if ($userLogin) {
                    if ($ip !== MAIN_IP) {
                        $this->connectionManager->logConnection($ip, "SUCCESS");
                    }
                    header("Location: ?route=admin");
                } else {
                    $_SESSION["systemMessage"] = "Not the right credentials";
                    if ($ip !== MAIN_IP) {
                        $this->connectionManager->logConnection($ip, "FAILED");
                    }
                    header("Location: ./");
                }
                exit();
            }


        echo $this->twig->render('public/public.login.html.twig', [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "location" => "login",
            "sessionExpiresAt" => $sessionExpiresAt
        ]);
    }

public function begone() : void
{
    global $systemMessage, $sessionRole, $sessionExpiresAt;
    echo $this->twig->render('public/public.begone.html.twig', [
        "systemMessage" => $systemMessage,
        "sessionRole" => $sessionRole,
        "sessionExpiresAt" => $sessionExpiresAt
    ]);
}

}