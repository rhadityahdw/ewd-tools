<?php

namespace App;

enum Status: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case ENDED = 'ended';
    case EXPIRED = 'expired';
}
