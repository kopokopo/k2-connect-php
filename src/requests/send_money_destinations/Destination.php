<?php

namespace Kopokopo\SDK\Requests\SendMoneyDestinations;

abstract  class Destination
{
    protected array $data;
    abstract public function getDestinationData(): array;

    public function __construct(array $data) {
        $this->data = $data;
    }

    protected function getType(): string {
        return $this->getDestinationAttribute("type");
    }

    protected function getReference(): string {
        return $this->getDestinationAttribute("reference");
    }

    protected function getNickname(): ?string {
        if (!isset($this->data["nickname"])) return null;

        return $this->getDestinationAttribute("nickname");
    }

    protected function getAmount(): float {
        return $this->getDestinationAttribute("amount");
    }

    protected function getDescription(): string {
        return $this->getDestinationAttribute("description");
    }

    protected function getFavourite(): bool {
        if (!isset($this->data["favourite"])) return false;

        return $this->getDestinationAttribute("favourite");
    }

    protected function getDestinationAttribute($key)
    {
        if (!isset($this->data[$key])) throw new \InvalidArgumentException("You have to provide the $key");

        return $this->data[$key];
    }
}