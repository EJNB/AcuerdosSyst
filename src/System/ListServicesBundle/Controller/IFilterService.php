<?php
/**
 * Created by PhpStorm.
 * User: NOTEBOOK
 * Date: 11/11/14
 * Time: 20:24
 */

namespace System\ListServicesBundle\Controller;


interface IFilterService {
    /**
     * @param $text
     * @return bool
     */
    function Filter($text);

    /**
     * @return string
     */
    function getText();
} 