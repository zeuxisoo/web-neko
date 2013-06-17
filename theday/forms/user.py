#!/usr/bin/env python
# coding: utf-8

from .base import BaseForm
from flask.ext.wtf import TextField, PasswordField, BooleanField
from flask.ext.wtf import DataRequired, Length
from ..models import User

class SigninForm(BaseForm):
	account = TextField(
		'Account',
		validators=[
			DataRequired(message="Please enter account name"),
			Length(min=3, max=200)
		],
	)
	password = PasswordField(
		'Password',
		validators=[
			DataRequired(message="Please enter password")
		]
	)
	permanent = BooleanField('Remember me')

	def validate_password(self, field):
		account = self.account.data

		if '@' in account:
			user = User.query.filter_by(email=account).first()
		else:
			user = User.query.filter_by(username=account).first()

		if not user:
			raise ValueError('Account not found')

		if user.is_match_password(field.data):
			self.user = user
			return user
		else:
			raise ValueError('Password incorrect')
