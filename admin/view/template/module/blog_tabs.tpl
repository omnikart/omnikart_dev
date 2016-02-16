<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-blog_tabs" data-toggle="tooltip"
					title="<?php echo $button_save; ?>" class="btn btn-primary">
					<i class="fa fa-save"></i>
				</button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip"
					title="<?php echo $button_cancel; ?>" class="btn btn-default"><i
					class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
		</div>
	</div>
	<div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
    <?php } ?>
    <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post"
					enctype="multipart/form-data" id="form-blog_tabs"
					class="form-horizontal">

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?php echo $name; ?>"
								placeholder="<?php echo $entry_name; ?>" id="input-name"
								class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-limit_title"><?php echo $entry_word_limit_title; ?></label>
						<div class="col-sm-10">
							<input type="text" name="limit_title"
								value="<?php echo $limit_title; ?>" id="input-limit_title"
								class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-limit_content"><?php echo $entry_word_limit_content; ?></label>
						<div class="col-sm-10">
							<input type="text" name="limit_content"
								value="<?php echo $limit_content; ?>" id="input-limit_content"
								class="form-control" />
						</div>
					</div>

					<ul id="myTab" class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#recent"
							id="recent-tab" role="tab" data-toggle="tab"
							aria-controls="recent" aria-expanded="true">Recent Post</a></li>
						<li role="presentation"><a href="#popular" role="tab"
							id="popular-tab" data-toggle="tab" aria-controls="popular">Popular
								Post</a></li>
					</ul>

					<div id="myTabContent" class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="recent"
							aria-labelledBy="recent-tab">

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-title_recent"><?php echo $entry_title; ?></label>
								<div class="col-sm-10">
									<input type="text" name="title_recent"
										value="<?php echo $title_recent; ?>"
										placeholder="<?php echo $entry_title; ?>"
										id="input-title_recent" class="form-control" />
                  <?php if ($error_title_recent) { ?>
                  <div class="text-danger"><?php echo $error_title_recent; ?></div>
                  <?php } ?>
                </div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"
									for="input-listicon_recent"><?php echo $entry_listicon; ?></label>
								<div class="col-sm-10">
									<input type="text" name="listicon_recent"
										value="<?php echo $listicon_recent; ?>"
										placeholder="<?php echo $entry_listicon; ?>"
										id="input-listicon_recent" class="form-control" />
                  <?php if ($error_listicon_recent) { ?>
                  <div class="text-danger"><?php echo $error_listicon_recent; ?></div>
                  <?php } ?>
                </div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-limit_recent"><?php echo $entry_limit; ?></label>
								<div class="col-sm-10">
									<input type="number" name="limit_recent"
										value="<?php echo $limit_recent; ?>"
										placeholder="<?php echo $entry_limit; ?>"
										id="input-limit_recent" class="form-control" />
                  <?php if ($error_limit_recent) { ?>
                  <div class="text-danger"><?php echo $error_limit_recent; ?></div>
                  <?php } ?>
                </div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"
									for="input-thumbnail_show_recent"><?php echo $entry_thumbnail_show; ?></label>
								<div class="col-sm-10">
									<select name="thumbnail_show_recent"
										id="input-thumbnail_show_recent" class="form-control">
                    <?php if ($thumbnail_show_recent) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"
									for="input-thumbnail_size_recent"><?php echo $entry_thumbnail_size; ?></label>
								<div class="col-sm-10">
									<input type="text" name="thumbnail_size_recent"
										value="<?php echo $thumbnail_size_recent; ?>"
										placeholder="<?php echo $entry_thumbnail_size; ?>"
										id="input-thumbnail_size_recent" class="form-control" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"
									for="input-thumbnail_type_recent"><?php echo $entry_thumbnail_type; ?></label>
								<div class="col-sm-10">
									<select name="thumbnail_type_recent"
										id="input-thumbnail_type_recent" class="form-control">
										<option value="static"
											<?php echo $thumbnail_type_recent == 'static' ? 'selected="selected"' : ''; ?>><?php echo $text_static; ?></option>
										<option value="slide"
											<?php echo $thumbnail_type_recent == 'slide' ? 'selected="selected"' : ''; ?>><?php echo $text_slider; ?></option>
									</select>
								</div>
							</div>

						</div>
						<!-- Recent Tab -->

						<div role="tabpanel" class="tab-pane fade in" id="popular"
							aria-labelledBy="popular-tab">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-title_popular"><?php echo $entry_title; ?></label>
								<div class="col-sm-10">
									<input type="text" name="title_popular"
										value="<?php echo $title_popular; ?>"
										placeholder="<?php echo $entry_title; ?>"
										id="input-title_popular" class="form-control" />
                  <?php if ($error_title_popular) { ?>
                  <div class="text-danger"><?php echo $error_title_popular; ?></div>
                  <?php } ?>
                </div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"
									for="input-listicon_popular"><?php echo $entry_listicon; ?></label>
								<div class="col-sm-10">
									<input type="text" name="listicon_popular"
										value="<?php echo $listicon_popular; ?>"
										placeholder="<?php echo $entry_listicon; ?>"
										id="input-listicon_popular" class="form-control" />
                  <?php if ($error_listicon_popular) { ?>
                  <div class="text-danger"><?php echo $error_listicon_popular; ?></div>
                  <?php } ?>
                </div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-limit_popular"><?php echo $entry_limit; ?></label>
								<div class="col-sm-10">
									<input type="number" name="limit_popular"
										value="<?php echo $limit_popular; ?>"
										placeholder="<?php echo $entry_limit; ?>"
										id="input-limit_popular" class="form-control" />
                  <?php if ($error_limit_popular) { ?>
                  <div class="text-danger"><?php echo $error_limit_popular; ?></div>
                  <?php } ?>
                </div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_thumbnail_show; ?></label>
								<div class="col-sm-10">
									<select name="thumbnail_show_popular"
										id="input-thumbnail_show_popular" class="form-control">
                    <?php if ($thumbnail_show_popular) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"
									for="input-thumbnail_size_popular"><?php echo $entry_thumbnail_size; ?></label>
								<div class="col-sm-10">
									<input type="text" name="thumbnail_size_popular"
										value="<?php echo $thumbnail_size_popular; ?>"
										placeholder="<?php echo $entry_thumbnail_size; ?>"
										id="input-thumbnail_size_popular" class="form-control" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"
									for="input-thumbnail_type_popular"><?php echo $entry_thumbnail_type; ?></label>
								<div class="col-sm-10">
									<select name="thumbnail_type_popular"
										id="input-thumbnail_type_popular" class="form-control">
										<option value="static"
											<?php echo $thumbnail_type_popular == 'static' ? 'selected="selected"' : ''; ?>><?php echo $text_static; ?></option>
										<option value="slide"
											<?php echo $thumbnail_type_popular == 'slide' ? 'selected="selected"' : ''; ?>><?php echo $text_slider; ?></option>
									</select>
								</div>
							</div>

						</div>
						<!-- Popular Tab -->
					</div>
					<!-- Tab Contnt -->
					<hr>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-custom_style"><?php echo $entry_custom_style; ?></label>
						<div class="col-sm-10">
							<textarea name="custom_style" id="input-custom_style" cols="30"
								rows="10" class="form-control">
                <?php echo $custom_style; ?>
              </textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-custom_script"><?php echo $entry_custom_script; ?></label>
						<div class="col-sm-10">
							<textarea name="custom_script" id="input-custom_script" cols="30"
								rows="10" class="form-control"><?php echo $custom_script; ?></textarea>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>