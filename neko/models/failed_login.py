# -*- coding: utf-8 -*-

import hashlib
import random
from datetime import datetime, timedelta
from .base import db, SessionMixin

class FailedLogin(db.Model, SessionMixin):
    id        = db.Column(db.Integer, primary_key=True)
    username  = db.Column(db.String(40), nullable=False, index=True)
    ip_address= db.Column(db.String(20), nullable=False)
    failures  = db.Column(db.Integer, default=0)
    create_at = db.Column(db.DateTime, default=datetime.utcnow)
    update_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)

    def __str__(self):
        return self.username

    def __repr__(self):
        return '<FaildedLogin: {0}>'.format(self.id)

    def too_many_failures(self, failures=3):
        return self.failures >= failures

    def recent_failure(self, ban_minutes=3):
        return datetime.utcnow() < self.update_at + timedelta(minutes=ban_minutes)

    def blocked(self):
        return self.too_many_failures() and self.recent_failure()
