class apache {

  package { 'apache2':
    ensure  => 'present',
    require => Exec['apt-get update'],
  }
  package { 'libapache2-mod-php7.0':
    ensure  => 'present',
    require => Exec['apt-get update'],
  }
  exec { 'a2enmod rewrite':
    require     => Package['apache2'],
    notify      => Service["apache2"],
  }

  file { 'vagrant-apache-ports':
    path    => '/etc/apache2/ports.conf',
    ensure  => file,
    require => Package['apache2'],
    source  => 'puppet:///modules/apache/ports.conf',
    notify  => Service["apache2"],
  }
  file { 'vagrant-apache-sites':
    path    => '/etc/apache2/sites-available/000-default.conf',
    ensure  => file,
    require => Package['apache2'],
    source  => 'puppet:///modules/apache/000-default.conf',
    notify  => Service["apache2"],
  }
  file { 'vagrant-apache':
    path    => '/etc/apache2/apache2.conf',
    ensure  => file,
    require => Package['apache2'],
    source  => 'puppet:///modules/apache/apache2.conf',
    notify  => Service["apache2"],
  }

  service { 'apache2':
    ensure  => running,
    require => Package['apache2'],
  }

  /*	# Disable default nginx vhost
    file { 'default-nginx-disable':
        path => '/etc/nginx/sites-enabled/default',
        ensure => absent,
        require => Package['nginx'],
    }
  
    # Symlink our vhost in sites-enabled
    file { 'vagrant-nginx-enable':
        path => '/etc/nginx/sites-enabled/127.0.0.1',
        target => '/etc/nginx/sites-available/127.0.0.1',
        ensure => link,
        notify => Service['nginx'],
        require => [
            File['vagrant-nginx'],
            File['default-nginx-disable'],
        ],
    }*/
}
