<?php

/*
 * This file is part of the AWS S3 Assets plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\AWSS3Assets;

use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use Aws\Credentials\Credentials;
use JonnyW\AWSS3Assets\Exception\ObjectNotFoundException;

/**
 * AWS S3 Assets
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class S3Bucket
{
    /**
     * S3 Bucket instance.
     *
     * @var \JonnyW\AWSS3Assets\S3Bucket
     * @access private
     */
    private static $instance;

    /**
     * S3 client
     *
     * @var \Aws\S3\S3ClientInterface
     * @access protected
     */
    protected $client;

    /**
     * Bucket name
     *
     * @var string
     * @access protected
     */
    protected $name;

    /**
     * Internal constructor.
     *
     * @access public
     * @param \Aws\S3\S3ClientInterface $client
     * @param string                    $name
     */
    public function __construct(S3ClientInterface $client, $name)
    {
        $this->client = $client;
        $this->name   = $name;
    }

    /**
     * Get singleton instance.
     *
     * @access public
     * @static
     * @param  string                       $region
     * @param  string                       $name
     * @param  string                       $key    (default: null)
     * @param  string                       $secret (default: null)
     * @return \JonnyW\AWSS3Assets\S3Bucket
     */
    public static function getInstance($region, $name, $key = null, $secret = null)
    {
        if (!self::$instance instanceof \JonnyW\AWSS3Assets\S3Bucket) {

            $args = array(
                'version' => 'latest',
                'region'  => $region
            );

            if (!empty($key) && !empty($secret)) {
                $args['credentials'] = new Credentials($key, $secret);
            }

            $client = new S3Client($args);

            self::$instance = new static(
                $client,
                $name
            );
        }

        return self::$instance;
    }

    /**
     * Copy media.
     *
     * @access public
     * @param  string            $path
     * @param  string            $filename
     * @return \Aws\Result|false
     */
    public function cp($path, $filename)
    {
        if (!file_exists($path)) {
            return false;
        }

        $handle = fopen($path, 'r');
        $body   = fread($handle, filesize($path));

        fclose($handle);

        $result = $this->client
            ->putObject(array(
                'Bucket' => $this->name,
                'Key'    => $filename,
                'Body'   => $body
            ));

        return $result;
    }

    /**
     * Remove media.
     *
     * @access public
     * @param  string      $filename
     * @return \Aws\Result
     */
    public function rm($filename)
    {
        $result = $this->client
            ->deleteObject(array(
                'Bucket' => $this->name,
                'Key'    => $filename
            ));

        return $result;
    }

    /**
     * Get media.
     *
     * @access public
     * @param  string                                                $filename
     * @return \Aws\Result
     * @throws \Aws\S3\Exception\S3Exception
     * @throws \JonnyW\AWSS3Assets\Exception\ObjectNotFoundException
     */
    public function get($filename)
    {
        try {

            $result = $this->client
                ->getObject(array(
                    'Bucket' => $this->name,
                    'Key'    => $filename
                ));

        } catch (\Aws\S3\Exception\S3Exception $e) {

            if ($e->getStatusCode() == '404') {
                throw new ObjectNotFoundException($e->getMessage());
            }

            throw $e;
        }

        return $result;
    }
}
