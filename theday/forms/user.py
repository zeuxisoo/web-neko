#!/usr/bin/env python
# coding: utf-8

from .base import BaseForm
from flask.ext.wtf import TextField, PasswordField, BooleanField
from flask.ext.wtf import DataRequired, Length

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
