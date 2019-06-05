<?php
	namespace Core;

	use OpenApi\Annotations as OA;

	class ClassName extends AnotherClass
	{
		/**
		 * @OA\Get(
		 *		path="/posts",
		 *		@OA\Response(
		 *			response="200",
		 *			description="Nos articles",
		 *			@OA\JsonContent(type="string", description="Titre du premier article")
		 *		)
		 * )
		 */
		public function index()
		{
			# code...
		}
	}