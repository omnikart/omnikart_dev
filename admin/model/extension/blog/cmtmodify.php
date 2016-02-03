<?php
class ModelExtensionBlogCmtmodify extends Model {
	public function editComment($comment_id, $data) {
		$this->event->trigger ( 'pre.admin.comment.edit', $data );
		$date = date ( 'Y-m-d H:i:s' );
		$query = $this->db->query ( "UPDATE " . DB_PREFIX . "blog_comment SET comment_author = '" . $this->db->escape ( $data ['comment_author'] ) . "', author_email = '" . $this->db->escape ( $data ['author_email'] ) . "', comment_content = '" . $this->db->escape ( $data ['comment_content'] ) . "', comment_approve = '" . $this->db->escape ( $data ['comment_approve'] ) . "' WHERE comment_ID = '" . ( int ) $comment_id . "' LIMIT 1" );
		
		$this->event->trigger ( 'post.admin.comment.add', $product_id );
		
		return $this->db->countAffected ();
	}
	public function deleteComment($comment_id) {
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_comment WHERE comment_ID = '" . ( int ) $comment_id . "' LIMIT 1" );
		
		return $this->db->countAffected ();
	}
}