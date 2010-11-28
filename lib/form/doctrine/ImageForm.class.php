<?php

/**
 * Форма для загрузки изображения в БД
 */
abstract class ImageForm extends BaseFormDoctrine
{
    /**
     * SetUp
     */
    public function setup()
    {
        parent::setup();

        $this->setWidget('path', new sfWidgetFormInputFile);
        $this->setValidator('path', new sfValidatorFile(array(
            'max_size'   => 4194304, // 4Mb
            'mime_types' => 'web_images',
        )));
        $this->widgetSchema->setHelp('path',
            'Вы можете загрузить одно или несколько изображений. Только JPG, PNG и GIF файлы до 2Mb.');

        $this->widgetSchema->setNameFormat('image[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }


    /**
     * Все свойства изображения инициализируются здесь
     *
     * @param  string $field    - Название поля к которому прикреплен sfValidatorFile
     * @param  string $filename
     * @param  array  $values
     * @return string           - Возвращает изображение ввиде строки для предачи его в объект
     */
    protected function processUploadedFile($field, $filename = null, $values = null)
    {
        if (is_null($values)) {
            $values = $this->values;
        }

        $image = $this->getObject();
        $file  = $this->getValue($field);

        $image->setType($file->getType());
        $image->setSize($file->getSize());

        return Image::saveUploadedFile($file, $this->getModelName());
    }

}
