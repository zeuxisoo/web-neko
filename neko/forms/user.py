# -*- coding: utf-8 -*-

from flask import request, g
from flask.ext.wtf.html5 import EmailField
from wtforms import TextField, PasswordField, BooleanField
from wtforms.validators import DataRequired, Length, Email, Regexp, EqualTo
from datetime import datetime
from .base import BaseForm
from ..models import User, FailedLogin, LoginRecord

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
        #
        account    = self.account.data
        ip_address = request.remote_addr

        # Check user is or not login banned
        failed_login = FailedLogin.query.order_by(
            FailedLogin.update_at.desc()
        ).filter_by(
            username=account,
            ip_address=ip_address
        ).first()

        if failed_login and failed_login.blocked():
            raise ValueError('Login failed and Banned')

        # Check user is exists or not
        if '@' in account:
            user = User.query.filter_by(email=account).first()
        else:
            user = User.query.filter_by(username=account).first()

        if not user:
            raise ValueError('Account not found')

        # Check password
        if user.is_match_password(field.data):
            if failed_login is not None:
                failed_login.failures = 0;
                failed_login.save()

            # Save login record
            LoginRecord(account=account, ip_address=request.remote_addr, user_agent=request.user_agent.string).save()

            self.user = user
            return user
        else:
            # Add to failed table
            if failed_login is None:
                failed_login = FailedLogin(username=account, ip_address=request.remote_addr, failures=1)
                failed_login.save()
            else:
                # Reset fail count when ban is expired and more than fail count
                if failed_login.too_many_failures():
                    failed_login.failures = 0

                failed_login.failures += 1
                failed_login.save()

            raise ValueError('Password incorrect')

class ProfileForm(BaseForm):
    username = TextField(
        'Username',
        validators=[
            DataRequired(message="Please enter username"),
            Regexp(r'^[a-z0-9A-Z]+$', message="English characters only"),
            Length(min=3, max=20),
        ],
    )
    email = EmailField(
        'Email',
        validators=[
            DataRequired(message="Please enter email"),
            Email()
        ],
    )

    def validate_username(self, field):
        if User.query.filter_by(username=field.data.lower()).count():
            raise ValueError('Username already registered')

    def validate_email(self, field):
        if User.query.filter_by(email=field.data.lower()).count():
            raise ValueError('Email already registered')

class PasswordForm(BaseForm):
    old_password = PasswordField(
        'Old Password',
        validators=[
            DataRequired(message="Please enter old password")
        ]
    )
    new_password = PasswordField(
        'New Password',
        validators=[
            DataRequired(message="Please enter new password"),
            EqualTo('confirm_password', message="Password must match confirm password")
        ]
    )
    confirm_password = PasswordField(
        'confirm Password',
        validators=[
            DataRequired(message="Please enter confirm password")
        ]
    )

    def validate_old_password(self, field):
        user = User.query.filter_by(username=g.user.username).first()

        if user.is_match_password(field.data.lower()) is False:
            raise ValueError("Password incorrect")
