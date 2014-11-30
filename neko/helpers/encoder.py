# -*- coding: utf-8 -*-

import hashlib

def talk_username(value, talk_create_at):
    return hashlib.sha256(value + str(talk_create_at)).hexdigest().lower()[:10]

def memo_username(value, memo_create_at):
    return hashlib.sha224(value + str(memo_create_at)).hexdigest().lower()[:10]
