<?php

namespace model\Mapping;

use DateTime;
use model\Abstract\AbstractMapping;

class DokuDokusMapping extends AbstractMapping
{
    private int $doku_id;
    private string $doku_title;
    private string $doku_url;
    private int $doku_setter_id;
    private string $doku_setter_name;
    private ?string $doku_variant_names;

    private DateTime $doku_date;
    private ?int $doku_duration;
    private ?int $doku_stars;

    private ?string $doku_img_location;
    private int $doku_solve_count;
    private int $doku_view_count;

    public function getDokuId(): int
    {
        return $this->doku_id;
    }

    public function setDokuId(int $doku_id): void
    {
        $this->doku_id = $doku_id;
    }

    public function getDokuTitle(): string
    {
        return htmlspecialchars_decode($this->doku_title);
    }

    public function setDokuTitle(string $doku_title): void
    {
        $this->doku_title = $doku_title;
    }

    public function getDokuUrl(): string
    {
        return $this->doku_url;
    }

    public function setDokuUrl(string $doku_url): void
    {
        $this->doku_url = $doku_url;
    }

    public function getDokuSetterId(): int
    {
        return $this->doku_setter_id;
    }

    public function setDokuSetterId(int $doku_setter_id): void
    {
        $this->doku_setter_id = $doku_setter_id;
    }

    public function getDokuSetterName(): string
    {
        return htmlspecialchars_decode($this->doku_setter_name);
    }

    public function setDokuSetterName(string $doku_setter_name): void
    {
        $this->doku_setter_name = $doku_setter_name;
    }

    public function getDokuDate(): DateTime
    {
        return $this->doku_date;
    }

    public function setDokuDate(DateTime|string $doku_date): void
    {
        if(is_string($doku_date)) {
            $doku_date = new DateTime($doku_date);
        }
        $this->doku_date = $doku_date;
    }

    public function getDokuDuration(): ?int
    {
        return $this->doku_duration;
    }

    public function setDokuDuration(?int $doku_duration): void
    {
        $this->doku_duration = $doku_duration;
    }

    public function getDokuVariantNames(): ?string
    {
        return $this->doku_variant_names;
    }

    public function setDokuVariantNames(?string $doku_variant_names): void
    {
        $this->doku_variant_names = $doku_variant_names;
    }

    public function getDokuImgLocation(): ?string
    {
        return $this->doku_img_location;
    }

    public function setDokuImgLocation(?string $doku_img_location): void
    {
        $this->doku_img_location = $doku_img_location;
    }

    public function getDokuStars(): ?int
    {
        return $this->doku_stars;
    }

    public function setDokuStars(?int $doku_stars): void
    {
        $this->doku_stars = $doku_stars;
    }

    public function getDokuSolveCount(): int
    {
        return $this->doku_solve_count;
    }

    public function setDokuSolveCount(int $doku_solve_count): void
    {
        $this->doku_solve_count = $doku_solve_count;
    }

    public function getDokuViewCount(): int
    {
        return $this->doku_view_count;
    }

    public function setDokuViewCount(int $doku_view_count): void
    {
        $this->doku_view_count = $doku_view_count;
    }






}