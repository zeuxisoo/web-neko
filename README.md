## Prepare

	chmod 777 log
	chmod 666 log/*

## Install

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

## Start web server

    gunicorn wsgi:app -c neko/configs/gunicorn.py

## Add cron job

    crontab -e

	/path/to/app/venv/bin/python /path/to/app/manager.py cron_twitter >> /path/to/app/log/cron.twitter.txt

## Test cron job

	/path/to/app/venv/bin/python /path/to/app/manager.py cron_twitter
