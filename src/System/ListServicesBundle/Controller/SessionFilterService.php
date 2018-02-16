<?php
/**
 * Created by PhpStorm.
 * User: NOTEBOOK
 * Date: 11/11/14
 * Time: 20:45
 */

namespace System\ListServicesBundle\Controller;


use System\SessionServicesBundle\Controller\PrefixedSessionService;

class SessionFilterService implements IFilterService {
    private $text = "";
    private $session = null;

    public function __construct(PrefixedSessionService $session) {
        $this->session = $session;
    }

    /**
     * @param $text
     * @return bool
     */
    public function Filter($text)
    {
        $this->text = $text;

        if (is_null($text)) {
            $this->text = $this->session->get('filter');
        }
        else {
            $this->session->set('filter', $this->text);
        }

        return true;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
} 