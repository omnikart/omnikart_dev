<div class="row">
<div class="col-lg-12">
	<div id="blog-comment">
		<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title"><span class="<?php echo $titleicon_list; ?>"></span>&nbsp;<?php echo $title_commentlist; ?></h2>
		</div>
		<div class="panel-body">
			<ul id="commentList" class="comment-box">
			<?php if(!empty($comments)): ?>
				<?php 
				$inc = 0;
				foreach ($comments as $comment) : ?>
					<li class="comment">
					<div class="comment-author <?php echo $author_photo_position ? 'comment-author-'.$author_photo_position : null; ?>">
						<?php if($author_photo_display) : ?>
						<div class="author-img">
							<img class="img-rounded" src="<?php echo $comment['author_image']; ?>" onerror="this.style.display='none'" alt="">
						</div>
						<?php endif; ?>
						<span>BY: <?php echo $comment['comment_author']; ?>, </span>
						<span><?php echo $comment['comment_date']; ?></span>
					</div>
					<div class="comment-content <?php echo $author_photo_position ? 'comment-content-'.$author_photo_position : null; ?>">
						<div id="comment<?php echo $inc; ?>"><?php echo html_entity_decode($comment['comment_content']); ?></div>
						<?php
							$html = str_get_html(html_entity_decode($comment['comment_content']));
						?>
					</div>
					<div class="clearfix"></div>
					</li>
				<?php 
				$inc++;
				endforeach; ?>
				<div class="pagination-container">
                    <div id="comment-pagination">
		                <?php echo $pagination; ?><br>
		                <?php echo $results; ?>
		            </div>
		            <div class="clerarfix"></div>
				</div>
			<?php else: ?>
				<div class="alert alert-warning"><?php echo $not_found; ?></div>
			<?php endif; ?>
			</ul>
		</div>
		</div>
		<!-- .panel -->
	</div>
	<!-- #blog-comment -->
</div>
</div>

<?php if($comment_status == 'open') : ?>
	
<div id="commentAlert">
	<?php form_error(); ?>
	<?php form_msg(); ?>
</div>

<div class="row">
<div class="col-lg-12">
	<div id="comment-form" class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title"><span class="<?php echo $titleicon_comment; ?>"></span>&nbsp;<?php echo $title_commentbox; ?></h2>
		</div>
		<div class="panel-body">
		<form id="form" class="form-horizontal">

			<?php if(!getId()) : ?>
			<div class="form-group">
				<label for="name" class="control-label col-sm-12"><?php echo $entry_name; ?></label>
				<div class="col-sm-12">
					<input type="text" name="name" id="name" class="form-control" data-toggle="tooltip" data-placement="top" data-original-title="">
					<?php if (isset($form_error['name'])) { ?>
                      <div class="label label-danger"><?php echo ucfirst($form_error['name']); ?></div>
                    <?php } ?>
				</div>
			</div>

			<div class="form-group">
				<label for="website" class="control-label col-sm-12"><?php echo $entry_email; ?></label>
				<div class="col-sm-12">
					<input type="email" name="email" id="email" placeholder="" class="form-control"  data-toggle="tooltip" data-placement="top" data-original-title="">
					<?php if (isset($form_error['email'])) { ?>
                      <div class="label label-danger"><?php echo ucfirst($form_error['email']); ?></div>
                    <?php } ?>
				</div>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<label for="comment" class="control-label col-sm-12"><?php echo $entry_comment; ?></label>
				<div class="col-sm-12">
					<?php if ($login) { ?>
						<span><a href="<?php echo $login_url; ?>">Login </a>to post comment</span>
					<?php } ?>
					<textarea name="comment" id="comment" rows="10" class="form-control" data-toggle="tooltip" data-placement="top" data-original-title="" <?php echo ($login?'disabled':''); ?> ></textarea>
					<?php if (isset($form_error['comment'])) { ?>
							<div class="label label-danger"><?php echo ucfirst($form_error['comment']); ?></div>
						<?php } ?>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<button type="button" class="btn btn-success btn-lg" id="submit"><?php echo $button_submit; ?></button>&nbsp;&nbsp;
					<button type="reset" class="btn btn-warning btn-lg"><?php echo $button_reset; ?></button>
				</div>
			</div>
		</form>
		</div>
	</div>
	<!-- #comment-form -->
</div>
</div>
<?php endif; ?>

<script type="text/javascript"><!--
	$('#submit').on('click', function() {
	var comment = $('#comment').val();
	$.ajax({
		url: "index.php?route=module/blog_comment/submit&pid=<?php echo $_GET['pid']; ?>",
		type: 'post',
		dataType: 'json',
		data: $('#form').serialize()+'&comment='+comment+'&ajax=1',
		beforeSend: function() {
			$('#submit').button('loading');
		},
		complete: function() {
			$('#submit').button('reset');
		},
		success: function(json) {
			if (json.error) {
					if (json.redirect) {
						var r = confirm("Log in to submit a comment...! Should we redirect you to login page?");
						if (r == true) {
							window.location = json.redirect;
						} 
					}
					if (json.error=='comment') {
						$('#comment').addClass('alert-danger').parent().append('<div class="text-danger">Comment should be more than 25 Characters </div>');
						setTimeout(function(){ $('#comment').removeClass('alert-danger').parent().children('.text-danger').remove(); }, 2000);
					}
			} else {
					$('#comment').val('');
			}
		}
	});
	return false;
});
//--></script>
