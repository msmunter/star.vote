# Star.vote

This project is a tangible example of using the Score Then Automatic Runoff method for polling. Apache, PHP7, MariaDB, jQuery/Mobile.

See it live at [star.vote](https://star.vote/) or learn more about STAR voting from [The Equal Vote Coalition](http://equal.vote/).

Installation
------------
Apache home directory needs `AllowOverride All` set for pretty URLs (i.e. star.vote/pollname). For example:
```apache
<Directory /var/www/html/>
    Options FollowSymLinks
    AllowOverride All
    Order allow,deny
    allow from all
</Directory>
```