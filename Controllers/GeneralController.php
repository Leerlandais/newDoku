<?php

namespace Controllers;

use Controllers\Abstract\AbstractController;
use Factory\ManagerFactory;
use model\Manager\ConnectionManager;
use model\Manager\DokuManager;
use model\Mapping\DokuDokusMapping;
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
        $puzzles = $this->dokuManager->getAllPuzzles($pageCounter);
        $puzzleCount = $this->dokuManager->getPuzzleCount();
        $totalTime = $this->dokuManager->getTotalTime();
        echo $this->twig->render("public/public.viewAll.html.twig", [
            "sessionRole" => $sessionRole,
            "systemMessage" => $systemMessage,
            "puzzles" => $puzzles,
            "totalTime" => $totalTime,
            "puzzleCount" => $puzzleCount,
            "pageCounter" => $pageCounter,
            "sessionExpiresAt" => $sessionExpiresAt
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
                "sessionExpiresAt" => $sessionExpiresAt,
                "foundPuzzles" => true
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
        if (!isset($_SESSION["testPage_logged"])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $ip = $ip == "::1" ? "localhost" : $ip;
            if ($ip !== MAIN_IP) {
                $this->connectionManager->logConnection($ip, "testPage");
                $_SESSION["testPage_logged"] = true;
            }
        }

        if (!isset($getParams["id"])) {
            header("Location: ?route=home");
            exit();
        }
        $id = $this->intClean($getParams["id"]);
        $puzzle = $this->dokuManager->getPuzzleById($id);
        $variants = $this->dokuManager->getAllVariants();

        if (isset($_FILES["fileToUpload"])) {
            /*
             * Handles the uploading of a new Logo
             */
            $file = $_FILES["fileToUpload"];
            $uploadImage = $this->dokuManager->uploadImage($file);
            if ($uploadImage) {
                $filePath = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_FILENAME);
                $altText = $this->standardClean($_POST["altText"]);
                $insertInfo = [
                    "doku_id" => $id,
                    "img_location" => $filePath,
                    "img_alt" => $altText,
                ];
                $this->dokuManager->addImageToDatabase($insertInfo);

            }
            header("Location: ?route=showDetails&id=$id");
            exit();

        }

            echo $this->twig->render('public/public.showDetails.html.twig', [
                "systemMessage" => $systemMessage,
                "sessionRole" => $sessionRole,
                "csrfToken" => $this->csrfToken,
                "location" => "test",
                "sessionExpiresAt" => $sessionExpiresAt,
                "puzzle" => $puzzle,
                "variants" => $variants
            ]);
    }
    private function extractSearchParams(array $data): array|false|DokuDokusMapping
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
            "date" => [$data["doku_start"] ?? null, $data["doku_end"] ?? null]

        ];
        if($dataArray["date"][0] == null && $dataArray["date"][1] == null) unset($dataArray["date"]);

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
                case "date":
                      $collectedPuzzles[] = $this->dokuManager->getPuzzlesByDate($value[0], $value[1]);
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
        if (empty($collectedPuzzles)) {
            return false;
        }
     //   die(var_dump(get_debug_type($collectedPuzzles)));

        if (is_array($collectedPuzzles) && is_array($collectedPuzzles[0])) {
            $collectedPuzzles = $collectedPuzzles[0];
        }

        return $collectedPuzzles;
    }


}