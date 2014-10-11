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

    initializeBoard(15);
    
    function initializeBoard(iscale) {
      // meze size
      var w = ($('#user-image').get(0).width-iscale)/(2*iscale);
      var h = ($('#user-image').get(0).height-iscale)/(2*iscale);
      imaze = maze(parseInt(h), parseInt(w)); // easy=(1620x820), med=(1215, 615), hard=(810x410) => (w,h) = (2nd*iscale*2+iscale, 1st*iscale*2+iscale)
      pixSize = iscale;
      ibitmap = scale(bitmap(imaze), iscale);
      
      initializeGame(false);
    }
    
    function initializeGame(multiplayer) {
      if (multiplayer) {
        var token = requestMultiplayerGame($('#user-image').attr('src'), imaze, pixSize, function(snapshot) {
          if (snapshot.name() == 'ready') {  
            var ready = snapshot.val();
            if (ready.player1) {
              $('.buttons').html('<span class="awesome-button">Waiting for player 2...</span>');
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
        $('.buttons').html('<div class="awesome-button" style="display: inline-block;"><label for="url">Send the following URL to your opponent<br></label><input id="url" type="text" value="' + URI(window.location.href).search('token='+token) + '"</span><br><br></div>');
        var $readyBtn = $('<a href="#" class="button" style="color: #000; font-size: 120%;">I\'m ready!</a>').click(function() {
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
        });
        $('.buttons').hide();
        // show maze immediately
        showMaze(ibitmap, pixSize);
        ibitmap = null;
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
<body style="text-align: center;">

    <div class="maze-wrap">
      <div class="buttons">
        <div id="difficulty-easy" class="awesome-button"><p class="playbutton">Easy</p></div>
        <div id="difficulty-medium" class="awesome-button"><p class="playbutton">Medium</p></div>
        <div id="difficulty-hard" class="awesome-button"><p class="playbutton">Hard</p></div>
      </div>
      <canvas id="maze" width="600" height="400"></canvas>
      <?php if (isset($_GET['mazeImage'])): ?>
        <img id="user-image" src="<?php echo $_GET['mazeImage']; ?>" />
      <?php else: ?>
        <!-- Logo here! -->
        <img id="user-image" src="./img/logo.png" />
      <?php endif; ?>
    </div> 
    
</body>