<?php

namespace App;

enum ReportStatusEnum: string
{
    case OPEN = 'Open';
    case INVESTIGATING = 'Investigating';
    case CLOSED = 'Closed';
}
