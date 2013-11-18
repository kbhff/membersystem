membersystem
============

KBHFF member system

## Installation

### Clone the repo

```
git clone git@github.com:kbhff/membersystem.git
```

### Create and import database

```
echo "create database membersystem" | mysql -u root -p
mysql -u root -p membersystem < kbhff.sql
```
### Testdata are two departments, one administrator, one member
### Administrator: Memberno: 1, password: 1234

### Copy config file sample (and edit as needed)

Don't edit the sample file directly!

```
cp global_config.sample.php global_config.php
```

## Install phpmailer:
apt-get install libphp-phpmailer
## You may need:  ln -s  /usr/share/php/libphp-phpmailer/class.phpmailer.php /usr/share/php/class.phpmailer.php

## Create on-tracked directory manually
mkdir CodeIgniter_2.0.2/application/logs/

## Git Workflow

TBA
