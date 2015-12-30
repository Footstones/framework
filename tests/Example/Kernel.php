<?php

namespace Footstones\Framework\Test\Example;

use Footstones\Framework\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    public function boot()
    {
        date_default_timezone_set('Asia/Shanghai');
    }

    public function getNamespace()
    {
        return __NAMESPACE__;
    }
}
