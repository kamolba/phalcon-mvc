<!DOCTYPE html>
<html>

<head>
    {% block head %}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="DESC" />
    <link rel="shortcut icon" href="/favicon.ico">
    {% endblock %}
    {% block title %}
        {{ get_title() }}
    {% endblock %}
    {{ assets.outputCss() }}
</head>

<body>
    {% block content %}
        {{ content() }}
    {% endblock %}
    {{ assets.outputJs() }}
</body>

</html>
