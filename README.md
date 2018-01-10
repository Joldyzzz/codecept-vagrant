# My project's README

[![Build Status](https://travis-ci.org/joldyzzz/codecept-vagrant.svg?branch=master)](https://travis-ci.org/joldyzzz/codecept-vagrant)

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

# Чтобы потом не искать
Устанавливаем JAVA для запуска Selenium:

apt-get install default-jdk

https://tecadmin.net/install-java-8-on-debian/