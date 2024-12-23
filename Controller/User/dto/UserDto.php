<?php

namespace Controller\User\Dto;

use Core\Dto\Dto;
use Core\Helpers\Pipes\IsEmail;
use Core\Helpers\Pipes\IsInt;
use Core\Helpers\Pipes\IsRequired;
use Core\Helpers\Pipes\MaxLength;
use Core\Helpers\Pipes\MinLength;

class UserDto extends Dto
{
    public function __construct(
        public string $mise_name,
        public string $mise_slug,
        public string $mise_email,
        public int $mise_price,
        public string $mise_category,
        public string $mise_phone,
        public string $mise_country,
        public string $mise_password
    ) {
        parent::__construct($this->validations);
    }

    private array $validations = [
        'mise_name' => [
            IsRequired::class,
            [MinLength::class, 3],
            [MaxLength::class, 10]
        ],
        'mise_slug' => [
            IsRequired::class,
        ],
        // 'mise_email' => [
        //     IsRequired::class,
        //     IsEmail::class
        // ],
        'mise_price' => [
            IsRequired::class,
            IsInt::class
        ]
    ];
}
