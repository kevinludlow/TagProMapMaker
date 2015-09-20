# TagProMapMaker
An HTML/JS map maker for the popular internet capture the flag game, TagPro

Since this is only just being released to the community I am still in the process of building the readme.  Please feel free to contribute as you see fit.

# Requirements

* Linux Machine
* PHP
* MySQL Database
* ImageMagick (http://www.imagemagick.org/script/install-source.php)

# Installing ImageMagick

ImageMagick is used to turn the map pieces into a visible PNG image, the same image that is used by the TagPro interpreter.  On CentOS/RHEL you can use the following steps to install ImageMagick.

* sudo yum install gcc php-devel php-pear
* sudo yum install ImageMagick ImageMagick-devel
* sudo pecl install imagick
* sudo echo "extension=imagick.so" > /etc/php.d/imagick.ini

# Setup / Configuration

* Create a database called TagProMapMaker
* Run the SQL script found in /config/TagProMapMaker.sql (this will automatically create a test user)
* Edit config/config.php

# Important

* Once you have configured your config.php file be sure to add it to your .gitignore file.  We do not want people checking in their private security keys.
