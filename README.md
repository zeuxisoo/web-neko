## Installation

	chmod 777 log
	chmod 666 log/*

### Install App

SQLite

    cd /usr/ports/databases/sqlite3
    make distclean
    make config
    make install clean
    cd -

Libevent for Gevent

    cd /usr/ports/devel/libevent
    make install clean
    cd -
    
Virtualenv

    easy_install -U virtualenv
    virtualenv --no-site-package --python=/usr/local/bin/python2.7 venv
    source venv/bin/activate.csh
   
For pysqlite, gevent

    setenv CFLAGS "-I /usr/local/include"
    setenv LDFLAGS "-L /usr/local/lib/"
   
Dependency
            
    pip install -r requirements.txt

Clean up

    unsetenv CFLAGS
    unsetenv LDFLAGS

### Test app

    gunicorn wsgi:app -c neko/configs/gunicorn.py

### Install cron

	/home/user/app/venv/bin/python /home/user/app/manager.py cron_twitter >> /home/user/app/log/cron.twitter.txt

### Test cron

	cd /
	/home/user/app/venv/bin/python /home/user/app/manager.py