<?php

namespace App\Enums;

enum OrderReturnReason: string
{
    case DEFECTIVE = 'defective';
    case WRONG_ITEM = 'wrong_item';
    case DAMAGED_PACKAGING = 'damaged_packaging';
    case MISSING_ITEMS = 'missing_items';
    case CHANGE_OF_MIND = 'change_of_mind';
    case OTHERS = 'others';

    public function label(): string
    {
        return match ($this) {
            self::DEFECTIVE => 'Item is defective / does not work',
            self::WRONG_ITEM => 'Received the wrong item/size/color',
            self::DAMAGED_PACKAGING => 'Item arrived damaged',
            self::MISSING_ITEMS => 'Missing items or accessories',
            self::CHANGE_OF_MIND => 'Change of mind',
            self::OTHERS => 'Others (Please specify in notes)',
        };
    }
}
