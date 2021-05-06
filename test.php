<html>
<head>
    <title>Testing</title>
</head>
<body>
<ul id="content">

</ul>
<script>
    fetch('http://localhost/maskify/api/v1/hours/')
        .then(response => response.json())
        .then(data => document.getElementById('content').innerHTML = JSON.stringify(data))
</script>
</body>
</html>