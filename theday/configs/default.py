import os

DEBUG                   = False
ASSETS_DEBUG            = False
SQLALCHEMY_DATABASE_URI = "sqlite:///{0}".format(os.path.join(os.getcwd(), 'database', 'default.sqlite'))
SECRET_KEY              = "Your_Secret_Key"

BABEL_DEFAULT_LOCALE    = "en"
BABEL_DEFAULT_TIMEZONE  = "Asia/Hong_Kong"
