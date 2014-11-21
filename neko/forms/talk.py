# -*- coding: utf-8 -*-

from .base import BaseForm
from wtforms import TextAreaField
from wtforms.validators import DataRequired
from ..models import Talk

class CreateTalkForm(BaseForm):
    content = TextAreaField(
        'Content',
        validators=[
            DataRequired(message="Please enter content")
        ]
    )

    def save(self, user, service=None):
        data = self.data

        if service is not None:
            data['service'] = service

        talk = Talk(**data)
        return talk.save(user=user)
