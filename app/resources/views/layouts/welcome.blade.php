<!DOCTYPE html>
<html >
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{{ asset('static/img/paperyard_logo.png') }}}">
    <title>Welcome</title>


<style type="text/css" media="screen">
@import url("https://fonts.googleapis.com/css?family=Open+Sans:300,400");
.text-js {
  opacity: 0;
}

.cursor {
  display: block;
  position: absolute;
  height: 100%;
  top: 0;
  right: -5px;
  width: 2px;
  /* Change colour of Cursor Here */
  background-color: white;
  z-index: 1;
  -webkit-animation: flash 0.5s none infinite alternate;
          animation: flash 0.5s none infinite alternate;
}

@-webkit-keyframes flash {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}

@keyframes flash {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}
* {
  margin: 0;
  padding: 0;
  boz-sizing: border-box;
  font-family: "Open Sans", sans-serif;
}

body {
  background-image: linear-gradient(to right top, #017cff, #4193ff, #68aaff, #8cc0ff, #b1d5ff);
  height: 100vh;

}

.headline {
  margin: 20px;
  color: white;
  font-size: 26px;
  text-align: center;
  margin-top:300px;
}
.headline h1 {
  letter-spacing: 1.6px;
  font-weight: 300;
}

.lg-btn-tx {
  font-size:22px;
  color:#017cff;
  font-weight:bold;
}

.lg-btn_x2 {
  width:130px;
  height:45px;
  border:none;
  border-radius:5px
}
.lg-btn_x2:hover {
  cursor: pointer;
}

.btn_color{
  background-color:#b1d5ff
}

 </style>

  
</head>

<body>

<center>
<div class="type-js headline">
  <h1 class="text-js">Hi there! Thank you for installing Paperyard.</h1>
</div>

<div>
<button class="btn-flat btn_color main_color waves-effect lg-btn_x2 btn_no_folders" onclick="start()">
    <span class="lg-btn-tx">S t a r t </span>
</button>
</div>

</center>


  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>

<script type="text/javascript">

function autoType(elementClass, typingSpeed){
  var thhis = $(elementClass);
  thhis.css({
    "position": "relative",
    "display": "inline-block"
  });
  thhis.prepend('<div class="cursor" style="right: initial; left:0;"></div>');
  thhis = thhis.find(".text-js");
  var text = thhis.text().trim().split('');
  var amntOfChars = text.length;
  var newString = "";
  thhis.text("|");
  setTimeout(function(){
    thhis.css("opacity",1);
    thhis.prev().removeAttr("style");
    thhis.text("");
    for(var i = 0; i < amntOfChars; i++){
      (function(i,char){
        setTimeout(function() {        
          newString += char;
          thhis.text(newString);
        },i*typingSpeed);
      })(i+1,text[i]);
    }
  },100);
}

$(document).ready(function(){
   autoType(".type-js",100);

});

 function start(){
    window.location.replace('/register');
 }

</script>

</body>


</html>
