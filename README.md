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

If you're new to git, [Code School has a nice free (and fun) introduction to get started with](http://try.github.io/levels/1/challenges/1).

The workflow is based on these rules:

1. The master branch is "sacred", ie it should always be in working condition/deployable.
2. All new work is done on branches
3. Branced are merged into master via peer-reviewed pull requests on GitHub

The basic workflow commands are: 

```bash
git checkout master # move to local master branch
git pull origin master # pull latest changes from master branch on "origin" (=GitHub)
git checkout my_new_feature -b # create and move to new local branch named "my_new_feature"
git commit, git commit, git commit... # Work and do as many commits as you want
git push origin my_new_feature # push finished branch to GitHub
open https://github.com/kbhff/membersystem # Go to GitHub and create a pull request
```
