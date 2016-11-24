<?php

/*
 * This file is part of the AWS S3 Assets plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\JonnyW\AWSS3Assets\Integration;

use JonnyW\AWSS3Assets\S3Bucket;

/**
 * AWS S3 Assets
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class S3BucketTest extends \PHPUnit_Framework_TestCase
{
    use \Tests\JonnyW\AWSS3Assets\Traits\FileTestTrait;

    /**
     * Test can copy new media
     * to S3 bucket
     *
     * @return void
     */
    public function testCanCopyNewMediaToS3Bucket()
    {
        $bucket = S3Bucket::getInstance('us-east-1', 'tests.jonnyw.me');
        $bucket->cp(
            $this->getFilePath(),
            $this->getFileName()
        );

        $this->assertInstanceOf('\Aws\Result', $bucket->get($this->getFileName()));
    }

    /**
     * Test can overwrite existing
     * media in S3 bucket
     *
     * @return void
     */
    public function testCanOverwriteExistingMediaInS3Bucket()
    {
        $bucket = S3Bucket::getInstance('us-east-1', 'tests.jonnyw.me');

        $path = $this->getFilePath();
        $name = $this->getFileName();

        $this->assertTrue($bucket->cp($path, $name) && $bucket->cp($path, $name));
    }

    /**
     * Test can copy new media
     * to S3 bucket in subdirectory
     *
     * @return void
     */
    public function testCanCopyNewMediaToS3BucketInSubDirectory()
    {
        $file = 'assets/' . $this->getFileName();

        $bucket = S3Bucket::getInstance('us-east-1', 'tests.jonnyw.me');
        $bucket->cp(
            $this->getFilePath(),
            $file
        );

        $this->assertInstanceOf('\Aws\Result', $bucket->get($file));
    }

    /**
     * Test can delete media from
     * S3 bucket
     *
     * @return void
     */
    public function testCanDeleteMediaFromS3Bucket()
    {
        $this->setExpectedException('\JonnyW\AWSS3Assets\Exception\ObjectNotFoundException');

        $bucket = S3Bucket::getInstance('us-east-1', 'test.jonnyw.me');
        $bucket->cp(
            $this->getFilePath(),
            $this->getFileName()
        );

        $bucket->rm($this->getFileName());
        $bucket->get($this->getFileName());
    }
}
