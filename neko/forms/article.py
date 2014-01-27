# -*- coding: utf-8 -*-

from .base import BaseForm
from wtforms import TextField, TextAreaField
from wtforms.validators import DataRequired, Length
from ..models import Article

class CreateArticleForm(BaseForm):
    title = TextField(
        'Title',
        validators=[
            DataRequired(message="Please enter title")
        ],
    )
    content = TextAreaField(
        'Content',
        validators=[
            DataRequired(message="Please enter content")
        ]
    )

    def save(self, user):
        article = Article(**self.data)
        return article.save(user=user)
