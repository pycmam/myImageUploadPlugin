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
            $proxy = new sfReplicaImageDoctrine($this);
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
}