# DT167G Software Security project - grupp4

## Vagrant

### Install

1. Install vagrant on your system.
2. Run `vagrant up` in the project directory

This will install the virtual machine and setup the webserver to
http://localhost:8000 and the database to 127.0.0.1:5432.

### Usage

- `vagrant up` - start the machine
- `vagrant halt` - stop the machine
- `vagrant ssh` - ssh into the machine
- `vagrant destroy` - remove the machine
- `vagrant provision` - run the Vagrant script provision commands again.
- `vagrant box update` - update the vagrant box Database

### Database config

|         |        |
| ------- | ------ |
| DB name | group4 |
| User    | group4 |
| Pass    | test   |
| Port    | 5432   |
