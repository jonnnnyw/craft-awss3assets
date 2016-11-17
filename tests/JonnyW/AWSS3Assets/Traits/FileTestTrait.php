<?php

/*
 * This file is part of the AWS S3 Assets plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\JonnyW\AWSS3Assets\Traits;

/**
 * AWS S3 Assets
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
trait FileTestTrait
{
    /**
     * Test filename
     *
     * @var string
     * @access protected
     */
    protected $filename;

    /**
     * Test directory
     *
     * @var string
     * @access protected
     */
    protected $directory;

    /**
     * Set up test environment.
     *
     * @access public
     * @return void
     * @throws \RuntimeException
     */
    public function setUp()
    {
        $this->filename  = sprintf('%s.png', uniqid());
        $this->directory = sys_get_temp_dir();

        if (!is_writable($this->directory)) {
            throw new \RuntimeException(sprintf('Test directory must be writable: %s', $this->directory));
        }

        $handle = fopen($this->getFilePath(), 'wb');
        fwrite($handle, $this->getFileContent());
        fclose($handle);
    }

    /**
     * Tear down test environment.
     *
     * @access public
     * @return void
     */
    public function tearDown()
    {
        $file = $this->getFilePath();

        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Get file name.
     *
     * @access public
     * @return string
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /**
     * Get file path.
     *
     * @access public
     * @return string
     */
    public function getFilePath()
    {
        return sprintf('%1$s/%2$s', $this->directory, $this->filename);
    }

    /**
     * Get file data.
     *
     * @access private
     * @return binary
     */
    private function getFileContent()
    {
        return base64_decode('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');
    }
}
