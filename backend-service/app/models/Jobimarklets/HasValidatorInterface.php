<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: HasValidatorInterface.php
 * Date: 12/02/2017
 * Time: 21:32
 */

namespace Jobimarklets;


use Illuminate\Validation\Validator;

interface HasValidatorInterface
{
    /**
     * @return Validator
     */
    public function validate();
}