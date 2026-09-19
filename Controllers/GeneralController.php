<?php

namespace Controllers;

use Controllers\Abstract\AbstractController;
use Factory\ManagerFactory;
use model\Manager\ConnectionManager;
use model\Manager\DokuManager;
use Twig\Environment;

class GeneralController extends Abstract\AbstractController
{
    private DokuManager $dokuManager;
    private ConnectionManager $connectionManager;

    public function __construct(Environment $twig, ManagerFactory $managerFactory)
    {
        parent::__construct($twig, $managerFactory);
        $this->dokuManager = $this->getManager(DokuManager::class);
        $this->connectionManager = $this->getManager(ConnectionManager::class);
    }

    public function viewAll(): void
    {
        global $sessionRole, $systemMessage;
        $ip = $_SERVER['REMOTE_ADDR'];
        if($ip !== MAIN_IP) {
            $this->connectionManager->logConnection($ip, "View All");
        }
        $puzzles = $this->dokuManager->getAllPuzzles();
        $totalTime = $this->dokuManager->getTotalTime();
        echo $this->twig->render("public/public.viewAll.html.twig", [
            "sessionRole" => $sessionRole,
            "systemMessage" => $systemMessage,
            "puzzles" => $puzzles,
            "totalTime" => $totalTime,
        ]);
    }

    public function findDoku(): void
    {
        global $sessionRole, $systemMessage;
        $ip = $_SERVER['REMOTE_ADDR'];
        if($ip !== MAIN_IP) {
            $this->connectionManager->logConnection($ip, "FindDoku");
        }
        $setters = $this->dokuManager->getAllSetters();
        $variants = $this->dokuManager->getAllVariants();
        $variantCounter = $this->dokuManager->countPuzzlesByVariant();
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
            $cleanedData = array_filter($cleanedData, fn($value) => $value !== null && $value !== 0);
            $searchResults = $this->extractSearchParams($cleanedData);
            if(empty($searchResults)) {
                $_SESSION["systemMessage"] = "No puzzles found fitting those parameters";
                header("Location: ?route=findDoku");
                exit();
            }

            echo $this->twig->render("public/public.searchResults.html.twig", [
                "sessionRole" => $sessionRole,
                "systemMessage" => $systemMessage,
                "csrfToken" => $this->csrfToken,
                "puzzles" => $searchResults,
            ]);
            exit();


        }

        echo $this->twig->render("public/public.searchPuzzles.html.twig", [
            "sessionRole" => $sessionRole,
            "systemMessage" => $systemMessage,
            "csrfToken" => $this->csrfToken,
            "setters" => $setters,
            "variants" => $variants,

        ]);
    }

    private function extractSearchParams(array $data): array|false
    {
        if (isset($data["variant_id"])) {
            $variantIds = $data["variant_id"];
            unset($data["variant_id"]);
        }
        $puzzlesByVariant = null;
        if (isset($variantIds)) {
            $puzzlesByVariant = $this->dokuManager->getPuzzlesByVariant($variantIds);

        }
        $dataArray = [
            "findBySetter" => $data["doku_setter_id"] ?? null,
            "duration" => $data["doku_duration"] ?? null,
            "startDate" => $data["doku_start"] ?? null,
            "endDate" => $data["doku_end"] ?? null,
        ];

        $dataArray = array_filter($dataArray, fn($value) => $value !== null && $value !== 0);
        $collectedPuzzles = [];
        foreach ($dataArray as $key => $value) {
            switch ($key) {
                case "findBySetter":
                    $collectedPuzzles = $this->dokuManager->getPuzzlesBySetter($value);

                    break;
                case "duration":
                    $collectedPuzzles = $this->dokuManager->getPuzzlesByDuration($value);
                    break;
                case "startDate":
                    //  $collectedPuzzles[] = $this->dokuManager->getPuzzlesByStartDate($value);
                    break;
                case "endDate":
                    //  $collectedPuzzles[] = $this->dokuManager->getPuzzlesByEndDate($value);
                    break;

            }
        }
        if(isset($puzzlesByVariant)) {
            foreach ($puzzlesByVariant as $puzzle) {
                $collectedPuzzles[] = $puzzle;
            }
        }
        return $collectedPuzzles;
    }
}