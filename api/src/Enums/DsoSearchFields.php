<?php

namespace App\Enums;

enum DsoSearchFields: string
{
    case ID = 'id.keyword';
    case DESIGS = 'desigs';
    case ALT = 'alt.alt';
    case DISCOVER = 'discover';

}
