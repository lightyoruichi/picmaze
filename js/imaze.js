document.write("<script src='https://cdn.firebase.com/v0/firebase.js' type='text/javascript'></script>");
document.write("<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js' type='text/javascript'></script>");

function bitmap(m) {
  var map = [];
  for (var j = 0; j < m.x * 2 + 1; j++) {
    var line = [];
    if (0 == j % 2)
      for (var k = 0; k < m.y * 2 + 1; k++)
        if (0 == k % 2) line[k] = 1;
        else
    if (j > 0 && m.verti[j / 2 - 1][Math.floor(k / 2)]) line[k] = 0;
    else line[k] = 1;
    else
      for (var k = 0; k < m.y * 2 + 1; k++)
        if (0 == k % 2)
          if (k > 0 && m.horiz[(j - 1) / 2][k / 2 - 1]) line[k] = 0;
          else line[k] = 1;
    else line[k] = 0;
    //if (0 == j) line[1] = 0;
    //if (m.x*2-1 == j) line[2*m.y]= 0;
    map.push(line);
  }
  return map;
}

function maze(x, y) {
  var n = x * y - 1;
  if (n < 0) {
    alert("This dimension of maze is impossible");
    return;
  }
  var horiz = [];
  for (var j = 0; j < Math.max(x, y) + 1; j++) horiz[j] = [];
  var verti = [];
  for (var j = 0; j < Math.max(x, y) + 1; j++) verti[j] = [];
  var here = [Math.floor(Math.random() * x), Math.floor(Math.random() * y)];
  var path = [here];
  var unvisited = [];
  for (var j = 0; j < x + 2; j++) {
    unvisited[j] = [];
    for (var k = 0; k < y + 1; k++) unvisited[j].push(j > 0 && j < x + 1 && k > 0 && (j != here[0] + 1 || k != here[1] + 1));
  }
  while (0 < n) {
    var potential = [
      [here[0] + 1, here[1]],
      [here[0], here[1] + 1],
      [here[0] - 1, here[1]],
      [here[0], here[1] - 1]
    ];
    var neighbors = [];
    for (var j = 0; j < 4; j++)
      if (unvisited[potential[j][0] + 1][potential[j][1] + 1]) neighbors.push(potential[j]);
    if (neighbors.length) {
      n = n - 1;
      next = neighbors[Math.floor(Math.random() * neighbors.length)];
      unvisited[next[0] + 1][next[1] + 1] = false;
      if (next[0] == here[0]) horiz[next[0]][(next[1] + here[1] - 1) / 2] = true;
      else verti[(next[0] + here[0] - 1) / 2][next[1]] = true;
      path.push(here = next);
    } else here = path.pop();
  }
  return ({
    x: x,
    y: y,
    horiz: horiz,
    verti: verti
  });
}

function loadMaze(token, loadedFn) {
  var myDataRef = new Firebase('https://z.firebaseio.com/' + token);
  myDataRef.on('child_added', loadedFn);
  myDataRef.on('child_changed', loadedFn);
}

function requestMultiplayerGame(url, maze, scale, readyFn) {
  var n = new Date().getTime();
  var randToken = n + Math.random().toString().substr(2);
  var myDataRef = new Firebase('https://z.firebaseio.com/' + randToken);
  myDataRef.child('data').set(JSON.stringify({
    url: url,
    maze: maze,
    scale: scale
  }));
  myDataRef.on('child_added', readyFn);
  myDataRef.on('child_changed', readyFn);
  myDataRef = new Firebase('https://z.firebaseio.com/' + randToken + '/ready');
  myDataRef.child('player1').set(false);
  myDataRef.child('player2').set(false);
  return randToken;
}

function setReady(token, player, isReady) {
  var myDataRef = new Firebase('https://z.firebaseio.com/' + token + '/ready');
  myDataRef.child(player).set(isReady);
}

function display(m) {
  var text = [];
  for (var j = 0; j < m.x * 2 + 1; j++) {
    var line = [];
    if (0 == j % 2)
      for (var k = 0; k < m.y * 4 + 1; k++)
        if (0 == k % 4) line[k] = '+';
        else
    if (j > 0 && m.verti[j / 2 - 1][Math.floor(k / 4)]) line[k] = ' ';
    else line[k] = '-';
    else
      for (var k = 0; k < m.y * 4 + 1; k++)
        if (0 == k % 4)
          if (k > 0 && m.horiz[(j - 1) / 2][k / 4 - 1]) line[k] = ' ';
          else line[k] = '|';
    else line[k] = ' ';
    if (0 == j) line[1] = line[2] = line[3] = ' ';
    if (m.x * 2 - 1 == j) line[4 * m.y] = ' ';
    text.push(line.join('') + '\r\n');
  }
  return text.join('');
}

function scale(bitmap, scale) {
  var scaled = [];
  if (bitmap.length == 0) return scaled;
  var width = bitmap[0].length;
  var height = bitmap.length;
  for (var y = 0; y < height; y++) {
    var line = [];
    for (var x = 0; x < width; x++) {
      for (var i = 0; i < scale; i++) {
        line[x * scale + i] = bitmap[y][x];
      }
    }
    for (var j = 0; j < scale; j++) {
      scaled.push(line);
    }
  }
  return scaled;
}

function _isColor(pixel, color) {
  return pixel[0] == color[0] && pixel[1] == color[1] && pixel[2] == color[2] && pixel[3] == color[3];
}

function _pointInArray(point, array) {
  for (var i = array.length - 1; i >= 0; i--) {
    if (point[0] == array[i][0] && point[1] == array[i][1]) {
      return i;
    }
  }
  return -1;
}

function canMove(ctx, x, y, scale, myColor) {
  var c = ctx.getImageData(x * scale, y * scale, 1, 1).data;
  var finished = !_isColor(c, myColor) && !_isColor(c, [0, 0, 0, 0]) && !_isColor(c, [255, 255, 255, 255]);
  // can move on white squere
  if (_isColor(c, [255, 255, 255, 255]) || finished) {
    // is neighbour pixel of myColour
    var eq = 0;
    var neighbour, fillAlso;
    // top
    var top = ctx.getImageData(x * scale, (y - 1) * scale, 1, 1).data;
    if (_isColor(top, myColor)) {
      eq++;
      neighbour = [x, y - 1];
    }
    // right
    var right = ctx.getImageData((x + 1) * scale, y * scale, 1, 1).data;
    if (_isColor(right, myColor)) {
      eq++;
      neighbour = [x + 1, y];
    }
    // bottom
    var bottom = ctx.getImageData(x * scale, (y + 1) * scale, 1, 1).data;
    if (_isColor(bottom, myColor)) {
      eq++;
      neighbour = [x, y + 1];
    }
    // left
    var left = ctx.getImageData((x - 1) * scale, y * scale, 1, 1).data;
    if (_isColor(left, myColor)) {
      eq++;
      neighbour = [x - 1, y];
    }
    // top-right
    c = ctx.getImageData((x + 1) * scale, (y - 1) * scale, 1, 1).data;
    if (_isColor(c, myColor)) {
      eq++;
      if (_isColor(top, [255, 255, 255, 255])) fillAlso = [x, y - 1];
      else fillAlso = [x + 1, y];
    }
    // bottom-right
    c = ctx.getImageData((x + 1) * scale, (y + 1) * scale, 1, 1).data;
    if (_isColor(c, myColor)) {
      eq++;
      if (_isColor(bottom, [255, 255, 255, 255])) fillAlso = [x, y + 1];
      else fillAlso = [x + 1, y];
    }
    // bottom-left
    c = ctx.getImageData((x - 1) * scale, (y + 1) * scale, 1, 1).data;
    if (_isColor(c, myColor)) {
      eq++;
      if (_isColor(bottom, [255, 255, 255, 255])) fillAlso = [x, y + 1];
      else fillAlso = [x - 1, y];
    }
    // top-left
    c = ctx.getImageData((x - 1) * scale, (y - 1) * scale, 1, 1).data;
    if (_isColor(c, myColor)) {
      eq++;
      if (_isColor(top, [255, 255, 255, 255])) fillAlso = [x, y - 1];
      else fillAlso = [x - 1, y];
    }
    // clear fillAlso if neighbour was found
    if (neighbour && fillAlso) {
      fillAlso = null;
    }
    return {
      count: eq,
      closest: neighbour,
      fillAlso: fillAlso,
      finished: finished
    };
  }
  return {
    count: 0,
    closest: null,
    fillAlso: null,
    finished: false
  };
}

function pixelsToRemove(ctx, fromPixel, toPixel, scale) {
  var removePixels = [];
  if (fromPixel == null || toPixel == null) {
    return removePixels;
  }
  while (fromPixel[0] != toPixel[0] || fromPixel[1] != toPixel[1]) {
    var x = fromPixel[0],
      y = fromPixel[1];
    var t = ctx.getImageData(toPixel[0] * scale, toPixel[1] * scale, 1, 1).data;
    removePixels.push(fromPixel.slice()); // store the copy of array
    // top
    c = ctx.getImageData(x * scale, (y - 1) * scale, 1, 1).data;
    if (_isColor(c, t) && _pointInArray([x, y - 1], removePixels) < 0) {
      fromPixel[0] = x;
      fromPixel[1] = y - 1;
      continue;
    }
    // right
    c = ctx.getImageData((x + 1) * scale, y * scale, 1, 1).data;
    if (_isColor(c, t) && _pointInArray([x + 1, y], removePixels) < 0) {
      fromPixel[0] = x + 1;
      fromPixel[1] = y;
      continue;
    }
    // bottom
    c = ctx.getImageData(x * scale, (y + 1) * scale, 1, 1).data;
    if (_isColor(c, t) && _pointInArray([x, y + 1], removePixels) < 0) {
      fromPixel[0] = x;
      fromPixel[1] = y + 1;
      continue;
    }
    // left
    c = ctx.getImageData((x - 1) * scale, y * scale, 1, 1).data;
    if (_isColor(c, t) && _pointInArray([x - 1, y], removePixels) < 0) {
      fromPixel[0] = x - 1;
      fromPixel[1] = y;
      continue;
    }
  }
  return removePixels;
}