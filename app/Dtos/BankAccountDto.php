<?php

namespace App\Dtos;

class BankAccountDto
{
    public function __construct(
        public int $user_id,
        public string $bank_name,
        public ?string $type,
        public string $bank_number,
        public string $bank_branch,
        public string $bank_account,
        public ?string $bank_account_owner_name,
        public ?string $balance,
        public ?string $date,
    ) {}

    public function toArray(): array
    {
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        $array = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
        }

        return $array;
    }
}
