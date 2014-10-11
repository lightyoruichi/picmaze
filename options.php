<?php
  include_once "header.php";
?>
    <body>
    <!-- Load Feather code -->
<script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script>
<script type='text/javascript'>
var featherEditor = new Aviary.Feather({
       apiKey: 'R5ctaJYc7kSNYxrLjHSREg',
       apiVersion: 2,
       tools: 'all',
       appendTo: '',
       onSave: function(imageID, newURL) {
           var img = document.getElementById(imageID);
           img.src = newURL;
       },
       onError: function(errorObj) {
           alert(errorObj.message);
       }
   });
   function launchEditor(id, src) {
       featherEditor.launch({
           image: id,
           url: src
       });
      return false;
   }
</script>
<script type="text/javascript">
function uploadToMaze(){
	window.location.href = "./maze.php?mazeImage="+$("#image").attr('src');
}
</script>
    <div class="menu">
      <div class="row-fluid">
        <div class="span10 offset1">
        <div class="row-fluid">
          <div class="span3">
            <img src="./img/logo.png" width="40%" height="40%">
          </div>
          <div class="span3 offset6">
            <p class="text-right"<a href="index.php"><b>PLAY</b></a> <a href="about.php">ABOUT</a></p>
          </div>
        </div>
        </div><!--end span10 offset1!-->
      </div><!--end row fluid!-->
      </div><!--end menu!-->

      <div class="upload">
        <div class="row-fluid">
          <div class="span12">
            <div class="row-fluid">
              <div class="span10 offset1">
              <center><img type='image' src='http://images.aviary.com/images/edit-photo.png' value='Edit photo' onclick="return launchEditor('image', '<?php echo $_GET['mazeImage'];?>');" style="display:none">
              <img id='image' src='<?php echo $_GET['mazeImage'];?>' style="border-radius:0px;max-height:600px;max-width:400px;"/>
              </center>
               </div>
              </div>
            </div>
          </div>
        </div><!--end row fluid!-->

        <div class="options">
          <div class="row">
            
              <div class="row-fluid">
              <div class="text-center">

                <div class="span4"><button class="awesome-button4" value='Edit photo' onclick="return launchEditor('image', '<?php echo $_GET['mazeImage'];?>');">Edit This Photo</button></div>

                <div class="span4"><div onclick="uploadToMaze()" class="awesome-button" ><p class="playbutton">Play Now!</p></div></div>
                <div class="span4"><a href="/upload.php"><button class="awesome-button4">New Photo</button></a></div>

                

                </div>
              </div>
            
          </div>
      </div>
<?php
   include_once "footer.php";
?>
