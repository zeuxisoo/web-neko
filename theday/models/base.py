#!/usr/bin/env python
# coding: utf-8

from flask.ext.sqlalchemy import SQLAlchemy, BaseQuery

db = SQLAlchemy()

class SessionMixin(object):
	def save(self):
		db.session.add(self)
		db.session.commit()
		return self

	def delete(self):
		db.session.delete(self)
		db.session.commit()
		return self
