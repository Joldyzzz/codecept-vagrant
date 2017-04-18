REQUIRED_PLUGINS = %w(vagrant-hostmanager)

plugins_to_install = REQUIRED_PLUGINS.select { |plugin| not Vagrant.has_plugin? plugin }
if not plugins_to_install.empty?
  puts "Installing plugins: #{plugins_to_install.join(' ')}"
  if system "vagrant plugin install #{plugins_to_install.join(' ')}"
    exec "vagrant #{ARGV.join(' ')}"
  else
    abort "Installation of one or more plugins has failed. Aborting."
  end
end

Vagrant.configure("2") do |config|
  config.vm.box = "debian/jessie64"
  config.vm.box_version = "8.2.1"
  config.vm.box_check_update = false
  config.vm.network :forwarded_port, host: 2223, guest: 22, host_ip: "127.0.0.1", id: 'ssh'
  config.vm.network :forwarded_port, host: 5556, guest: 80, host_ip: "127.0.0.1", id: 'tcp5555'
  config.vm.network :forwarded_port, host: 55556, guest: 55555, host_ip: "127.0.0.1", id: 'tcp55555'

  config.vm.network :private_network, ip: "55.55.55.10"

  config.vm.synced_folder ".", "/vagrant", :mount_options => ["dmode=777","fmode=666"]

#  config.vm.provider :virtualbox do |vb|
#      vb.gui = true
#  end

  unless Vagrant.has_plugin?('vagrant-hostmanager')
      puts 'vagrant-hostmanager plugin is not installed!'
  else
      config.hostmanager.enabled = true
      config.hostmanager.manage_host = true
      config.hostmanager.ignore_private_ip = false
      config.hostmanager.include_offline = true
      config.hostmanager.aliases = [
      "codecept.vagrant",
      "www.codecept.vagrant"
      ]
      puts 'hosts updated'
  end

  config.vm.provision "fix-no-tty", type: "shell" do |s|
      s.privileged = false
      s.inline = "sudo sed -i '/tty/!s/mesg n/tty -s \\&\\& mesg n/' /root/.profile"
  end

  config.vm.provision "shell", path: "puppet/debian.sh"


      config.vm.provision :puppet do |puppet|
        puppet.manifests_path = "puppet/manifests"
        puppet.module_path = "puppet/modules"
        puppet.manifest_file  = "init.pp"
  #       puppet.options="--verbose --debug"
      end

end