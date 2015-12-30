<?php

namespace Footstones\Framework\Service;

use Footstones\Framework\Kernel;
use Footstones\Framework\Exception\ServiceException;

class BaseService
{
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function kernel()
    {
        return $this->kernel;
    }

    protected function createServiceException($message = 'Service Exception', $code = 0)
    {
        return new ServiceException($message, $code);
    }
}
