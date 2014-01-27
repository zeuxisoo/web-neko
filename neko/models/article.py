# -*- coding: utf-8 -*-

import hashlib
import random
from datetime import datetime
from .base import db, SessionMixin

class Article(db.Model, SessionMixin):
    id        = db.Column(db.Integer, primary_key=True)
    user_id   = db.Column(db.Integer, db.ForeignKey('user.id'))
    title     = db.Column(db.String(50), nullable=False)
    content   = db.Column(db.Text)
    create_at = db.Column(db.DateTime, default=datetime.utcnow)
    update_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)

    def __str__(self):
        return self.title

    def __repr__(self):
        return '<Article: {0}>'.format(self.id)

    def save(self, user=None):
        # Update article
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
