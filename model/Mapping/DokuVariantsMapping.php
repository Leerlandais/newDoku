<?php

namespace model\Mapping;

use model\Abstract\AbstractMapping;

class DokuVariantsMapping extends AbstractMapping
{
    private int $variant_id;
    private string $variant_name;
    private string $variant_rules;

    public function getVariantId(): int
    {
        return $this->variant_id;
    }

    public function setVariantId(int $variant_id): void
    {
        $this->variant_id = $variant_id;
    }

    public function getVariantName(): string
    {
        return $this->variant_name;
    }

    public function setVariantName(string $variant_name): void
    {
        $this->variant_name = $variant_name;
    }

    public function getVariantRules(): string
    {
        return $this->variant_rules;
    }

    public function setVariantRules(string $variant_rules): void
    {
        $this->variant_rules = $variant_rules;
    }


}