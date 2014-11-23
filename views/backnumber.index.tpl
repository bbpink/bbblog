{% extends "index.layout.tpl" %}
{% block content %}

{% for v in model.articles %}
        <article>
          <h2><a href="{{ core.url }}/article/{{ v.id }}">{{ v.title }}</a></h2>
          {% autoescape false %}{{ v.body }}{% endautoescape %}

          <div class="article-footer">
            <p class="article-date">at {{ v.created }}</p>
          </div>
        </article>

{% endfor %}
{% endblock %}
