//Set up some globals
var lastPoint = null, lastPlaced = null, mouseDown = 0;
  
var HexConverter = {
	hexDigits : '0123456789ABCDEF',

	dec2hex : function( dec )
	{ 
		return( this.hexDigits[ dec >> 4 ] + this.hexDigits[ dec & 15 ] ); 
	},

	hex2dec : function( hex )
	{ 
		return( parseInt( hex, 16 ) ) 
	}
}

function initializeCanvas(canvas, ctx, endOfGameCallback, currentColor, firebaseToken) {
  var pixelDataRef;

  if (!currentColor) {
    currentColor = [0,255,0,255];
  }
  
  if (firebaseToken) {
    //Create a reference to the pixel data for our drawing.
    pixelDataRef = new Firebase('https://z.firebaseio.com/'+firebaseToken+'/path');
  }
  else {
    pixelDataRef = null;
  }
  
  var addPixel = function(pos, color) {
    if (jQuery.isArray(color)) {
        color = HexConverter.dec2hex(color[0]) + HexConverter.dec2hex(color[1]) + HexConverter.dec2hex(color[2]);
    }
    if (pixelDataRef != null) {
      pixelDataRef.child(pos).set(color);
    }
    else {      
      drawPixel({name: function() {return pos}, val: function() {return color} });
    }
  };
  
  var removePixel = function(pos) {
    if (pixelDataRef != null) {
      pixelDataRef.child(pos).set(null);
    }
    else {
      clearPixel({name: function() {return pos}});
    }
  }
  
  //Keep track of if the mouse is up or down
  var $canvas = $(canvas);
  $canvas.bind('mousedown touchstart', function () {mouseDown = 1;});
  $canvas.bind('mouseout mouseup mouseleave touchend touchcancel', function () {
    mouseDown = 0, lastPoint = null;
  });
  
  //Draw a line from the mouse's last position to its current position
  var drawLineOnMouseMove = function(e) {
    if (!mouseDown) return;
  
    // Bresenham's line algorithm. We use this to ensure smooth lines are drawn
    var offset = $canvas.offset();
    var x1 = Math.floor((e.pageX - offset.left) / pixSize),
      y1 = Math.floor((e.pageY - offset.top) / pixSize);
    
    var neighbours = canMove(ctx, x1, y1, pixSize, currentColor);
    if (neighbours.count > 0) {
        // check the finish
        if (neighbours.finished) {
          endOfGameCallback({me:10, opponent:5});
          return;
        }
    
        // if not finish fill current screen
        addPixel(x1 + ":" + y1, currentColor);
        // fill out all necessary images
        if (neighbours.fillAlso) {
          addPixel(neighbours.fillAlso[0] + ":" + neighbours.fillAlso[1], currentColor);
        }
        
        if (neighbours.count > 1) {
          // path was corrected
          removePixels = pixelsToRemove(ctx, lastPlaced, neighbours.closest, pixSize);
          for (var i=0; i<removePixels.length; i++) {
              //pixelDataRef.child(x0 + ":" + y0).set(null);
              removePixel(removePixels[i][0] + ":" + removePixels[i][1]);                
          }
        }
        lastPlaced = [x1, y1];
    }
    
    lastPoint = [x1, y1];
  }
  $canvas.bind('mousemove touchmove mousedown', drawLineOnMouseMove);
  
  // Add callbacks that are fired any time the pixel data changes and adjusts the canvas appropriately.
  // Note that child_added events will be fired for initial pixel data as well.
  var drawPixel = function(snapshot) {
    var coords = snapshot.name().split(":");
    ctx.fillStyle = "#" + snapshot.val();
    ctx.fillRect((parseInt(coords[0]) * pixSize), (parseInt(coords[1]) * pixSize), pixSize, pixSize);
  }
  var clearPixel = function(snapshot) {
    var coords = snapshot.name().split(":");
    ctx.fillStyle = "#fff";
    ctx.fillRect(parseInt(coords[0]) * pixSize, parseInt(coords[1]) * pixSize, pixSize, pixSize);
  }
  
  if (pixelDataRef != null) {
    pixelDataRef.on('child_added', drawPixel);
    pixelDataRef.on('child_changed', drawPixel);
    pixelDataRef.on('child_removed', clearPixel);
  }
}