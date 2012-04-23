<?php
/**
 * User
 * Contains related fields and body text(s).
 *
 * @version 	1.0
 * @author 		Keegan Berry
 * @since		03/03/12
 */

class User
{

	/**
	 * public email fields
	 */
	public $twitter_id;
	public $nicename;
	public $lastlogin;

	/**
	 * Creates the instance of the email, you can optionally pass in the field values
	 *
	 * @param string twitter_id
	 * @param int nicename	 
	 * @param int lastlogin 
	 *
	 */
	function __construct($twitter_id = null, $nicename = null, $lastlogin = null)
	{	
		$this->twitter_id = $twitter_id;
		$this->nicename = $nicename;		
		$this->lastlogin = $lastlogin;
	}
	
	/**
	 * Getter function to get the array of body text(s)
	 *
 	 * @return array array of body text(s)
	 */
	public function getBodies() {
		return $this->bodies;
	}
}

?>