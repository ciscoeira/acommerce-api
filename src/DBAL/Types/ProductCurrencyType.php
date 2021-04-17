<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class ProductCurrencyType extends AbstractEnumType
{
    public const EUR = 'EUR';
    public const USD = 'USD';

    protected static $choices = [
        self::EUR => '€',
        self::USD => '$',
    ];
}