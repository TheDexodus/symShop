<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

final class OrderStatisticsAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'order_statistics';
    protected $baseRouteName = 'order_statistics';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('import');
        $collection->clearExcept(['list']);
    }
}
