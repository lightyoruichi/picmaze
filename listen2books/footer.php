    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
        <script src="js/bootstrap-modalmanager.js"></script>
    <script src="js/bootstrap-modal.js"></script>
<script type="text/javascript">

  $(function(){

    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = 
      '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">' +
          '<div class="progress-bar" style="width: 100%;"></div>' +
        '</div>' +
      '</div>';

    $.fn.modalmanager.defaults.resize = true;

    $('[data-source]').each(function(){
      var $this = $(this),
        $source = $($this.data('source'));

      var text = [];
      $source.each(function(){
        var $s = $(this);
        if ($s.attr('type') == 'text/javascript'){
          text.push($s.html().replace(/(\n)*/, ''));
        } else {
          text.push($s.clone().wrap('<div>').parent().html());
        }
      });
      
      $this.text(text.join('\n\n').replace(/\t/g, '    '));
    });

    prettyPrint();
  });
</script>
    

<script id="dynamic" type="text/javascript">
$('.dynamic .demo').click(function(){
  var tmpl = [
    // tabindex is required for focus
    '<div class="modal hide fade" tabindex="-1">',
      '<div class="modal-header">',
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>',
        '<h4 class="modal-title">Modal header</h4>', 
      '</div>',
      '<div class="modal-body">',
        '<p>Test</p>',
      '</div>',
      '<div class="modal-footer">',
        '<a href="#" data-dismiss="modal" class="btn btn-default">Close</a>',
        '<a href="#" class="btn btn-primary">Save changes</a>',
      '</div>',
    '</div>'
  ].join('');
  
  $(tmpl).modal();
});
</script>

<script id="ajax" type="text/javascript">

var $modal = $('#ajax-modal');

$('.ajax .demo').on('click', function(){
  // create the backdrop and wait for next modal to be triggered
  $('body').modalmanager('loading');

  setTimeout(function(){
     $modal.load('modal_ajax_test.html', '', function(){
      $modal.modal();
    });
  }, 1000);
});

$modal.on('click', '.update', function(){
  $modal.modal('loading');
  setTimeout(function(){
    $modal
      .modal('loading')
      .find('.modal-body')
        .prepend('<div class="alert alert-info fade in">' +
          'Updated!<button type="button" class="close" data-dismiss="alert">&times;</button>' +
        '</div>');
  }, 1000);
});

</script>
          <div class="mastfoot">
            <div class="inner">
              <p>Built at GoInnovative 2014 Hackathon.</p>
            </div>
          </div>

        </div>

      </div>

    </div>
  </body>
</html>