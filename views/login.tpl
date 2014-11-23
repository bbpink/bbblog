{% extends "admin.layout.tpl" %}
{% block css %}
  <style>
    h1 {font-size: 120%;}
    a {text-decoration:none;color:#995555;}
    body {margin-top: 3em;margin-left:3em;}
  </style>
{% endblock %}
{% block content %}

        <form action="{{ core.url }}/admin.php{% if model.init == false %}?m=top{% endif %}" method="post" style="margin-bottom:2em;">
          <table>
            <tr>
              <td>id: </td>
              <td><input type="text" name="id" /></td>
            </tr>
            <tr>
              <td>password: </td>
              <td><input type="password" name="password" /></td>
            </tr>
{% if model.init == true %}
            <tr>
              <td>password(confirm): </td>
              <td><input type="password" name="confirm" /></td>
            </tr>
{% endif %}
          </table>
          <input type="submit" value="{% if model.init == true %}register{% else %}login{% endif %}" style="padding:0.5em;margin-top:1em;">
        </form>

{% endblock %}
{% block menu %}{% endblock %}