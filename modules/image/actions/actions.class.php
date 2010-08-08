<?php

/**
 * Контроллер загрузки картинок для объектов недвижимости
 */
class imageActions extends sfActions
{
    /**
     * PreExecute
     */
    public function preExecute()
    {
        sfConfig::set('sf_web_debug', false);
    }

    /**
     * Загрузить изображение в галерею
     */
    public function executeUpload(sfWebRequest $request)
    {
        $object = $this->getRoute()->getObject();
        $relation = $object->getTable()->getRelation('Images');
        $objectSetter = 'set' . get_class($object);

        $imageClass = $relation->getClass();
        $image = new $imageClass;
        $image-> $objectSetter ($object);

        $imageFormClass = $relation->getClass() . 'Form';
        $form = new $imageFormClass($image);

        $this->setVar('form', $form); // for tests
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        // Сохранить изображение и вернуть его превью
        if ($form->isValid()) {
            $image = $form->save();

            return $this->renderPartial('upload_preview', array(
                'image'   => $image,
                'object'  => $object,
                'routePrefix' => $request->getParameter('prefix'),
                'display' => 'none', // CSS, чтобы JS плавно показал
            ));

        // Вернуть ошибку
        } else {
            $this->getResponse()->setStatusCode(400);
            return sfView::HEADER_ONLY;
        }
    }

    /**
     * Установить картинку главной
     */
    public function executePrimary(sfWebRequest $request)
    {
        $this->forward404Unless($request->isXmlHttpRequest());

        $object = $this->getRoute()->getObject();
        $relation = $object->getTable()->getRelation('PrimaryImage');
        $manyFk = $object->getTable()->getRelation('Images')->getForeign();

        $this->forward404Unless($imageId = (int)$request->getParameter('image'));
        $this->forward404If($object->getImageId() == $imageId);

        $imageExists = Doctrine::getTable($relation->getClass())
            ->createQuery('i')
            ->where('i.id = ?', $imageId)
            ->andWhere(sprintf('i.%s = ?', $manyFk), $object->getId())
            ->count();

        $this->forward404Unless($imageExists);

        $object->setImageId($imageId);
        $object->save();

        $this->renderText($imageId);
        return sfView::NONE;
    }


    /**
     * Удалить изображение
     */
    public function executeDelete(sfWebRequest $request)
    {
        $this->forward404Unless($request->isXmlHttpRequest());

        $image = $this->getRoute()->getObject();
        $image->delete();

        return sfView::HEADER_ONLY;
    }
}
