membersystem
============

KBHFF member system

## Installation

### Clone the repo

```
git clone git@github.com:kbhff/membersystem.git
```

### Replace credentials in the following files

```
/ressources/.mysql_common.php -> HOSTNAME, DBUSER, DBPASSWORD, DBNAME
/ressources/.sendmail.php -> SMTPUSERNAME, SMTPPASSWORD
/ressources/ajax/smsscript.php -> SMSSENDER, SMSPASSWORD, SMSUSER
/CodeIgniter_2.0.2/application/config/database.php -> HOSTNAME, DBUSER, DBPASSWORD, DBNAME
/CodeIgniter_2.0.2/controllers/mail.php -> SMTPUSERNAME, SMTPPASSWORD
/CodeIgniter_2.0.2/controllers/mailtest.php -> SMTPUSERNAME, SMTPPASSWORD
```

## Git Workflow

TBA
