<?php
  include_once "header.php";
?> 
<script>
$(document).ready( function(){
    //Get the canvas & context
    // var c = $('#respondCanvas');
    // var ct = c.get(0).getContext('2d');
    // var container = $(c).parent();

    //Run function when browser resizes
    /*
    $(window).resize( respondCanvas );

    function respondCanvas(){ 
        c.attr('width', $(container).width() ); //max width
        c.attr('height', $(container).height() ); //max height

        //Call a function to redraw other content (texts, images etc)
    }
    */  

    //Initial call 
    //respondCanvas();

}); </script>
    <body>
    <div class="menu">
      <div class="row-fluid">
        <div class="span10 offset1">
        <div class="row-fluid">
          <div class="span3">
            <div class="hidden-phone">
            <img src="img/logo.png" width="40%" height="40%">
            </div>
            <div class="visible-phone">
              <center><img src="img/logo.png" width="40%" height="40%"></center>
            </div>
          </div>
          <div class="span3 offset6">
          <div class="hidden-phone">
            <p class="text-right"><a href="#">PLAY</a> <a href="about.php"><b>ABOUT</b></a></p>
          </div>
          <div class="visible-phone">
            <p class="text-center"><a href="#">PLAY</a> <a href="about.php"><b>ABOUT</b></a></p>
          </div>
          </div>
        </div>
        </div><!--end span10 offset1!-->
      </div><!--end row fluid!-->
      </div><!--end menu!-->
      <!--<div class="hidden-phone">
      <div class="slider">
        <div class="row-fluid">
          <div class="span12">
            <div class="row-fluid">
              <div class="span12">
                <h1 class="text-center">Create Mazes from Images</h1><br><br><br>
                <center><img src="img/custom.gif"></center>
              </div>
            </div>
          </div>
        </div><!--end row fluid!-->
      <!--</div><!--end class slider!-->
      <!--</div><!--end hidden phone!-->
      <div class="hidden-phone">
      <div class="slider">
        <div class="row-fluid">
          <div class="span12">
            <div class="row-fluid">
              <div class="span12">
                <h1 class="text-center">Create Mazes from Images</h1><br><br><br>
                <center><img src="img/custom.gif"></center>
              </div>
            </div>
          </div>
        </div><!--end row fluid!-->
      </div><!--end class slider!-->
      </div><!--end hidden phone!-->

      <div class="features">
        <div class="row-fluid">
          <div id="topic">
          <div class="visibile phone"><br><br></div>
          <h1>Awesome Features</h1>
          </div>
        </div><!--end row fluid!-->
        <div id="features1">
        <div class="row-fluid">
          <div class="span4 offset1">
            <h2>Custom Mazes</h2>
            <p>Upload your images and we will generate stunning mazes for you to solve with your friends. The image will be the basis for the image and it is a ton of fun to play with friends when your bored.</p>
          </div><!--end span10 offset1!-->

          <div class="span4 offset2">
          <img src="./img/custom.gif">
          </div>

          </div><!--row fluid!-->
          </div>

        <div id="features2">
        <div class="row-fluid">
          <div class="span4 offset1">
             <img src="./img/imports.gif">
          </div><!--end span10 offset1!-->

          <div class="span4 offset2">
            <h2>Easy Imports</h2>
            <p>Easily import your images from a variety of online services such as FaceBook, DropBox, Flickr, Instagram, etc. Or choose a picture on your computer, take a webcam pic, or one from our public gallery.</p>
          </div>

          </div><!--row fluid!-->
        </div>

        <div id="features1">
        <div class="row-fluid">
          <div class="span4 offset1">
            <h2>Play with Friends</h2>
            <p>Looking to compete against your friends? No problem. Compete in real time with your friends, even on different devices. Be able to see your opponenets progress, and win awesome prizes.</p>
          </div><!--end span10 offset1!-->

          <div class="span4 offset2">
          <img src="./img/gamewithfriends.jpg">
          </div>

          </div><!--row fluid!-->
          </div>

        </div>
   </div><!--end features!-->

    <div class="signup">
      <div class="row-fluid">
        <div class="span10 offset1">
          <h1 class="text-center">Want to play? Let's get started!</h1><br><br>
          <center><a class="awesome-button" href="upload.php">Get Started</a></center>
        </div>
      </div>
    </div>
<?php
   include_once "footer.php";
?>