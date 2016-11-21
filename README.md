Craft AWS S3 Assets
===================

When you upload or replace an asset in the Craft control panel, your asset will be uploaded to your AWS S3 bucket. When you delete an asset, your asset will be deleted from your S3 bucket.

This plugin is useful for serving assets from a CloudFront CDN and makes load balancing your Craft app across multiple server instances, simpler.

Requirements
---------------------

* PHP 5.6+
* AWS S3 bucket - [Documentation](http://docs.aws.amazon.com/AmazonS3/latest/gsg/CreatingABucket.html)
* AWS CloudFront distribution (optional) - [Documentation](http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/GettingStarted.html)

Installation
---------------------

It is recommended that you use [Composer](https://getcomposer.org/) to manage this plugin. If you are not using Composer for your PHP projects then it's worth taking a look at. It can be easily set up for any new or existing project and takes the hassle out of package management.

When you have Composer set up in your project, simply require the AWS S3 Assets package.

```
composer require jonnyw/craft-awss3assets
```

Setup
---------------------

### One

After installing the plugin, navigate to `Settings > Plugins` in your Craft control panel. If you have installed the plugin correctly then you should see **AWS S3 Assets** in your plugin list. If you do not see **AWS S3 Assets** listed then ensure that the plugin is install in `~/craft/plugins/awss3assets/`.

### Two

Install the **AWS S3 Assets** plugin using the `install` button in the list. At this point you may receive the following error:

`AWSS3AssetsPlugin could not locate an autoload file`

By default the plugin will look in your sites `~/vendor` folder for an autoload file. This is the default install location for Composer. If you are using a custom Composer location and you haven't already included your `autoload.php` file in Craft, then you will need to do so before enabling this plugin. See the Composer [documentation](https://getcomposer.org/doc/) for more information.

### Three

After the plugin has been successfully installed, navigate to `Settings` in your Craft control panel. You should see **AWS S3 Assets** listed under the Plugins subheading. Here you will find the following settings:

##### Bucket Region
The AWS region that you created your S3 bucket in e.g. us-east-1. Please ensure the region is the same as your bucket or you will encounter an error.

##### Bucket Name
The name of your AWS S3 bucket. This is the name your provided when you created your bucket e.g. media.mywebsite.com.

##### AWS Key (optional)
Your IAM user key. The IAM user associated with this key must have read/write access to your AWS S3 bucket.

##### AWS Secret (optional)
Your IAM user secret. The IAM user associated with this key must have read/write access to your AWS S3 bucket.

**Note** If you are running your Craft application on an EC2 instance that already has access to your S3 bucket, then you can leave the IAM user credentials blank.

### Four (optional)

If you wish to serve your assets from an AWS CloudFront distribution, then you will need to create an asset source that points to CloudFront.

Navigate to `Settings > Assets` in your Craft control panel. Click `New asset source`. Configure your asset source as your normally would with the exception of the URL field. Here you will need to add the URL of your CloudFront distribution. More information can be found on setting up a CloudFront distribution, in the [documentation](http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/GettingStarted.html).
