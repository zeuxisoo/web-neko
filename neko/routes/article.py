# -*- coding: utf-8 -*-

from flask import Blueprint, request, g
from flask import render_template, redirect, url_for, flash
from mistune import Markdown
from ..helpers import login_user, require_login, force_integer
from ..forms import CreateArticleForm
from ..models import Article

blueprint = Blueprint("article", __name__)

@blueprint.route('/index')
@require_login
def index():
    page = force_integer(request.args.get('page', 1), 0)

    if not page:
        return abort(404)
    else:
        paginator = Article.query.order_by(Article.update_at.desc()).paginate(page)

        return render_template("articles/index.html", paginator=paginator)

@blueprint.route('/create', methods=['GET', 'POST'])
@require_login
def create():
    form = CreateArticleForm()

    if form.validate_on_submit():
        article = form.save(g.user)
        return redirect(url_for("article.view", article_id=article.id))

    return render_template("articles/create.html", form=form)

@blueprint.route('/view/<int:article_id>')
@require_login
def view(article_id):
    article = Article.query.get_or_404(article_id)

    markdown = Markdown(escape=True)
    article.content = markdown.render(article.content)

    return render_template("articles/view.html", article=article)

@blueprint.route('/delete/<int:article_id>')
@require_login
def delete(article_id):
    article = Article.query.get_or_404(article_id)
    article.delete()

    flash(u'Deleted article: {0}'.format(article.title), 'success')

    return redirect(url_for('article.index'))

@blueprint.route('/edit/<int:article_id>', methods=['GET', 'POST'])
@require_login
def edit(article_id):
    article = Article.query.get_or_404(article_id)
    form    = CreateArticleForm(obj=article)

    if form.validate_on_submit():
        form.populate_obj(article)
        article.save()

        return redirect(url_for('article.view', article_id=article_id))

    return render_template('articles/create.html', form=form)
