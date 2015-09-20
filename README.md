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

More detailed instructions can be found here: http://tecadmin.net/install-imagemagick-on-linux/#

# Setup / Configuration

* Create a database called TagProMapMaker
* Run the SQL script found in /config/TagProMapMaker.sql (this will automatically create a test user)
* Edit config/config.php

# Important

* Once you have configured your config.php file be sure to add it to your .gitignore file.  We do not want people checking in their private security keys.

# Wishlist / To-Do

This is a far from exhaustive list of things I would like to see added to the MapMaker.

* Build to 2015 Spec
** Add new tiles (diagonals, yellow flags, gravity well, etc)
** Fix JSON builder (for buttons, gates, and portals)
** Include Mars Ball!
** Include Potatos and Gravity
** Ensure cross-browser compatibility

* Ease-of-Use Functionality
** Allow the user to click and hold while drawing
** Allow the user to change the size of the map

* Nice to Have
** Ranking system for other users
** Better/faster testing options
** Better importing and exporting of maps
** Multi-user collaborative map editing

* Design
** Better logo using some combination of a bee and something relevant to TagPro
