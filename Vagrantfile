# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'ffi'

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/xenial64"

  config.vm.network "forwarded_port", guest: 80, host: 9999

  if FFI::Platform::IS_MAC
    config.vm.provider "virtualbox" do |v|
      # Give VM 1/4 system memory & access to all cpu cores on the host
      cpus = [`sysctl -n hw.ncpu`.to_i / 2, 1].max
      # sysctl returns Bytes and we need to convert to MB
      mem = `sysctl -n hw.memsize`.to_i / 1024 / 1024 / 4

      v.customize ["modifyvm", :id, "--memory", mem]
      v.customize ["modifyvm", :id, "--cpus", cpus]
    end
  end

  # Ansible provisioning
  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "docs/ansible/dev/site.yml"
  end

  # Import local setting
  custom_vagrantfile = 'Vagrantfile.local'
  external = File.read custom_vagrantfile if File.exist?(custom_vagrantfile)
  eval external if not external.nil?
end
