# My project's README

# Требования
Vagrant https://www.vagrantup.com

VirtualBox https://www.virtualbox.org/

# Шаг №1
git clone path

# Шаг №2
vagrant up

# Шаг №3
composer install

# Шаг №4
composer global require "codeception/codeception=\*"

composer global require "codeception/specify=\*"

composer global require "codeception/verify=\*"

# Шаг №5
sudo ln -s ~/.composer/vendor/bin/codecept    /usr/local/bin/codecept