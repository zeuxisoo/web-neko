# -*- coding: utf-8 -*-

from .base import BaseForm
from wtforms import TextAreaField
from wtforms.validators import DataRequired
from ..models import Memo

class CreateMemoForm(BaseForm):
    content = TextAreaField(
        'Content',
        validators=[
            DataRequired(message="Please enter content")
        ]
    )

    def save(self, user):
        memo = Memo(**self.data)
        return memo.save(user=user)
