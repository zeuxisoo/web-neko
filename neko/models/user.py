# -*- coding: utf-8 -*-

import hashlib
import random
from datetime import datetime
from .base import db, SessionMixin

class User(db.Model, SessionMixin):
	id        = db.Column(db.Integer, primary_key=True)
	username  = db.Column(db.String(40), nullable=False, unique=True, index=True)
	email     = db.Column(db.String(200), nullable=False, unique=True, index=True)
	password  = db.Column(db.String(100), nullable=False)
	salt      = db.Column(db.String(16), nullable=False)
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
		password = '%s%s%s' % (self.salt, password, db.app.config['PASSWORD_SECRET_KEY'])
		hashed   = hashlib.sha256(password).hexdigest()
		return self.password == hashed

	@property
	def hashed_username(self):
		return hashlib.sha256(self.username + str(self.create_at)).hexdigest().lower()[:10]

	@staticmethod
	def create_salt(length = 8):
		chars  = ('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
		string = ''.join([random.choice(chars) for i in xrange(length)])
		return string

	@staticmethod
	def create_password(password):
		salt     = User.create_salt(8)
		password = '%s%s%s' % (salt, password, db.app.config['PASSWORD_SECRET_KEY'])
		hashed   = hashlib.sha256(password).hexdigest()
		return {
			"salt": salt,
			"content": hashed
		}

	def change_password(self, raw):
		password_dict = self.create_password(raw)

		self.password = password_dict['content']
		self.salt     = password_dict['salt']

		return self
