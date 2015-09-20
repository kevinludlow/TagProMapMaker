# TagProMapMaker
An HTML/JS map maker for the popular internet capture the flag game, TagPro

Since this is only just being released to the community I am still in the process of building the readme.  Please feel free to contribute as you see fit.

Live version available at: http://www.tagpromapmaker.com/

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

### Build to 2015 Spec
* Add new tiles (diagonals, yellow flags, gravity well, etc)
* Fix JSON builder (for buttons, gates, and portals)
* Include Mars Ball!
* Include Potatos and Gravity
* Ensure cross-browser compatibility

### Ease-of-Use Functionality
* Allow the user to click and hold while drawing
* Allow the user to change the size of the map

## Nice to Have
* Ranking system for other users
* Better/faster testing options
* Better importing and exporting of maps
* Multi-user collaborative map editing

### Design
* Better logo using some combination of a bee and something relevant to TagPro

# Known Bugs and Design Flaws

I will try to keep this updated as best I can. If you come across a bug or have a feature request, feel free to email me at the address above.

* Editor takes up too much screen real-estate
   I know. There are many ways I could change this so that the allocation of real-estate was more fitting of your small monitor, but all of this tends to result in excess coding and clunky Javascripting. Given the program is intended as a design tool, my current response to this is just that you'll have to scroll a bit or use a larger monitor (I use a pretty basic Soyo 26" connected to my MacBook and it has ample room).

* No functionality to assign gates to buttons
   This is pretty much the last thing I need to incorporate into the codebase. I should have this working in the next day or two. For now, feel free to build maps. The changes will all be backwards-compatible.

* Shortcut Keys Not Working in Safari
   The shortcut keys for quickly selecting brushes are currently not working in Safari. This is due to the keycodes being reported by Javascript, but I have yet to look into why there is a difference. You can still click on the tool to select it.

# Version History

* version 2.00.00
   * Open-sourcing project
* version 1.13.11
   * fixed JS glitch that was causing bombs from properly loading
   * standardized site navigation menu
   * added "Active Maps" section allowing people to view other people's maps
   * fixed additional DNS issues
* version 1.05.09
   * added support for red and blue team tiles
*     version 1.03.09
   * added four different brush sizes to expedite the design process (these only apply to blank, wall, and floor cells)
   * fixed DNS issues
*     version 1.01.07
   * added functionality for assigning buttons to switches and bombs
   * added map author to map editor functionality (also included in JSON output)
   * JSON output is now fully functioning with buttons, switches, and bombs
   * further separated options on the map editor site navigation
*     version 1.00.05
   * fixed map PNG generator issues
   * hard-coded TagPro server IP to speed up connection problems (due to DNS resolve being slow)
*     version 1.00.03
   * added functionality allowing maps to be directly tested from within the map editor
   * fixed javascript bug causing map saving text to be distorted
   * built initial error checking (currently limited to flag usage) for map completion
*     version 1.00.00
   * initial release

