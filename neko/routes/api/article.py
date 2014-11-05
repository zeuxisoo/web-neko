# -*- coding: utf-8 -*-

from flask import request, jsonify, url_for, g
from ...helpers import force_integer
from ...helpers.json import json_error, json_form_errors, json_require_login
from ...helpers.blueprint import Blueprint
from ...models import Article
from ...forms import CreateArticleForm

blueprint = Blueprint("api_article", __name__)

@blueprint.route('/')
@json_require_login
def index():
    page = force_integer(request.args.get('page', 1), 1)

    paginator = Article.query.order_by(Article.update_at.desc()).paginate(page, 12)
    prev_page = url_for('api_article.index', page=page-1, _external=True) if paginator.has_prev else None
    next_age  = url_for('api_article.index', page=page+1, _external=True) if paginator.has_next else None

    return jsonify(
        items = [article.to_json() for article in paginator.items],
        prev  = prev_page,
        next  = next_age,
        count = paginator.total
    )

@blueprint.route('/show/<int:article_id>')
@json_require_login
def show(article_id):
    article = Article.query.get_or_404(article_id)

    return jsonify(article.to_json())

@blueprint.route('/create', methods=['POST'])
@json_require_login
def create():
    form = CreateArticleForm(csrf_enabled=False)

    if form.validate_on_submit():
        article = form.save(g.user)
        return jsonify(article.to_json())
    else:
        return json_form_errors(form)

@blueprint.route('/update/<int:article_id>', methods=['POST'])
@json_require_login
def update(article_id):
    article = Article.query.get_or_404(article_id)
    form    = CreateArticleForm(obj=article, csrf_enabled=False)

    if form.validate_on_submit():
        form.populate_obj(article)

        article = article.save()

        return jsonify(article)
    else:
        return json_form_errors(form)

@blueprint.route('/delete/<int:article_id>')
@json_require_login
def delete(article_id):
    article = Article.query.get_or_404(article_id)
    article.delete()

    return jsonify(status=200, message='Article deleted')
