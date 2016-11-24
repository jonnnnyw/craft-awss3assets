<?php

/*
 * This file is part of the AWS S3 Assets plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\AWSS3Assets\Model;

use Craft\BaseModel;
use Craft\AttributeType;

/**
 * AWS S3 Assets
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Settings extends BaseModel
{
    /**
     * Define model attributes.
     *
     * @access protected
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'bucketRegion' => array(AttributeType::String, 'required' => true),
            'bucketName'   => array(AttributeType::String, 'required' => true),
            'bucketPath'   => array(AttributeType::String, 'required' => false),
            'awsKey'       => array(AttributeType::String, 'required' => false),
            'awsSecret'    => array(AttributeType::String, 'required' => false)
        );
    }

    /**
     * Set custom validtion rules.
     *
     * @access public
     * @return array
     */
    public function rules()
    {
        $rules   = parent::rules();
        $rules[] = array('bucketRegion', '\JonnyW\AWSS3Assets\Validation\RegionValidator');

        return $rules;
    }
}
