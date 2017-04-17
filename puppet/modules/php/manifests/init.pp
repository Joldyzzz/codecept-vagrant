class php {

  package { [
    'php7.0',
    'php7.0-fpm',
    'php7.0-cli',
    'php7.0-mysql',
    'php7.0-curl',
    'php7.0-gd',
    'php7.0-xsl',
    'php7.0-xdebug',
    'php7.0-memcache',
    'php7.0-imagick',
    'php7.0-pgsql',
    'php7.0-intl',
    'php7.0-redis',
    'php7.0-mbstring',
    #for files
    'pngquant',
    'optipng',
    'pngcrush',
    #  'pngout',
    'libjpeg-progs',
    'jpegoptim',
    'gifsicle',
    'libimage-exiftool-perl',
    'pngnq',
    'advancecomp',
    'imagemagick',
  ]:
    ensure  => present,
    notify  => Service["php7.0-fpm", "apache2"],
    require => Exec['apt-get update'],
  }

  file { 'php-apache2-php-ini':
    path    => '/etc/php/7.0/apache2/php.ini',
    ensure  => file,
    require => Package['php7.0-fpm'],
    source  => 'puppet:///modules/php/apache2/php.ini',
    force   => true,
    notify  => Service["php7.0-fpm", "apache2"],
  }
  /*	file { 'php-cli-php-ini':
      path => '/etc/php/7.0/cli/php.ini',
      ensure => file,
      require => Package['php7.0-fpm'],
      source => 'puppet:///modules/php/cli/php.ini',
      force => true,
      notify  => Service["php7.0-fpm"],
    }*/
  file { 'php-fpm-php-ini':
    path    => '/etc/php/7.0/fpm/php.ini',
    ensure  => file,
    require => Package['php7.0-fpm'],
    source  => 'puppet:///modules/php/fpm/php.ini',
    force   => true,
    notify  => Service["php7.0-fpm", "apache2"],
  }
  file { 'xdebug-ini':
    path    => '/etc/php/7.0/mods-available/xdebug.ini',
    ensure  => file,
    source  => 'puppet:///modules/php/mods-available/xdebug.ini',
    force   => true,
    require => Package['php7.0-xdebug'],
    notify  => Service["php7.0-fpm", "apache2"],
  }

  service { 'php7.0-fpm':
    ensure  => running,
    require => Package['php7.0-fpm'],
  }
}
