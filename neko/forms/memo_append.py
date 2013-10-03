# -*- coding: utf-8 -*-

from .base import BaseForm
from wtforms import TextAreaField
from wtforms.validators import DataRequired
from ..models import MemoAppend

class CreateMemoAppendForm(BaseForm):
	content = TextAreaField(
		'Content',
		validators=[
			DataRequired(message="Please enter append content")
		]
	)

	def save(self, user, memo):
		memo_append = MemoAppend(**self.data)
		return memo_append.save(user=user, memo=memo)
