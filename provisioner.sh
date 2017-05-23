#!/usr/bin/env bash
# Set start time so we know how long the bootstrap takes
T="$(date +%s)"

#echo 'Updating'
sudo apt-get -y update

echo 'Installing Zip/Unzip'
sudo apt-get -y install zip unzip

echo 'Installing Google Chrome'
sudo apt-get -y install google-chrome-stable
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
sudo dpkg -i google-chrome-stable_current_amd64.deb
sudo apt-get -y install -f

echo 'Installing Google XVFB'
sudo apt-get -y install xvfb
sudo apt-get -y install -f

echo 'Installing JRE'
sudo apt-get -y install default-jdk
sudo apt-get -y install -f

echo 'Downloading and Moving the ChromeDriver/Selenium Server to /usr/local/bin'
cd /tmp
wget "http://chromedriver.storage.googleapis.com/2.29/chromedriver_linux64.zip"
wget "https://selenium-release.storage.googleapis.com/3.4/selenium-server-standalone-3.4.0.jar"
unzip chromedriver_linux64.zip
mv chromedriver /usr/local/bin
mv selenium-server-standalone-3.4.0.jar /usr/local/bin
export DISPLAY=:10
cd /vagrant

echo "Starting Xvfb ..."
Xvfb :10 -screen 0 1366x768x24 -ac &

echo "Starting Google Chrome ..."
google-chrome --remote-debugging-port=9222 &

echo "Starting Selenium ..."
cd /usr/local/bin
java -jar selenium-server-standalone-3.4.0.jar

# Print how long the bootstrap script took to run
T="$(($(date +%s)-T))"

echo "Time bootstrap took: ${T} seconds"