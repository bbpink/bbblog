{% extends "admin.layout.tpl" %}
{% block content %}

        <ul>
{% for v in model.backnumbers %}
          <li id="entry{{ v.id }}"><button class="button-edit">edit</button><button class="button-delete">delete</button><span class="title">{{ v.title }}</span><span class="created"> at {{ v.created }}</span></li>
{% endfor %}
        </ul>

{% endblock %}
{% block menu%}
{{ parent() }}

        <div>
          <h2><a href="{{ core.url }}/admin.php?m=top">記事を書く</a></h2>
        </div>
{% endblock %}