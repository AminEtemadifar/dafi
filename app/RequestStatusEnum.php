<?php

namespace App;

enum RequestStatusEnum: string
{
    case PREPARE = 'prepare';
    case REQUESTED = 'requested';
    case DONE = 'done';
}
