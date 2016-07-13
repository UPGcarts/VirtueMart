<?php

namespace Upg\Library\Validation\Helper;


class Regex
{
    const REGEX_FULL_ALPHANUMERIC = '/[\p{Cyrillic}\p{Latin}\p{Greek}\s0-9]*/';
    const REGEX_FULL_ALPHA = '/[\p{Cyrillic}\p{Latin}\p{Greek}\s]*/';

    const REGEX_PARTIAL_ALPHANUMERIC = '[\p{Cyrillic}\p{Latin}\p{Greek}\s0-9]';
    const REGEX_PARTIAL_ALPHA = '[\p{Cyrillic}\p{Latin}\p{Greek}\s]';
}
