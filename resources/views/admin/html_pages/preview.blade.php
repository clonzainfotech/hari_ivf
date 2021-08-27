<!DOCTYPE html>
<html>
<head>
<title></title>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    img{
      margin: 0px 0px 0px 40px;
    }
    body
    {
      text-align: justify; 
      padding:20px;
      font-family: poppins-regular;
    }
@font-face {
    font-family: poppins-regular;
    src: url('../fonts/Poppins-Regular.ttf');
}
  </style>
</head>
<body>

@php
    $data = !empty($html_page->description) ? $html_page->description : '';
    echo htmlspecialchars_decode(stripslashes($data));
@endphp
</body>
</html>