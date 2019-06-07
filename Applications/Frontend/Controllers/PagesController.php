<?php 
	namespace Applications\Frontend\Controllers;

	use OpenApi\Annotations AS OA;

	/**
	 * @OA\Parameter(
	 * 		name="id",
	 * 		description="ID de la source",
	 * 		required=true,
	 * 		@OA\Schema(type="integer")
	 * )
	 * 
	 * @OA\Parameter(
	 * 		name="limit",
	 * 		description="La limite des données à renvoyer",
	 * 		required=false,
	 * 		@OA\Schema(type="integer")
	 * )
	 * 
	 * @OA\Response(
	 * 		response="NotFound",
	 * 		description="La source n'existe",
	 * 		@OA\JsonContent(
	 * 			@OA\Property(property="message", type="string")
	 * 		)
	 * )
	 */
	class PagesController extends \Core\Controller
	{
		public function index($req, $res)
		{
			$res->send(['nom' => 'moliso']);
		}
	}


 ?>