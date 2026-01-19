<?php

namespace app\enums;

enum RequestStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Declined = 'declined';
    case Error = 'error';
    case Processing = 'processing';
}
