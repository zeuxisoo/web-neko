# -*- coding: utf-8 -*-

import hashlib
import random
from datetime import datetime
from .base import db, SessionMixin

class Memo(db.Model, SessionMixin):
    id        = db.Column(db.Integer, primary_key=True)
    user_id   = db.Column(db.Integer, db.ForeignKey('user.id'))
    content   = db.Column(db.Text)
    create_at = db.Column(db.DateTime, default=datetime.utcnow)
    update_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)

    memo_appends = db.relationship('MemoAppend', backref='memo', lazy='dynamic')

    def __str__(self):
        return self.content

    def __repr__(self):
        return '<Memo: {0}>'.format(self.id)

    def save(self, user=None):
        # Update memo
        if self.id:
            db.session.add(self)
            db.session.commit()
            return self

        # Set and Update related user
        if user:
            self.user_id = user.id
            db.session.add(user)

        db.session.add(self)
        db.session.commit()

        return self
