<?php

namespace App\Enums;

enum LabelType: string
{
    case Tray = 'tray';
    case Package = 'package';
    case Box = 'box';
    case Pallet = 'pallet';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
