# -*- coding: utf-8 -*-

import hashlib
import random
from datetime import datetime, timedelta
from .base import db, SessionMixin

class LoginRecord(db.Model, SessionMixin):
    id         = db.Column(db.Integer, primary_key=True)
    account    = db.Column(db.String(40), nullable=False, index=True)
    ip_address = db.Column(db.String(20), nullable=False)
    user_agent = db.Column(db.Text)
    create_at  = db.Column(db.DateTime, default=datetime.utcnow)
    update_at  = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)

    def __str__(self):
        return self.account

    def __repr__(self):
        return '<LoginRecord: {0}>'.format(self.id)
