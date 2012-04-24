<?php

header('Content-Type: application/json');

require_once('./settings.php');
require_once('./lib/AmazonECS.class.php');



if (isset($_POST['search_term']))
{
	$search_term = $_POST['search_term'];

	/**
	 * From the PHP Soap library for Amazon Product Advertising 
	 * http://aws.amazon.com/code/Product-Advertising-API/4373
	 * For a running Search Demo see: http://amazonecs.pixel-web.org
	 */
	try
	{
		// get a new object with your API Key and secret key. Lang is optional.
		// if you leave lang blank it will be US.
		$amazonEcs = new AmazonECS(AWS_API_KEY, AWS_API_SECRET_KEY, 'com', AWS_ASSOCIATE_TAG);

		// for the new version of the wsdl its required to provide a associate Tag
		// @see https://affiliate-program.amazon.com/gp/advertising/api/detail/api-changes.html?ie=UTF8&pf_rd_t=501&ref_=amb_link_83957571_2&pf_rd_m=ATVPDKIKX0DER&pf_rd_p=&pf_rd_s=assoc-center-1&pf_rd_r=&pf_rd_i=assoc-api-detail-2-v2
		// you can set it with the setter function or as the fourth paramameter of ther constructor above
		$amazonEcs->associateTag(AWS_ASSOCIATE_TAG);

		//$response = $amazonEcs->country('com')->search('MySql');
		$response = $amazonEcs->category('All')->responseGroup('Small,Images')->search($search_term);
		
		
		echo json_encode($response);
	}
	catch(Exception $e)
	{
	  echo $e->getMessage();
	}
}

else {
	header('HTTP/1.1 403 Forbidden');
}