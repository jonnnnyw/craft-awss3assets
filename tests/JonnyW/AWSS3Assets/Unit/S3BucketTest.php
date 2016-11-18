<?php

/*
 * This file is part of the AWS S3 Assets plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\JonnyW\AWSS3Assets\Unit;

use JonnyW\AWSS3Assets\S3Bucket;

/**
 * AWS S3 Assets
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class S3BucketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test can get bucket instance
     *
     * @return void
     */
    public function testCanGetBucketInstance()
    {
        $bucket = S3Bucket::getInstance(
            'us-east-1',
            'test.jonnyw.me',
            'testKey',
            'testSecret'
        );

        $this->assertInstanceOf('\Jonnyw\AWSS3Assets\S3Bucket', $bucket);
    }

    /**
     * Test can get bucket instance
     * without credentials
     *
     * @return void
     */
    public function testCanGetBucketInstanceWithoutCredentials()
    {
        $bucket = S3Bucket::getInstance(
            'us-east-1',
            'test.jonnyw.me'
        );

        $this->assertInstanceOf('\Jonnyw\AWSS3Assets\S3Bucket', $bucket);
    }
}
