<!DOCTYPE html>

<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>{{ core.title }}{% block title %}{% endblock %}(administration)</title>
  <link rel="stylesheet" href="{{ core.url }}/admin.css" />
{% block css %}{% endblock %}
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
{% block js %}{% endblock %}
  <script src="{{ core.url }}/javascripts/admin.js"></script>
</head>

<body>

  <div id="wrap">

    <header>
      <h1><a href="{{ core.url }}/admin.php">{{ core.title }}(administration)</a></h1>
    </header>

    <div id="content">

      <div id="main">
{% block content %}
{% endblock %}
      </div>

      <nav>
{% block menu %}
        <div>
          <h2>
            <a href="" onclick="document.logout.submit();return false;">ログアウト</a>
            <form name="logout" method="post" action="{{ core.url }}/admin.php"><input type="hidden" name="logout" value="1" /></form>
          </h2>
        </div>
{% endblock %}
      </nav>

    </div>

    <footer>
      <p>&copy; {{ core.year }} bbpink.</p>
    </footer>

  </div>

</body>

</html>
