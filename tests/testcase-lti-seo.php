<?php namespace Lti\Seo\Test;

use Lti\Seo\LTI_SEO;
use Lti\Seo\Plugin\Postbox_Values;

class LTI_SEO_UnitTestCase extends \WP_UnitTestCase {

	/**
	 * @var LTI_SEO
	 */
	protected $instance;
	/**
	 * @var \Lti\Seo\Admin
	 */
	protected $admin;
	/**
	 * @var \Lti\Seo\Frontend
	 */
	protected $frontend;

	protected $output;

	public function setUp() {
		parent::setUp();
		$this->instance = LTI_SEO::get_instance();
	}

	public function hasContent($content,$query){
		$s = new \DOMDocument();
		@$s->loadHTML($content);
		$xpath = new \DOMXPath($s);
		$tags = $xpath->query($query);

		if($tags->length>0){
			return true;
		}
		return false;
	}

	public function go_to( $url ) {
		$_GET = $_POST = array();
		foreach (array('query_string', 'id', 'postdata', 'authordata', 'day', 'currentmonth', 'page', 'pages', 'multipage', 'more', 'numpages', 'pagenow') as $v) {
			if ( isset( $GLOBALS[$v] ) ) unset( $GLOBALS[$v] );
		}
		$parts = parse_url($url);
		if (isset($parts['scheme'])) {
			$req = isset( $parts['path'] ) ? $parts['path'] : '';
			if (isset($parts['query'])) {
				$req .= '?' . $parts['query'];
				parse_str($parts['query'], $_GET);
			}
		} else {
			$req = $url;
		}
		if ( ! isset( $parts['query'] ) ) {
			$parts['query'] = '';
		}

		$_SERVER['REQUEST_URI'] = $req;
		unset($_SERVER['PATH_INFO']);

		$this->flush_cache();
		unset($GLOBALS['wp_query'], $GLOBALS['wp_the_query']);
		$GLOBALS['wp_the_query'] = new \WP_Query();
		$GLOBALS['wp_query'] = $GLOBALS['wp_the_query'];
		$GLOBALS['wp'] = new \WP();
		_cleanup_query_vars();

		$GLOBALS['wp']->main($parts['query']);
	}

	public function add_featured_image( $post_id ) {

		$img = "post-1_social.jpg";
		$basename       = basename( $img );
		$wp_upload_dir     = wp_upload_dir();
		$source   = dirname( __FILE__ ) . '/images/'.$img;
		$featured = $wp_upload_dir['path'].'/'.$img;
		copy( $source, $featured );

		$file = array(
			'name'     => $basename,
			'tmp_name' => $featured,
		);
		$attach_id  = media_handle_sideload( $file, $post_id );

		return $attach_id;
	}

	public function set_post_box_values($postID, $values){
		update_post_meta( $postID, 'lti_seo', new Postbox_Values( (object) $values ) );
	}

}

