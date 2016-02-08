<?php echo $header; ?><div id="columns">
	<div class="container">
		<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
		<div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php echo $description; ?><?php echo $content_bottom; ?>
      <br />

				<script type="text/javascript" id="rbox-loader-script">
		if(!window._rbox){
		_rbox = { host_protocol:document.location.protocol, ready:function(cb){this.onready=cb;} };
		(function(d, e) {
		    var s, t, i, src=['/static/client-src-served/widget/39457/rbox_api.js', '/static/client-src-served/widget/39457/rbox_impl.js'];
		    t = d.getElementsByTagName(e); t=t[t.length - 1];
		    for(i=0; i<src.length; i++) {
		        s = d.createElement(e); s.src = _rbox.host_protocol + '//w.recruiterbox.com' + eval("src" + String.fromCharCode(91) + String(i) + String.fromCharCode(93));
		        t.parentNode.insertBefore(s, t.nextSibling);
		    }})(document, 'script');
		}
	</script>

				<div class="buttons">
					<div class="pull-right">
						<a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
					</div>
				</div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
	</div>
</div><?php echo $footer; ?>