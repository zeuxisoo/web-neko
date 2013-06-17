#!/usr/bin/env python
# coding: utf-8

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
	update_at = db.Column(db.DateTime, default=datetime.utcnow)

	def __str__(self):
		return self.username or self.email

   	def __repr__(self):
		return '<User: {0}>'.format(self.email)

	def is_match_password(self, password):
		password = '%s%s%s' % (self.salt, password, db.app.config['PASSWORD_SECRET_KEY'])
		hashed   = hashlib.sha256(password).hexdigest()
		return self.password == hashed

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
