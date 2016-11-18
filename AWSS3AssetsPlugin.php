<?php

/*
 * This file is part of the AWS S3 Assets plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Craft;

use Craft\Exception as CraftException;
use JonnyW\AWSS3Assets\S3Bucket;
use JonnyW\AWSS3Assets\Model\Settings;

/**
 * AWS S3 Assets
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class AWSS3AssetsPlugin extends BasePlugin
{
    /**
     * Internal constructor
     *
     * - Autoload vendor libraries
     *
     * @access public
     */
    public function __construct()
    {
        $autoload = realpath(dirname(__FILE__)) . '/../../../vendor/autoload.php';

        if (file_exists($autoload)) {
            @include_once $autoload;
        }
    }

    /**
     * Get plugin name.
     *
     * @access public
     * @return string
     */
    public function getName()
    {
         return Craft::t('AWS S3 Assets');
    }

    /**
     * Get plugin version.
     *
     * @access public
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Get plugin developer.
     *
     * @access public
     * @return string
     */
    public function getDeveloper()
    {
        return 'JonnyW';
    }

    /**
     * Get plugin developer URL.
     *
     * @access public
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'http://www.jonnyw.me';
    }

    /**
     * Get plugin documenation URL.
     *
     * @access public
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/jonnnnyw/craft-awss3assets/blob/master/README.md';
    }

    /**
     * Get plugin release feed URL.
     *
     * @access public
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/jonnnnyw/craft-awss3assets/master/release.json';
    }

    /**
     * Initialize plugin.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        craft()->on('assets.onSaveAsset', function (Event $event) {
            $this->copyAsset($event->params['asset']);
        });

        craft()->on('assets.onReplaceFile', function (Event $event) {
            $this->copyAsset($event->params['asset']);
        });

        craft()->on('assets.onBeforeDeleteAsset', function (Event $event) {
            $this->deleteAsset($event->params['asset']);
        });
    }

    /**
     * Get settings model.
     *
     * @access protected
     * @return \JonnyW\AWSS3Assets\Model\Settings
     */
    protected function getSettingsModel()
    {
        return new Settings();
    }

    /**
     * Get settings HTML for plugin
     * CP screen.
     *
     * @access public
     * @return string
     */
    public function getSettingsHtml()
    {
       return craft()->templates->render('awss3assets/_settings', array(
           'settings' => $this->getSettings()
       ));
    }

    /**
     * Get S3 Bucket instance.
     *
     * @access private
     * @return \JonnyW\AWSS3Assets\S3Bucket
     */
    private function getS3Bucket()
    {
        $settings = $this->getSettings();

        $bucket = S3Bucket::getInstance(
            $settings->bucketRegion,
            $settings->bucketName,
            $settings->awsKey,
            $settings->awsSecret
        );

        return $bucket;
    }

    /**
     * Copy asset to bucket.
     *
     * @access public
     * @param  \Craft\AssetFileModel $asset
     * @return void
     */
    public function copyAsset(AssetFileModel $asset)
    {
        try {
            $this->getS3Bucket()->cp($this->getAssetPath($asset), $asset->filename);
        } catch (\Exception $e) {
            throw new CraftException($e->getMessage());
        }
    }

    /**
     * Delete asset from bucket.
     *
     * @access public
     * @param  \Craft\AssetFileModel $asset
     * @return void
     */
    public function deleteAsset(AssetFileModel $asset)
    {
        try {
            $this->getS3Bucket()->rm($asset->filename);
        } catch (\Exception $e) {
            throw new CraftException($e->getMessage());
        }
    }

    /**
     * Get asset path
     *
     * @access private
     * @param  \Craft\AssetFileModel $asset
     * @return string
     * @throws \Exception
     */
    private function getAssetPath(AssetFileModel $asset)
    {
        if ($asset->getSource()->type != 'Local') {
            throw new Exception(Craft::t('Could not get asset upload path as source is not "Local"'));
        }

        $sourcePath = $asset->getSource()->settings['path'];
        $folderPath = $asset->getFolder()->path;

        return $sourcePath . $folderPath . $asset->filename;
    }
}
