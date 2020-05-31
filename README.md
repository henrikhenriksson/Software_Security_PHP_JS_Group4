# DT167G Software Security project - grupp4

## Vagrant

### Install

1. Install vagrant on your system.
2. Run `vagrant up` in the project directory

This will install the virtual machine and setup the webserver. The website can 
then be accessed at http://localhost:8000 and the database at 127.0.0.1:15432.

### Usage

- `vagrant up` - start the machine
- `vagrant halt` - stop the machine
- `vagrant ssh` - ssh into the machine
- `vagrant destroy` - remove the machine
- `vagrant provision` - run the Vagrant script provision commands again.
                        (Reconfigure docker and ubuntu without reinstall)
- `vagrant box update` - update the vagrant box

### Database config

|         |        |
| ------- | ------ |
| DB name | group4 |
| User    | group4 |
| Pass    | test   |
| Port    | 15432  |
