<?php

namespace Src\Queue;

class Task
{
    public $key;

    /**
     * @var int
     */
    public $accountId;

    /**
     * @var \DateTimeImmutable
     */
    public $dateInsert;

    /**
     * @return string
     */
    public function getFormatDateInsert(): ?string
    {
        return $this->dateInsert ? $this->dateInsert->format('d.m.Y H:i:s') : null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'account' => $this->accountId,
            'dateInsert' => $this->getFormatDateInsert()
        ];
    }
}