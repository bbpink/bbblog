<!DOCTYPE html>

<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>{{ core.title }}{% block title %}{% endblock %}</title>
  <link rel="shortcut icon" href="{{ core.url }}/favicon.ico" />
  <link rel="stylesheet" href="{{ core.url }}/index.css" />
{% block css %}{% endblock %}
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{{ core.url }}/rss.xml" />
{% block js %}{% endblock %}
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-266401-4', 'sevensenses.jp');
    ga('send', 'pageview');
  </script>
</head>

<body>

  <div id="wrap">

    <header>
      <h1><a href="{{ core.url }}/">{{ core.title }}</a></h1>
    </header>

    <div id="content">

      <div id="main">
{% block content %}
{% endblock %}
      </div>

      <nav>

        <div>
          <h2>profile</h2>
          <a href="http://bbpink.sevensenses.jp">bbpink.sevensenses.jp</a>
        </div>

        <div>
          <h2>recent</h2>
          <ul>
{% for v in model.recentTitles %}
            <li><a href="{{ core.url }}/article/{{ v.id }}">{{ v.title }}</a></li>
{% endfor %}
          </ul>
        </div>

        <div>
          <h2>backnumber</h2>
          <ul>

{% for k, v in model.backNumbers %}
            <li>{{ k }}
              <ul>
{% for vv in v %}
                <li><a href="{{ core.url }}/{{ vv.year }}/{{ vv.month }}/">{{ vv.year }}/{{vv.month}} ({{ vv.articles }})</a></li>
{% endfor %}
              </ul>
            </li>

{% endfor %}
          </ul>
        </div>

        <div>
          <h2>powered by</h2>
          <a href="https://github.com/bbpink/bbblog">bbblog</a>
        </div>

      </nav>

    </div>

    <footer>
      <p>&copy; {{ core.year }} bbpink.</p>
    </footer>

  </div>

</body>

</html>
