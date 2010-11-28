<?php

/**
 * PluginImage
 */
abstract class PluginImage extends BaseImage
{
    /**
     * Возвращает путь к превьюшке
     *
     * @param string $type
     * @return string
     */
    public function getImagePath($type)
    {
        if ($this->isNew() || !$this->size) {
            $proxy = new Replica_ImageProxy_FromFile(null);
        } else {
            //$proxy = new sfReplicaImageDoctrine($this);
            $path = sfConfig::get('sf_web_dir') . sfConfig::get('app_images_dir') .
                DIRECTORY_SEPARATOR . $this->getPath();

            $proxy = new Replica_ImageProxy_FromFile($path);
        }

        $config = sfReplicaThumbnail::getConfig($type);

        // Has image
        if ($proxy->getUid()) {

            sfReplicaThumbnail::loadMacro($type, $config['macro']);

            // If image not found return null
            try {
                $path = sfConfig::get('app_thumbnail_dir') . '/'
                      . Replica::cache()->get($type, $proxy, $config['mimetype']);
            } catch (Replica_Exception_ImageNotInitialized $e) {
                return;
            }

        // Default image
        } else if (isset($config['default'])) {
            $path = $config['default'];
        }

        return $path;
    }

    /**
     * Сохранить файл
     */
    static public function saveUploadedFile($file, $model)
    {
        $filename = md5($model . microtime()) . $file->getExtension();

        $upload_dir = sfConfig::get('sf_web_dir') . sfConfig::get('app_images_dir');

        $relative_dir = $model . DIRECTORY_SEPARATOR .
                        substr($filename, 0, 2) . DIRECTORY_SEPARATOR .
                        substr($filename, 2, 2);

        self::checkDir($dir = $upload_dir . DIRECTORY_SEPARATOR . $relative_dir);

        if (! rename($file->getTempName(), $dir . DIRECTORY_SEPARATOR . $filename)) {
            copy($file->getTempName(), $dir . DIRECTORY_SEPARATOR . $filename);
        }

        return $relative_dir . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Проверить существует ли директрия, если нет - создать
     */
    static protected function checkDir($dir)
    {
        if (! file_exists($dir)) {
            if (! mkdir($dir, 0777, true)) {
                throw new Exception(__CLASS__.": Failed to create directory `{$dir}`");
            }
        }
    }
}