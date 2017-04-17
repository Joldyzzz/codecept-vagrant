class nginxz {

	file { '/var/www':
	  ensure  => 'link',
	  target  => '/vagrant',
		force => true
	}

	package { 'nginx':
	  ensure => 'present',
	  require => Exec['apt-get update'],
	}

	file { 'vagrant-nginx':
		path => '/etc/nginx/nginx.conf',
		ensure => file,
		require => Package['nginx'],
		source => 'puppet:///modules/nginxz/nginx.conf',
		force => true,
		notify  => Service["nginx"],
	}
	file { 'vagrant-nginx-codecept-available':
		path => '/etc/nginx/sites-available/codecept',
		ensure => file,
		source => 'puppet:///modules/nginxz/codecept',
		force => true,
		require => Package['nginx'],
		notify  => Service["nginx"],
	}
	file { '/etc/nginx/sites-enabled/default':
		ensure  => 'link',
		target  => '/etc/nginx/sites-available/codecept',
		force => true
	}
#	file { 'vagrant-nginx-default-delete':
#		path => '/etc/nginx/sites-available/default',
#		ensure => absent,
#		require => Package['nginx'],
#		notify  => Service["nginx"],
#	}
#	file { 'vagrant-nginx-default-enabled-delete':
#		path => '/etc/nginx/sites-enabled/default',
#		ensure => absent,
#		require => Package['nginx'],
#		notify  => Service["nginx"],
#	}

	service { 'nginx':
	  ensure => running,
	  require => Package['nginx'],
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
