<?php
class ModelModuleBlogComment extends Model {
	public function addComment($post_id, $data, $config) {
		// print_r($data); die();
		$this->event->trigger('pre.review.blog_comment', $data);

		// print_r($date); die();
		$comment_author = (isset($data['name']) ? $this->db->escape($data['name']) : '');
		$autor_email = (isset($data['email']) ? $this->db->escape($data['email']) : '');
		$comment_date = date('Y-m-d H:i:s');
		$comment_author_id = $data['id'];
		$comment_content = $this->db->escape($data['comment']);
		$comment_approve = $config['comment_autoapprove'] == '1' ? 'publish' : 'unpublish';
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "blog_comment(comment_post_ID,comment_author,comment_author_id,author_email,comment_date,comment_content,comment_approve) VALUE('$post_id','$comment_author',$comment_author_id,'$autor_email','$comment_date','$comment_content','$comment_approve')");
		
		$this->db->query("UPDATE " . DB_PREFIX . "blog_post SET comment_count = comment_count+1 WHERE ID = '" . $post_id. "' LIMIT 1");

		$this->event->trigger('post.review.blog_comment', $data);

		return $this->db->getLastId();
	}

	public function increaseComment($post_id) {
		// print_r($post_id); die();
		$query = $this->db->query("UPDATE " . DB_PREFIX . "blog_post SET comment_count = comment_count+1 WHERE ID='$post_id' LIMIT 1");
		return $this->db->countAffected();
	}
}
