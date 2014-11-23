{% extends "admin.layout.tpl" %}
{% block content %}

        <form action="./admin.php?m=top" method="post">
          <input type="text" name="title" placeholder="タイトル" min="1" tabindex="1" value="{{ model.title }}" autofocus required>
          <div>
            <button id="button-anchor" type="button">a</button>
            <button id="button-strong" type="button">B</button>
            <button id="button-up1" type="button">+1</button>
            <button id="button-up2" type="button">+2</button>
            <button id="button-quote" type="button">quote</button>
            <button id="button-image" type="button">image</button>
          </div>
          <textarea id="body-editor" name="body" placeholder="内容" tabindex="2" required>{{ model.body }}</textarea>
          <input type="hidden" name="id" value="{{ model.id }}" />
          <button id="button-publish" type="submit">記事を公開</button>
        </form>

{% endblock %}
{% block menu%}
{{ parent() }}

        <div>
          <h2><a href="{{ core.url }}/admin.php?m=backnumber">記事一覧</a></h2>
        </div>
{% endblock %}