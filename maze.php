<?php
  include_once "header.php";
?>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="./js/imaze.js"></script>
  <script type="text/javascript" src="./js/canvas-touch.js"></script>
  <script type="text/javascript" src="./js/URI.js"></script>

  <script type="text/javascript">
  //<!--
  $(document).ready(function() {
    var canvas = document.getElementById('maze');
    var ctx = canvas.getContext("2d");

    var token = window.location.href.match(/token=([^\.#$\[\]]+)/);
    //$('.win-lose').hide();
    if (token) {
        token = token[1];
        loadMaze(token, function (snapshot) {
          if (snapshot.name() == 'data') {
        	  var data = JSON.parse(snapshot.val());
             
            // load image;
            $('#user-image').attr('src', data.url);
            // bitmap (global)
            pixSize = data.scale;
            ibitmap = scale(bitmap(data.maze), pixSize);
            
            // initialize moving      
            initializeCanvas(canvas, ctx, function(score) {
              // end of game animation
              // $('.buttons').html('<p>'+score.me+':'+score.opponent+'</p>').show();
              $('#maze').animate({opacity: 0});
            }, [255,0,0,255], token);
            var $readyBtn = $('<a href="#" class="button awesome-button" style="color: #000; font-size: 200%;">I\'m ready!</a>').click(function() {
              setReady(token, "player2", true);
            });
            $('.buttons').html($readyBtn);
            $readyBtn = null;
          }
          else if (snapshot.name() == 'ready') {
            var ready = snapshot.val();
            if (ready.player2) {
              $('.buttons').html('<span style="margin-top: -50px;" class="awesome-button">Waiting for player 1...<br /><br /><img width="100" height="100" src="img/load.gif"/></span>');
              if (ready.player1) {
                $('.buttons').hide();
                // starting count down shuld goes here
                showMaze(ibitmap, pixSize);
                ibitmap = null;
              }
            }
          }
      	});
    }
    else {
        $('#difficulty-easy').click(function() {
           initializeBoard(20);
        });
        $('#difficulty-medium').click(function() {
           initializeBoard(15);
        });
        $('#difficulty-hard').click(function() {
           initializeBoard(10);
        });
    
        function initializeBoard(iscale) {
          var $singleBtn = $('<div id="single-player" class="awesome-button"><p class="playbutton">Single player</p></div>').click(function() {
            initializeGame(false);
          }); 
          var $multiBtn = $('<div id="competition" class="awesome-button"><p class="playbutton">Competition</p></div>').click(function() {
            initializeGame(true);
          });
          $('.buttons').html('').append($singleBtn).append($multiBtn); 
  
          // meze size
          var w = ($('#user-image').get(0).width-iscale)/(2*iscale);
          var h = ($('#user-image').get(0).height-iscale)/(2*iscale);
          imaze = maze(parseInt(h), parseInt(w)); // easy=(1620x820), med=(1215, 615), hard=(810x410) => (w,h) = (2nd*iscale*2+iscale, 1st*iscale*2+iscale)
          pixSize = iscale;
          ibitmap = scale(bitmap(imaze), iscale);
        }
        
        function initializeGame(multiplayer) {
          if (multiplayer) {
            var token = requestMultiplayerGame($('#user-image').attr('src'), imaze, pixSize, function(snapshot) {
              if (snapshot.name() == 'ready') {  
                var ready = snapshot.val();
                if (ready.player1) {
                  $('.buttons').html('<span style="margin-top: -50px;" class="awesome-button">Waiting for player 2...<br /><br /><img width="100" height="100" src="img/load.gif"/></span>');
                  if (ready.player2) {
                    $('.buttons').hide();
                    // starting count down shuld goes here
                    showMaze(ibitmap, pixSize);
                    ibitmap = null;
                  }
                } 
              }
            });
            imaze = null;
            
            // initialize moving      
            initializeCanvas(canvas, ctx, function(score) {
              // end of game animation
              // $('.buttons').html('<p>'+score.me+':'+score.opponent+'</p>').show();
              $('#maze').animate({opacity: 0});
            }, [0,255,0,255], token);
            $('.buttons').html('<div style="margin-top: -50px;" class="awesome-button" style="display: inline-block;"><label for="url">Send the following URL to your opponent<br></label><input id="url" type="text" value="' + URI(window.location.href).search('token='+token) + '"</span><br><br></div>');
            var $readyBtn = $('<a href="#" class="button" style="color: #000; font-size: 200%;">I\'m ready!</a>').click(function() {
              setReady(token, "player1", true);
            });
            $('.buttons > div').append($readyBtn);
            $readyBtn = null;
          }
          else {
            // initialize moving      
            initializeCanvas(canvas, ctx, function(score) {
              // end of game animation
              // $('.buttons').html('<p>'+score.me+':'+score.opponent+'</p>').show();
              $('#maze').animate({opacity: 0});
              //$('.win-lose').show();
            });
            $('.buttons').hide();
            // show maze immediately
            showMaze(ibitmap, pixSize);
            ibitmap = null;
          }
        }
      }
        
      function showMaze(ibitmap, iscale) {
        var width = ibitmap[0].length;
        var height = ibitmap.length;
        canvas.width = width;
        canvas.height = height;
  
        for (var y=0; y<height; y++) {
          for (var x=0; x<width; x++) {
            if (ibitmap[y][x] != 1) {
              // White
              ctx.fillStyle = "#fff";
              ctx.fillRect( x, y, 1, 1 );
            }
          }        
        }
      
        // Starting point
        ctx.fillStyle = "#0f0";
        ctx.fillRect( 0, iscale, iscale, iscale );
        // destination point
        ctx.fillStyle = "#f00";
        ctx.fillRect( width-iscale, height-(2*iscale), iscale, iscale );
        
        $('#maze').css('display', 'block');
        $('#user-image').css({'position': 'absolute', 'top': 0, 'left': 0});
      }
  });
  //-->
  </script>
<body>
  <div class="menu">
      <div class="row-fluid">
        <div class="span10 offset1">
        <div class="row-fluid">
          <div class="span3">
            <img src="./img/logo.png" width="40%" height="40%">
          </div>
          <div class="span3 offset6">
            
            <p class="text-right"><a href="index.php">HELLO</a> <a href="upload.php"><b>GENERATE</b></a> <a href="about.php">ABOUT</a> </p>
            
          </div>
        </div>
        </div><!--end span10 offset1!-->
      </div><!--end row fluid!-->
      </div><!--end menu!-->

  <div class="title">
    <div class="row-fluid">
      <div class="span10 offset1">
        <h1 class="text-center">Maze</h1>
      </div>
    </div>
  </div>
  <div class="maze">
  <div class="row">
    <div class="maze-wrap">
      <div class="buttons">
        <div id="difficulty-easy" class="awesome-button"><p class="playbutton">Easy</p></div>
        <div id="difficulty-medium" class="awesome-button"><p class="playbutton">Medium</p></div>
        <div id="difficulty-hard" class="awesome-button"><p class="playbutton">Hard</p></div>
      </div>
      <!--<div class="win-lose">
        <div class="awesome-button"><p width="400"><b>Congrats!</b></p></div>
      </div>-->
      <canvas id="maze" width="600" height="400"></canvas>
      <?php if (isset($_GET['mazeImage'])): ?>
        <img id="user-image" src="<?php echo $_GET['mazeImage']; ?>" />
      <?php else: ?>
        <!-- Logo here! -->
        <img id="user-image" src="./img/logo.png" />
      <?php endif; ?>
    </div>
  </div>
  </div>  
<?php
  include_once "footer.php";
?>