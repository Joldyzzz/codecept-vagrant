file {'php-dotdeb-install-souce':
    path    => '/etc/apt/sources.list.d/dotdeb.list',
    ensure  => file,
    replace => 'yes',
    source  => 'puppet:///modules/php/sources/dotdeb.list',
}

exec { 'php-dotdeb-install-key':
  command => 'wget -qO - https://www.dotdeb.org/dotdeb.gpg | sudo apt-key add -',
  path => '/usr/bin',
}

exec { 'apt-get update':
  path => '/usr/bin',
}

package { 'vim':
  ensure => present,
}

# set global path variable for project
# http://www.puppetcookbook.com/posts/set-global-exec-path.html
Exec { path => [ "/bin/", "/sbin/" , "/usr/bin/", "/usr/sbin/", "/usr/local/bin", "/usr/local/sbin", "~/.composer/vendor/bin/" ] }

/*
class { 'apache': }


apache::vhost { 'vhost.example.com':
  port    => '443',
  docroot => '/var/www/vhost',
}
*/

#file { '/var/www/':
#  ensure => 'directory',
#}

include php, apache, composer