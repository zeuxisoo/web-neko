# -*- coding: utf-8 -*-

from flask import Blueprint as _Blueprint
from flask import request

class Blueprint(_Blueprint):

    def __init__(self, *args, **kwargs):
        self.parent = super(Blueprint, self)
        self.parent.__init__(*args, **kwargs)

        self.register_404_handler()

    def register_404_handler(self):
        @self.parent.errorhandler(404)
        def handle_404(error=None):
            return json_error(404, 'Not Found: ' + request.url)
