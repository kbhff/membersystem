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

### Copy config file sample (and edit as needed)

Don't edit the sample file directly!

```
cp global_config.sample.php global_config.php
```

## Git Workflow

TBA
