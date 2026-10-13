<?php

namespace Controllers;

use Controllers\Abstract\AbstractController;
use Factory\ManagerFactory;
use model\Manager\AdminManager;
use model\Manager\CluesManager;
use model\Manager\ConnectionManager;
use model\Manager\DokuManager;
use model\Manager\SearchManager;
use model\Mapping\DokuDokusMapping;
use Twig\Environment;

class GeneralController extends Abstract\AbstractController
{
    private DokuManager $dokuManager;
    private ConnectionManager $connectionManager;
    private AdminManager $adminManager;
    private CluesManager $cluesManager;
    private SearchManager $searchManager;

    public function __construct(Environment $twig, ManagerFactory $managerFactory)
    {
        parent::__construct($twig, $managerFactory);
        $this->dokuManager = $this->getManager(DokuManager::class);
        $this->connectionManager = $this->getManager(ConnectionManager::class);
        $this->adminManager = $this->getManager(AdminManager::class);
        $this->cluesManager = $this->getManager(CluesManager::class);
        $this->searchManager = $this->getManager(SearchManager::class);
    }

    public function viewAll(array $getParams): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;

        if (!isset($_SESSION["viewAll_logged"])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $ip = $ip == "::1" ? "localhost" : $ip;
            //     if($ip !== MAIN_IP) {
            $this->connectionManager->logConnection($ip, "View All");
            $_SESSION["viewAll_logged"] = true;
            //      }
        }
        $pageCounter = $this->intClean($_GET["pageCounter"] ?? 1);
        $puzzleCount = $this->dokuManager->getPuzzleCount();
        $puzzles = $this->dokuManager->getAllPuzzles($pageCounter);
        $totalTime = $this->dokuManager->getTotalTime();
        echo $this->twig->render("public/public.viewAll.html.twig", [
            "sessionRole" => $sessionRole,
            "systemMessage" => $systemMessage,
            "puzzles" => $puzzles,
            "totalTime" => $totalTime,
            "puzzleCount" => $puzzleCount,
            "pageCounter" => $pageCounter,
            "sessionExpiresAt" => $sessionExpiresAt,
            "location" => "viewAll"
        ]);
    }

    public function getPrevious(array $getParams): void
    {

        global $systemMessage, $sessionRole, $sessionExpiresAt;

        $offset = null;
        if(isset($getParams["date"])) {
            $offset = $this->intClean($getParams["date"]);
        }

        $requestedDate = date("Y-m-d", strtotime("- $offset day"));
        $solvedPuzzles = $this->dokuManager->getLatestPuzzles($offset);
        $totalTime = $this->dokuManager->getTotalTime(true, $requestedDate);

        echo $this->twig->render('public/public.index.html.twig', [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "puzzles" => $solvedPuzzles,
            "location" => "home",
            "totalTime" => $totalTime,
            "sessionExpiresAt" => $sessionExpiresAt,
            "currentDateOffset" => $offset,
        ]);
    }

    public function findDoku(): void
    {
        global $sessionRole, $systemMessage, $sessionExpiresAt;
        if (!isset($_SESSION["findDoku_logged"])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $ip = $ip == "::1" ? "localhost" : $ip;
            //  if($ip !== MAIN_IP) {
            $this->connectionManager->logConnection($ip, "Find Doku");
            $_SESSION["findDoku_logged"] = true;
            //  }
        }
        $setters = $this->dokuManager->getAllSetters();
        $variants = $this->dokuManager->getAllVariants();
        $variantCounter = $this->dokuManager->countPuzzlesByVariant();
        $puzzleCount = $this->dokuManager->getPuzzleCount();
        $counts = [];

        foreach ($variantCounter as $row) {
            $counts[$row['doku_variant']] = $row['count'];
        }

// Set the count on each mapping object
        foreach ($variants as $variant) {
            $variantId = $variant->getVariantId();

            $variant->setVariantCount($counts[$variantId] ?? 0);
        }


        if (isset($_POST["unset:searchPuzzles"])) {
            $cleanedData = $this->preparePostData($_POST);
            $cleanedData = array_filter($cleanedData, fn($value) => $value !== null && $value !== 0 && $value !== "");

            $searchResults = $this->runSearch($cleanedData);
            $searchResults = array_filter($searchResults, fn($value) => $value !== false);
            $searchOptions = ["byTitle", "bySetter", "byDuration", "byStars", "byVariant", "byDate"
            ];

            echo $this->twig->render("public/public.searchResults.html.twig", [
                "sessionRole" => $sessionRole,
                "systemMessage" => $systemMessage,
                "csrfToken" => $this->csrfToken,
                "puzzles" => $searchResults,
                "sessionExpiresAt" => $sessionExpiresAt,
                "searchOptions" => $searchOptions,
            ]);
            exit();


        }

        echo $this->twig->render("public/public.searchPuzzles.html.twig", [
            "sessionRole" => $sessionRole,
            "systemMessage" => $systemMessage,
            "csrfToken" => $this->csrfToken,
            "setters" => $setters,
            "variants" => $variants,
            "sessionExpiresAt" => $sessionExpiresAt,
            "puzzleCount" => $puzzleCount,

        ]);
    }


    public function showDetails(array $getParams): void
    {
        global $systemMessage, $sessionRole, $sessionExpiresAt;
        if (!isset($getParams["id"]) || !is_numeric($getParams["id"])) {
            header("Location: ?route=home");
            exit();
        }
        $id = $this->intClean($getParams["id"]);
        $ip = $_SERVER['REMOTE_ADDR'];
        $ip = $ip == "::1" ? "localhost" : $ip;
        if ($ip !== MAIN_IP) {
            $this->connectionManager->logConnection($ip, "Puzzle :" . $id);
            $this->dokuManager->incrementViewCount($id);
        }
        $puzzle = $this->dokuManager->getPuzzleById($id);
        $variants = $this->dokuManager->getAllVariants();
        $averageTime = $this->dokuManager->getAverageTime();
        if (isset($_FILES["fileToUpload"])) {
            /*
             * Handles the uploading of a new Logo
             */
            $file = $_FILES["fileToUpload"];
            $uploadImage = $this->adminManager->uploadImage($file);
            if ($uploadImage) {
                $filePath = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_FILENAME);
                $altText = $this->standardClean($_POST["altText"]);
                $insertInfo = [
                    "doku_id" => $id,
                    "img_location" => $filePath,
                    "img_alt" => $altText,
                ];
                $this->adminManager->addImageToDatabase($insertInfo);

            }
            header("Location: ?route=showDetails&id=$id");
            exit();

        } elseif (isset($_POST["unset:addClue"])) {
            $cleanedData = $this->preparePostData($_POST);
            $addClue = $this->adminManager->addClue($cleanedData);
            $_SESSION["systemMessage"] = $addClue ? "Clue added" : "Clue not added";
            header("Location: ?route=showDetails&id=$id");
            exit();
        } elseif (isset($_POST["unset:updateStars"])) {
            $cleanedData = $this->preparePostData($_POST);
            $cleanedData["doku_id"] = $id;
            $this->adminManager->updateStars($cleanedData);
            header("Location: ?route=showDetails&id=$id");
            exit();
        }elseif (isset($_POST["unset:incrementSolve"])) {
            $puzzleId = $this->intClean($_POST["unset:incrementSolve"]);
            $this->adminManager->incrementSolve($puzzleId);
            header("Location: ?route=showDetails&id=$puzzleId");
        }
        $dokuClues = $this->cluesManager->getDokuClues($id);
        echo $this->twig->render('public/public.showDetails.html.twig', [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "location" => "test",
            "sessionExpiresAt" => $sessionExpiresAt,
            "puzzle" => $puzzle,
            "variants" => $variants,
            "clues" => $dokuClues,
            "averageTime" => $averageTime,
        ]);
    }

    public function getVariant(array $getParams): void
    {
        global $systemMessage, $sessionRole, $sessionExpiresAt;
        if (!isset($getParams["id"]) || !is_numeric($getParams["id"])) {
            header("Location: ?route=home");
            exit();
        }
        $id = $this->intClean($getParams["id"]);

        $puzzles["byVariant"] = $this->searchManager->getPuzzlesByVariantId($id);

        echo $this->twig->render('public/public.searchResults.html.twig', [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "csrfToken" => $this->csrfToken,
            "puzzles" => $puzzles,
            "foundPuzzles" => true,
            "sessionExpiresAt" => $sessionExpiresAt,
        ]);

    }

    public function showVariantRules(): void
    {
        global $systemMessage, $sessionRole, $sessionExpiresAt;
        if (!isset($_SESSION["variant_logged"])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $ip = $ip == "::1" ? "localhost" : $ip;
            if ($ip !== MAIN_IP) {
                $this->connectionManager->logConnection($ip, "Variant Rules");
            }
            $_SESSION["variant_logged"] = true;
        }
        $variants = $this->dokuManager->getAllVariants();
        echo $this->twig->render('public/public.showVariantRules.html.twig', [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "variants" => $variants,
            "sessionExpiresAt" => $sessionExpiresAt,
        ]);
    }

    public function getRandomPuzzle(): void
    {
        global $systemMessage, $sessionRole, $sessionExpiresAt;
        if (!isset($_SESSION["random_logged"])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $ip = $ip == "::1" ? "localhost" : $ip;
            if ($ip !== MAIN_IP) {
                $this->connectionManager->logConnection($ip, "Random Puzzle");
            }
            $_SESSION["random_logged"] = true;
        }
        $totalAllowed = $this->dokuManager->getPuzzleCount();

        $puzzleId = rand(1, $totalAllowed);
        header("Location: ?route=showDetails&id=$puzzleId");
        exit();
    }

    private function runSearch(array $data): array|false|DokuDokusMapping
    {

        $resultsArray = [];
        if (isset($data["doku_title"])) {
            $resultsArray["byTitle"] = $this->searchManager->getPuzzleByTitle($data["doku_title"]);
        } else {
            $resultsArray["byTitle"] = false;
        }
        if (isset($data["doku_setter_id"])) {
            $resultsArray["bySetter"] = $this->searchManager->getPuzzlesBySetter($data["doku_setter_id"]);
        } else {
            $resultsArray["bySetter"] = false;
        }

        if (isset($data["doku_duration"])) {
            $resultsArray["byDuration"] = $this->searchManager->getPuzzlesByDuration($data["doku_duration"]);
        } else {
            $resultsArray["byDuration"] = false;
        }
        if (isset($data["doku_stars"])) {
            $resultsArray["byStars"] = $this->searchManager->getPuzzleByStars($data["doku_stars"]);
        } else {
            $resultsArray["byStars"] = [];
        }

        if (isset($data["doku_stars_min"])) {
            $resultsArray["byStarsMin"] = $this->searchManager->getPuzzleByStars($data["doku_stars_min"], true);
        } else {
            $resultsArray["byStarsMin"] = false;
        }

        if (isset($data["variant_id"])) {
            $resultsArray["byVariant"] = $this->searchManager->getPuzzlesByVariant($data["variant_id"]);
        } else {
            $resultsArray["byVariant"] = false;
        }
        $stopIt = false;
        if (isset($data["doku_start"]) && isset($data["doku_end"])) {
            if(substr($data["doku_start"], 0, 10) == "2026-09-06" &&
                substr($data["doku_end"], 0, 10) == date("Y-m-d")) {
                $stopIt = true;
                if(count($data) < 3) {
                    header("Location: ?route=viewAll");
                }
            }
            $resultsArray["byDate"] = $this->searchManager->getPuzzlesByDate($data["doku_start"], $data["doku_end"]);
        }
        if($stopIt) $resultsArray["byDate"] = false;


        foreach (array_merge(
                     $resultsArray["byStars"] ?: [],
                     $resultsArray["byStarsMin"] ?: []
                 ) as $puzzle) {
            $resultsArray["byStars"][$puzzle->getDokuId()] = $puzzle;
        }

        $resultsArray["byStars"] = array_values($resultsArray["byStars"]);
        unset($resultsArray["byStarsMin"]);
        return $resultsArray;

    }

    public function searchByStars(array $getParams): void
    {
        global $systemMessage, $sessionRole, $sessionExpiresAt;
        $stars = $this->intClean($getParams["count"] ?? 1);
        $puzzles["byStars"] = $this->searchManager->getPuzzleByStars($stars);
        echo $this->twig->render('public/public.searchResults.html.twig', [
            "systemMessage" => $systemMessage,
            "sessionRole" => $sessionRole,
            "puzzles" => $puzzles,
            "sessionExpiresAt" => $sessionExpiresAt,
        ]);
    }
}