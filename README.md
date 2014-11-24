# bbblog
the lightweight blog system using php & sqlite

## features
- consists of four views / entrance / article (parmanent link) / backnumber / administration
- easy install & setup
- assist tools for posting
- visit [sample blog](http://sevensenses.jp/bbpink/ "sample blog")

## requirements
- PHP 5.5 up
- web server and PHP environment (ex. apache module, php-fpm and nginx)
- SQLite 3

## installing
1. git clone https://github.com/bbpink/bbblog.git
1. grant write permission to bbblog/data and subdirectories with PHP environment
1. set [production_host] section to blog root URI at config.yml
1. create symlink to bbblog/public from web server's public (or set root directory on web-server config file)
1. access to bbblog (on your web browser)
1. input ID/password for administration
1. write articles at /admin.php and read articles at /

## settings
You can modify the config file (bbblog/config.yml), like following.
- [production] not supported.
- [production_host] blog's root URI.
- [development_host] not supported.
- [production_title] blog title appear at title tag.
- [development_title] not suppoted.
- [template_dir] set view file directory.
- [template_cache] set cache directory used by Twig template engine.
- [stylesheet_dir] set style file directory.
- [stylesheet_cache] set cache directory used by scss compiling.
- [db_path] set database path(absolute).
- [development_contact] not supported.
- [production_contact] not supported.

## copyright
&copy;2014- [bbpink](http://bbpink.sevensenses.jp "bbpink").  
bbblog can be copied and changed under [new BSD License](http://opensource.org/licenses/BSD-3-Clause "new BSD License").