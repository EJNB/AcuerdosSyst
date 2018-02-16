<?php

namespace System\ListServicesBundle\Controller;


interface IOrderByService {
    /**
     * @param $column
     * @param $original_columns
     * @return bool
     */
    function OrderBy($column, $original_columns);

    /**
     * @return string
     */
    function getColumn();

    /**
     * @return string
     */
    function getOrder();
} 