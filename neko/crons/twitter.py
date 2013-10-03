#!/usr/bin/env python
# -*- coding: utf-8 -*-

import tweetpony
from datetime import datetime
from ..models import Talk

class Twitter:
	def connect(self):
		self.api = tweetpony.API(
			consumer_key = "",
			consumer_secret = "",
			access_token = "",
			access_token_secret = ""
		)

	def user_info(self):
		# Fetch user info
		print("User: {0}".format(self.api.user.screen_name))
		print("Date: {0}".format(datetime.now()))
		print("===" * 15)

	def fetch(self):
		# Add tweet
		talk = Talk.query.order_by(Talk.id.desc()).filter(Talk.service == "twitter").limit(0).first()

		for timeline in reversed(self.api.user_timeline(include_rts=False)):
			if talk is None or timeline.created_at > talk.create_at:
				talk = Talk(
					user_id   = 1,
					service   = "twitter",
					content   = timeline.text,
					create_at = timeline.created_at
				)
				talk.save()

				print("{0} {1} {2}".format(
					timeline.id,
					timeline.created_at,
					timeline.text.encode("utf8")
				))

		print("===" * 15)
