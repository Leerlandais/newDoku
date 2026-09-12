<?php

namespace model\Mapping;

use DateTime;
use model\Abstract\AbstractMapping;

class DokuUserMapping extends AbstractMapping
{
    private int $doku_user_id;
    private string $doku_username;
    private string $doku_password;
    private string $doku_email;
    private string $doku_user_roles;
    private DateTime $doku_user_created;
    private bool $doku_user_active;
    private string $doku_user_ip;
    private int $doku_user_status;
    private ?string $doku_user_intro;

    public function getDokuUserId(): int
    {
        return $this->doku_user_id;
    }

    public function setDokuUserId(int $doku_user_id): void
    {
        $this->doku_user_id = $doku_user_id;
    }

    public function getDokuUsername(): string
    {
        return $this->doku_username;
    }

    public function setDokuUsername(string $doku_username): void
    {
        $this->doku_username = $doku_username;
    }

    public function getDokuPassword(): string
    {
        return $this->doku_password;
    }

    public function setDokuPassword(string $doku_password): void
    {
        $this->doku_password = $doku_password;
    }

    public function getDokuEmail(): string
    {
        return $this->doku_email;
    }

    public function setDokuEmail(string $doku_email): void
    {
        $this->doku_email = $doku_email;
    }

    public function getDokuUserRoles(): string
    {
        return $this->doku_user_roles;
    }

    public function setDokuUserRoles(string $doku_user_roles): void
    {
        $this->doku_user_roles = $doku_user_roles;
    }

    public function getDokuUserCreated(): DateTime
    {
        return $this->doku_user_created;
    }

    public function setDokuUserCreated(DateTime $doku_user_created): void
    {
        $this->doku_user_created = $doku_user_created;
    }

    public function isDokuUserActive(): bool
    {
        return $this->doku_user_active;
    }

    public function setDokuUserActive(bool $doku_user_active): void
    {
        $this->doku_user_active = $doku_user_active;
    }

    public function getDokuUserIp(): string
    {
        return $this->doku_user_ip;
    }

    public function setDokuUserIp(string $doku_user_ip): void
    {
        $this->doku_user_ip = $doku_user_ip;
    }

    public function getDokuUserStatus(): int
    {
        return $this->doku_user_status;
    }

    public function setDokuUserStatus(int $doku_user_status): void
    {
        $this->doku_user_status = $doku_user_status;
    }

    public function getDokuUserIntro(): ?string
    {
        return $this->doku_user_intro;
    }

    public function setDokuUserIntro(?string $doku_user_intro): void
    {
        if(!$doku_user_intro) $doku_user_intro = "Nothing yet";
        $this->doku_user_intro = $doku_user_intro;
    }



}