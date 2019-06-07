<?php
	namespace Applications\Frontend;

	use OpenApi\Annotations AS OA;

	/**
	 * @OA\Info(title="API UHTEC Training", version="0.1")
	 * @OA\Server(
	 * 		url="http://uhapi.com:8080",
	 * 		description="L'API du WEBSITE de l'UHTEC Training"
	 * )
	 */
	class FrontendApplication extends \Core\Application
	{
		
		public function __construct()
		{
			parent::__construct();

			$this->name = 'Frontend';
			$this->getController($this);
			// $this->page->setTemplate('ApplicationsViews/')
		}

	}


 ?>