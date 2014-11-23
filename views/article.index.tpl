{% extends "index.layout.tpl" %}
{% block content %}

        <article>
          <h2><a href="{{ core.url }}/article/{{ model.id }}">{{ model.title }}</a></h2>
          {% autoescape false %}{{ model.body }}{% endautoescape %}

          <div class="article-footer">
            <p class="article-date">at {{ model.created }}</p>
          </div>
        </article>

{% endblock %}
