<?php
/**
 * Превью для просмотра и загруженного изображения.
 * Содержит контролы для работы с изображением.
 *
 * @param Image        $image
 * @param Object       $object
 * @param string       $routePrefix
 * @param string       $display - none|block - CSS правило
 */
use_helper('jQuery', 'I18N');

$display = isset($display) ? (string) $display : 'block';
?>

<li id="image_<?php echo $image->getId() ?>" <?php echo $object->getImageId() == $image->getId() ? 'class="primary"' : '' ?> style="display: <?php echo $display ?>">
    <?php echo tag('img', array('src' => $image->getImagePath('uploaded_thumbnail'))) ?>

    <div class="image-controls">
        <?php echo jq_link_to_remote(__('Delete image'), array(
            'url'      => url_for($routePrefix . '_image_delete', $image),
            'csrf'     => 1,
            'success'  => jq_visual_effect('fadeOut', '#image_'.$image->getId(), array('speed' => 100)),
        ), array('class' => 'delete')) ?>

        <?php echo jq_link_to_remote(__('Set as primary'), array(
            'url'      => url_for($routePrefix . '_image_primary', array('id' => $object->getId(), 'image' => $image->getId())),
            'csrf'     => 1,
            'success'  => 'jQuery("#image_list li").removeClass("primary"); jQuery("#image_'.$image->getId().'").addClass("primary");',
        ), array('class' => 'primary')) ?>
    </div>
</li>