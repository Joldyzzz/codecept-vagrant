class composer {
  exec { 'install composer':
    command => 'curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer',
    require => Package['php7.0-curl'],
    before  => Exec['composer-asset-plugin'],
    unless  => ['test -x "$(command -v composer)"']
  }
  #  exec { 'composer-home':
  #    command => 'COMPOSER_HOME=/vagrant',
  #    environment => ["COMPOSER_HOME=/vagrant"],
  #    #    cwd => '/vagrant/',
  #    require => Exec['install composer']
  #  }
  exec { 'composer-asset-plugin':
    command     => 'composer global require "fxp/composer-asset-plugin:~1.1.1"',
    environment => ["COMPOSER_HOME=/home/vagrant/.composer"],
    require     => Exec['install composer'],
    unless      => ['test -d /home/vagrant/.composer']
  }
  exec { 'composer-yii2':
    command     => 'composer create-project --stability=dev --prefer-dist yiisoft/yii2-app-basic /vagrant/yii2',
    require     => Exec['composer-asset-plugin'],
    timeout     => 1800,
    environment => ["COMPOSER_HOME=/home/vagrant/.composer"],
    unless      => ['test -d /vagrant/yii2']
  }
  file { 'auth-json':
    path        => '/home/vagrant/.composer/auth.json',
    ensure      => file,
    require     => Exec['install composer'],
    source      => 'puppet:///modules/composer/auth.json',
  }
  file { '/home/vagrant/.composer - mode':
    path        => '/home/vagrant/.composer',
    ensure      => present,
    recurse     => true,
    mode        => 0777,
    require     => Exec['composer-yii2'],
  }
}
