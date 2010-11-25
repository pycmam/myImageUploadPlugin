<?php

class myImageUploadSessionStorage extends sfSessionStorage
{
    public function initialize($options = null)
    {
        $context = sfContext::getInstance();

        $sessionName = $options["session_name"];

        if($value = $context->getRequest()->getParameter($sessionName)) {
            session_name($sessionName);
            session_id($value);
        }

        if (isset($options['session_cookie_domain']) && '.' == $options['session_cookie_domain']) {
            preg_match('/([^.]+\.[^.]+)$/', $_SERVER['SERVER_NAME'], $matches);
            $options['session_cookie_domain'] = '.' . $matches[1];
        }

        parent::initialize($options);
    }
}