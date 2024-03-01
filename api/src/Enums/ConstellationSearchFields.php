<?php

namespace App\Enums;

enum ConstellationSearchFields: string
{
    case ID = 'id.keyword';
    case GEN = 'gen';
    case ALT = 'alt.alt';
}
