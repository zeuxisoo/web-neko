<?php

namespace App\Enums;

enum AttachmentKind: string
{
    case FILE = 'file';
    case IMAGE = 'image';
    case VIDEO = 'video';
}
