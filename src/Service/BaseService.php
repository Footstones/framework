<?php

namespace Footstones\Framework\Service;

use Footstones\Kernel;
use Footstones\Exception\ServiceException;

class BaseService
{
    const MISSING_PARAMETER = 51002;

    const INVALID_PARAMETER = 51003;

    protected function kernel()
    {
        return Kernel::instance();
    }

    protected function createServiceException($message = 'Service Exception', $code = 0)
    {
        return new ServiceException($message, $code);
    }
}
