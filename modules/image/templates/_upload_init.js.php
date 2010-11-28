<?php
/**
 * Скрипт инициализации swfupload'a
 *
 * @param Object       $object
 * @param ImageForm    $form
 */
?>
<script type="text/javascript">
//<[CDATA[
var upload = new function() {

  function _construct() {

    this.errors = {
      '-100': 'Превышен лимит файлов',
      '-110': 'Превышен размер файла',
      '-120': 'Файл нулевой длины',
      '-130': 'Недопустимый тип файла',

      '-200': 'Ошибка сервера',
      '-220': 'Ошибка ввода-вывода',
      '-230': 'Ошибка безопасности',
      '-240': 'Превышен лимит загрузки',
      '-280': 'Загрузка отменена',
      '-290': 'Загрузка остановлена'
    };

    this.resetProgress = function() {
        $('#upload_progress .total').text(0);
        $('#upload_progress .count').text(0);
        $('#upload_progress .persents').text('0%');
        $('#error_list li').remove();
    };

    /**
     * диалог выбора файлов
     */
    this.fileDialogComplete = function(numFilesSelected, numFilesQueued) {
        if (numFilesSelected > 0) {
            upload.resetProgress();
            $('#upload_progress .total').text(numFilesQueued);
            $('#upload_progress').show();

            this.startUpload();
        }
    };

    /**
     * файл добавлен в очередь
     */
    this.fileQueued = function(file) { };

    /**
     * старт загрузки файла
     */
    this.uploadStart = function(file) {
        var count = parseInt($('#upload_progress .count').text())+1;
        $('#upload_progress .count').text(count);
    };

    /**
     * прогресс загрузки
     */
    this.uploadProgress = function(file, bytesLoaded, bytesTotal) {
        $('#upload_progress .percents').text(Math.round(bytesLoaded / (bytesTotal / 100)) + '%');
    };

    /**
     * ошибка загрузки
     */
    this.uploadError = function(file, errorCode, message) {
        $('#error_list').append('<li>' + file.name + ': ' + upload.errors[errorCode]);
    };

    /**
     * ошибка добавления в очередь
     */
    this.fileQueueError = function(file, errorCode, message) {
        $('#error_list').append('<li>' + file.name + ': ' + upload.errors[errorCode]);
    };

    /**
     * загрузка завершена успешно
     */
    this.uploadSuccess = function(file, response) {
        $('#image_list').append(response);
        $('#image_list li:last-child').fadeIn('slow');
    };

    /**
     * загрузка завершена
     */
    this.uploadComplete = function(file) { };

    /**
     * все файлы очереди загружены
     */
    this.queueComplete = function() {
        $('#upload_progress').hide();
        //upload.resetProgress();
    };

    /**
     * отмена всей очереди
     */
    this.cancelQueue = function() {
        upload.swfu.cancelQueue();
        upload.resetProgress();
        $('#upload_progress').hide();
        alert('Загрузка отменена');
    };

    /**
     * инициализация swfupload'а
     */
    this.init = function() {
      var settings = {
        flash_url : "/myImageUploadPlugin/swfupload.swf",
            upload_url: "<?php echo url_for($routePrefix . '_image_upload', $object) ?>?<?php echo ini_get('session.name') ?>=<?php echo session_id() ?>",
            file_post_name: "<?php echo $form->getName() ?>[path]",
            post_params: {
              '<?php echo $form->getName() ?>[_csrf_token]': "<?php echo $form->getCSRFToken(); ?>"
            },

            file_size_limit : "<?php echo $form->getValidator('path')->getOption('max_size') / 1024 / 1024 ?> MB",
            file_types : "*.jpg;*.jpeg;*.png;*.gif",
            file_types_description : "Файлы изображений",
            file_upload_limit : <?php echo sfConfig::get('app_image_upload_limit', 15) ?>,
            file_queue_limit : 0,
            debug: false,

            button_image_url: "/images/upload-button.png",
            button_width: "120",
            button_height: "29",
            button_placeholder_id: "upload_button",
            button_text: '<span class="upload_button">Выбрать файлы...</span>',
            button_text_style: ".upload_button { font-family: tahoma; font-size: 11pt; }",
            button_text_left_padding: 15,
            button_text_top_padding: 6,

            file_queued_handler : this.fileQueued,
            file_queue_error_handler : this.fileQueueError,
            file_dialog_complete_handler : this.fileDialogComplete,

            upload_start_handler : this.uploadStart,
            upload_progress_handler : this.uploadProgress,
            upload_error_handler : this.uploadError,
            upload_success_handler : this.uploadSuccess,
            upload_complete_handler : this.uploadComplete,

            queue_complete_handler : this.queueComplete    // Queue plugin event
      };

      this.swfu = new SWFUpload(settings);
    };
  }

  return new _construct();
};

$(function(){
  upload.init();
});
//]]>
</script>
