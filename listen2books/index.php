<?php require_once('header.php');?>  
          <div class="inner cover">
            <h1 style="color:white;" class="cover-heading">Listen to your Books</h1>
            <p class="lead">Generate a Spotify playlist from the books you're reading</p>
            <p class="lead">
              <button href="#responsive" data-toggle="modal" class="demo btn btn-primary btn-lg">Let's Go!</button>
              <div class="text-center">
            </div>
            </p>
          </div>
          
          


     <div id="responsive" class="modal fade" tabindex="-1" data-width="500" style="display: none;">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 style="color:black" class="modal-title">Book Search</h4>
    </div>
    <div class="modal-body">
    <div class="row">
    <div class="col-md-12">
    		<form id="tfnewsearch" method="post" action="search.php">
    <p><input class="form-control" name="term" type="text" placeholder="Book Title"></p>
    <p><input class="form-control" name="term1" type="text" placeholder="Author"></p>
    </div>
    </div>
    </div>
    <div class="modal-footer">
		        	<input type="submit" class="demo btn btn-primary btn-lg" value="search" class="tfbutton">

    </form>
    </div>
    </div>          
<?php require_once('footer.php');?>    