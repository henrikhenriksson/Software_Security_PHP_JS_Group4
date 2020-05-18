# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  config.vm.box = "ubuntu/bionic64"

  config.vm.network "forwarded_port", guest:8000, host:8000  # Http
  config.vm.network "forwarded_port", guest:5432, host:5432  # PostgreSQL

  config.vm.synced_folder "php/", "/php"
  config.vm.synced_folder "docker/", "/docker"
  config.vm.synced_folder "sql/", "/sql"

  config.vm.provision "shell", path: "setup.sh"

  config.vm.provision "docker" do |d|
    d.build_image "/docker/webserver", # Path to dockerfile context
      args: "-t 'php_server'"          # Name of docker image to use
    d.build_image "/docker/postgres",
      args: "-t 'psql-api-front'"
    d.run "php_server",
      args: "-p '8000:80' --mount type=bind,src=/php/,dst=/var/www/html" # vm -> docker php_server
    d.run "psql-api-front",
      args: "-p '5432:5432'" # vm -> docker psql-api-front
  end

end
