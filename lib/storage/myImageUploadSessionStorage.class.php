<?php

class myImageUploadSessionStorage extends sfSessionStorage
{
    public function initialize($options = null)
    {
        $context = sfContext::getInstance();

        //if($context->getActionName() == "upload") {
            $sessionName = $options["session_name"];

            if($value = $context->getRequest()->getParameter($sessionName)) {
                session_name($sessionName);
                session_id($value);
            }
        //}

        parent::initialize($options);
    }
}