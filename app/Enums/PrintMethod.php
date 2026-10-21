<?php

namespace App\Enums;

enum PrintMethod: string
{
    case Tformer = 'tformer'; // tfprint.exe -> PDF
    case Sbpl = 'sbpl';       // SBPL commands -> SATO printer
}
