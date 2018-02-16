<?php

namespace System\ListServicesBundle\Controller;

use System\SessionServicesBundle\Controller\PrefixedSessionService;

class SessionOrderByService implements IOrderByService {
    private $column = "";
    private $order = "";
    private $session = null;

    private function ValueExistsInArray($value, $array) {
        foreach($array as $item) {
            if ($item == $value) return true;
        }

        return false;
    }

    public function __construct(PrefixedSessionService $session) {
        $this->session = $session;
    }

    /**
     * @param $column
     * @param $original_columns
     * @return bool
     */
    public function OrderBy($column, $original_columns)
    {
        //Precondiciones...
        if (!is_null($column) && !$this->ValueExistsInArray($column, $original_columns)) {
            return false;
        }

        $this->order = "";
        $this->column = $column;

        if (is_null( $this->column)) {
            $this->column = $this->session->get('column');

            if (isset( $this->column)) { //Valores por defecto...
                $this->column = $original_columns[0];
                $this->order = "DESC";
            }
            else {
                $this->order = $this->session->get('order');
            }
        }
        else {
            //Si se ha seleccionado una columna poner ascendente por defecto sino cambiar a descendente...
            if ($this->column != $this->session->get('column')) {
                $this->order = "ASC";
            }
            else {
                $this->order = $this->session->get('order');
                if (is_null( $this->order) || $this->order == "DESC" ) $this->order = "ASC";
                else $this->order = "DESC";
            }
        }

        //Salvando en sesion...
        $this->session->set('column', $this->column);
        $this->session->set('order', $this->order);

        return true;
    }

    /**
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return string
     */
    function getOrder()
    {
        return $this->order;
    }
}