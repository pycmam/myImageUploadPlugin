<?php
/**
 * Виджет для загрузки галереи изображений.
 * Выводит форму и список превью изображений
 *
 * @param Object    $realty
 * @param string    $routePrefix
 * @param ImageForm $form
 */

use_helper('I18N');
?>

<?php include_partial('image/upload_init.js', array(
    'object' => $object,
    'routePrefix' => $routePrefix,
    'form' => $form
)); ?>

<div id="image_upload_gallery">
    <?php if ($object->isNew()): ?>
        <p><?php echo __('Save an object to add photos.') ?></p>
    <?php else: ?>
        <div id="upload_button"></div>
        <p><?php echo __('You can select multiple files.') ?></p>
        <div id="upload_progress">

            <?php
            use_javascript('/myImageUploadPlugin/js/swfupload/swfupload.js');
            use_javascript('/myImageUploadPlugin/js/swfupload/swfupload.queue.js');
            ?>

            <?php echo __('Uploading file') ?> <span class="count">0</span> (<span class="percents">0%</span>) <?php echo __('from') ?> <span class="total">0</span>
            <a href="#" onclick="upload.cancelQueue(); return false;"><?php echo __('Cancel') ?></a>
        </div>
        <ul id="error_list">
        </ul>
        <ul id="image_list">
        <?php
            foreach ($object->getImages() as $image) {
                echo include_partial('image/upload_preview', array(
                    'image'   => $image,
                    'routePrefix' => $routePrefix,
                    'object'  => $object,
                ));
            }
        ?>
        </ul>
    <?php endif ?>
</div>
