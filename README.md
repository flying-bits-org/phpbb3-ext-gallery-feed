# phpBB 3.1 Extension - Gallery Feed Extension

## Requirement

This extension requires gallery/core to be installed.

## Installation

Clone into phpBB/ext/gallery/feed:

    git clone https://github.com/nickvergessen/phpbb3-ext-gallery-feed.git phpBB/ext/gallery/feed

Add to database by inserting a row into phpbb_ext

    INSERT INTO phpbb_ext (ext_name, ext_active, ext_state) VALUES ('gallery/feed', 0, '');

Go to "ACP" > "Customise" > "Extensions" and enable the "phpBB Gallery Feed Extension" extension.

##Tests and Continuous Intergration

[![Build Status](https://travis-ci.org/nickvergessen/phpbb3-ext-gallery-feed.png?branch=master)](https://travis-ci.org/nickvergessen/phpbb3-ext-gallery-feed)

We use Travis-CI as a continous intergtation server and phpunit for our unit testing. See more information on the [phpBB development wiki](https://wiki.phpbb.com/Unit_Tests).

## License

[GPLv2](license.txt)
