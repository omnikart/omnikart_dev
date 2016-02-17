<div class="table-responsive">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<td style="width: 1px;"><input type="checkbox"
					onclick="$('input[name*=\'selected\']').prop('checked',this.checked);" /></td>
				<td>Name</td>
				<td>SEO Keyword
					<button type="button" onclick="generateSEO();"
						class="btn btn-default">Generate</button>
				</td>
				<td>Meta Title
					<button type="button" onclick="generateMetaT();"
						class="btn btn-default">Generate</button>
				</td>
				<td>Meta Keywords
					<button type="button" onclick="generateMetaK();"
						class="btn btn-default">Generate</button>
				</td>
				<td>Meta Description
					<button type="button" onclick="generateMetaD();"
						class="btn btn-default">Generate</button>
				</td>
			</tr>
		</thead>
		<tbody id="products">
			<?php if ($categories) { foreach($categories as $key => $product) { ?>
			<tr id="prod-<?php echo $key; ?>">
				<td class="category_id"><input name="selected[]"
					value="<?php echo $product['category_id']; ?>" type="checkbox"></td>
				<td class="name"><input
					name="products[<?php echo $product['category_id']; ?>][name]"
					value="<?php echo $product['name']; ?>" type="text" /></td>
				<td class="keyword"><input
					name="products[<?php echo $product['category_id']; ?>][keyword]"
					value="<?php echo $product['keyword']; ?>" type="text" /></td>
				<td class="meta_title"><input
					name="products[<?php echo $product['category_id']; ?>][meta_title]"
					value="<?php echo $product['meta_title']; ?>" type="text" /></td>
				<td class="meta_keyword"><input
					name="products[<?php echo $product['category_id']; ?>][meta_keyword]"
					value="<?php echo $product['meta_keyword']; ?>" type="text" /></td>
				<td class="meta_description"><textarea
						name="products[<?php echo $product['category_id']; ?>][meta_description]"
						rows="1"><?php echo $product['meta_description']; ?></textarea></td>

			</tr>
			<?php } } ?>
		</tbody>
	</table>
</div>
<script>
function generateSEO(){
	$('#products > tr').each(function(index){
		var ths = $(this).attr('id');
		var category_id = $('#'+ths+' > .category_id > input[type=\'checkbox\']').val();
		var name = $('#'+ths+' > .name > input[type^=\'text\']').val();
		var keyword = $('#'+ths+' > .keyword > input[type^=\'text\']');
		
		SEO = name+'-'+category_id;
		SEO = SEO.replace(/^\s+|\s+$/g, ''); 			// trim
        SEO = SEO.toLowerCase();						// remove accents, swap, etc
		var from = "àáäâèéëêìíïîòóöôùúüûñcçčlľštžýnrrdçõã·/_,:;";
        var to   = "aaaaeeeeiiiioooouuuuncccllstzynrrdcoa------";
		
		for (var i=0, l=from.length ; i<l ; i++) {
        	SEO = SEO.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        SEO = SEO.replace(/[^a-z0-9( -]/g, '')
        .replace(/\(/g,"-") 	// collapse whitespace and replace by -
        .replace(/\s+/g, '-')	// collapse dashes
        .replace(/-+/g, '-');	// return SEOlink;
        keyword.val(SEO.'.html');
	});
}
</script>