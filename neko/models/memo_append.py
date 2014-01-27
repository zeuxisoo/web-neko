# -*- coding: utf-8 -*-

import hashlib
import random
from datetime import datetime
from .base import db, SessionMixin

class MemoAppend(db.Model, SessionMixin):
    id        = db.Column(db.Integer, primary_key=True)
    user_id   = db.Column(db.Integer, db.ForeignKey('user.id'))
    memo_id   = db.Column(db.Integer, db.ForeignKey('memo.id'))
    content   = db.Column(db.Text)
    create_at = db.Column(db.DateTime, default=datetime.utcnow)
    update_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)

    def __str__(self):
        return self.content

    def __repr__(self):
        return '<MemoAppend: {0}>'.format(self.id)

    def save(self, user=None, memo=None):
        # Update memo
        if self.id:
            db.session.add(self)
            db.session.commit()
            return self

        # Set and Update related user
        if user:
            self.user_id = user.id
            db.session.add(user)

        # Set and Update related memo
        if memo:
            self.memo_id = memo.id
            db.session.add(memo)

        db.session.add(self)
        db.session.commit()

        return self
