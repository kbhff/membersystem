membersystem
============

KBHFF member system

## Installation

### Clone the repo

```bash
git clone git@github.com:kbhff/membersystem.git
```

### Create and import database

This will load the following test data: Two departments, one administrator, one member.
Admin account is: `Memberno: 1, password: 1234`

```bash
echo "create database membersystem" | mysql -u root -p
mysql -u root -p membersystem < kbhff.sql
```

### Copy config file sample (and edit as needed)

Don't edit the sample file directly!

```bash
cp global_config.sample.php global_config.php
```

### Install phpmailer

```bash
apt-get install libphp-phpmailer
# You may need to:  
# ln -s  /usr/share/php/libphp-phpmailer/class.phpmailer.php /usr/share/php/class.phpmailer.php
```

## Git Workflow

TBA
