<?php

/*
 * This file is part of the AWS S3 Assets plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\AWSS3Assets\Validation;

use CValidator;

/**
 * AWS S3 Assets
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class RegionValidator extends CValidator
{
    /**
     * Validate AWS region.
     *
     * @access protected
     * @param  \CModel $object
     * @param  string  $attribute
     * @return void
     */
    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;

        if ($value) {

            if (!preg_match('/^(us|sa|eu|ap)-(north|south|central)?(east|west)?-[1-9]$/', $value)) {
                $this->addError($object, $attribute, 'Please enter a valid AWS region e.g us-east-1.');
            }
        }
    }
}
