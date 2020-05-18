# -*- mode: ruby -*-
# vi: set ft=ruby :

# Ports
$psql_port = 15432
$https_port = 8080  # Obs ändra redirect port i docker/webserver/dockerfile
$http_port = 8000

# Initial bootstrap script
$boot_script = "setup.sh"

# Dirs to forward
$web_dir = "php/"
$docker_dir = "docker/"

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  config.vm.box = "ubuntu/bionic64"

  config.vm.network "forwarded_port", guest:8000, host:$http_port   # HTTP
  config.vm.network "forwarded_port", guest:8080, host:$https_port     # HTTPS
  config.vm.network "forwarded_port", guest:5432, host:$psql_port   # PostgreSQL

  config.vm.synced_folder $web_dir, "/php"       # The web directory
  config.vm.synced_folder $docker_dir, "/docker" # Contains docker files to build

  config.vm.provision "shell", path: $boot_script

  config.vm.provision "docker" do |d|
    d.build_image "/docker/webserver", # Path to dockerfile context
      args: "-t 'php_server'"          # Name of docker image to use
    d.build_image "/docker/postgres",
      args: "-t 'psql-api-front'"
    d.run "psql-api-front",
      args: "-p '5432:5432'" # vm -> docker psql-api-front
  end

  config.trigger.after :up do |trigger|
    trigger.info = "Starting web server"
    trigger.run_remote = {path: "start_webserver.sh"}
  end

end
