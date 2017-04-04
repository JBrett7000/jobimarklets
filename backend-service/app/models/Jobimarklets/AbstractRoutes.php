<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: AbstractRoutes.php
 * Date: 31/03/2017
 * Time: 16:24
 */

namespace Jobimarklets;


abstract class AbstractRoutes
{
    protected $app;

    public function __construct(&$app)
    {
        $this->buildRoutes($app);
    }

    public abstract function buildRoutes(&$app);
}