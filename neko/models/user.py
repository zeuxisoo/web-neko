# -*- coding: utf-8 -*-

import hashlib
import random
from flask.ext.bcrypt import Bcrypt
from datetime import datetime
from .base import db, SessionMixin

class User(db.Model, SessionMixin):
    id        = db.Column(db.Integer, primary_key=True)
    username  = db.Column(db.String(40), nullable=False, unique=True, index=True)
    email     = db.Column(db.String(200), nullable=False, unique=True, index=True)
    password  = db.Column(db.String(100), nullable=False)
    create_at = db.Column(db.DateTime, default=datetime.utcnow)
    update_at = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)

    articles     = db.relationship('Article', backref='user', lazy='dynamic')
    memos        = db.relationship('Memo', backref='user', lazy='dynamic')
    memo_appends = db.relationship('MemoAppend', backref='user', lazy='dynamic')
    talks        = db.relationship('Talk', backref='user', lazy='dynamic')

    def __str__(self):
        return self.username or self.email

    def __repr__(self):
        return '<User: {0}>'.format(self.email)

    def is_match_password(self, password):
        return Bcrypt().check_password_hash(self.password, password)

    @property
    def hashed_username(self):
        return hashlib.sha256(self.username + str(self.create_at)).hexdigest().lower()[:10]

    @staticmethod
    def create_password(password, rounds=None):
        return Bcrypt().generate_password_hash(password, rounds=rounds)

    def change_password(self, raw):
        self.password = self.create_password(raw)

        return self

    def to_json(self):
        item = {
            'id'              : self.id,
            'username'        : self.username,
            'email'           : self.email,
            'hashed_username' : self.hashed_username,
            'create_at'       : self.create_at,
            'update_at'       : self.update_at,
        }
        return item
