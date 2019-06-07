<?php
	namespace Applications\Frontend\Controllers;

	use OpenApi\Annotations AS OA;

	/**
	 * Controller gérant les formations
	 */
	class FormationController extends \Core\Controller
	{
		use \Traits\TraitController;

		/**
		 * Permet créer une formation
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function createFormation($req, $res)
		{
			if ($req->postNotEmpty(['id_sous_cat', 'titre', 'description', 'objectif', 'lieu']) && $req->files('poster')) {
				$errors = [];

				if (strlen($req->post('titre')) < 2) {
					$errors[] = 'Le nom de la formation minimum 2 caractères';
				}

				if (strlen($req->post('description')) < 10) {
					$errors[] = 'La description de la formation minimum 10 caractères';
				}

				if ($req->files('media')) {
					$resultUploadVideo = self::uploadFile($req->files('media'), 'video', 'medias/videos/formations/formation');
					if ($resultUploadVideo['success']) {
						$video = [];
						$video['type'] = 'video';
						$video['path'] = $resultUploadVideo['data']['folder'];
					}else {
						$errors[] = $resultUploadVideo['message'];
					}
					
				}

				if (strlen($req->post('objectif')) < 15) {
					$errors[] = "Le texte de l'objectif est très court, minimum 15 caractères";
				}

				if (count($errors) == 0) {
					$resultUploadPoster = self::uploadFile($req->files('poster'), 'image', 'medias/images/formations/formation');

					if ($resultUploadPoster['success']) {
						
						$dataVideo = !empty($video) ? $video : null;
						$dataFormation = $req->post();
						$dataFormation['poster'] = $resultUploadPoster['data']['folder'];

						$result = $this->model->createFormation($dataFormation, $dataVideo);
						
						$this->objetRetour['success'] = true;
						$this->objetRetour['data'] = $result;
						$this->objetRetour['message'] = "Formation ajoutée";
					}
				}else {
					$this->objetRetour['message'] = implode('<br>', $errors);
				}
				
			}else {
				$this->objetRetour['message'] = 'Veuillez remplir les champs requis';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Relie une formation à un type (local, online...)
		 * @param {Request} $res
		 * @param {Response} $res
		 */
		public function createFormationType($req, $res)
		{
			if ($req->bodyNotEmpty(['id_formation', 'id_type'])) {
				$errors = [];

				if (!is_int((int) $req->body('id_formation'))) {
					$errors[] = 'Formation invalide';
				}

				if (!is_int((int) $req->body('id_type'))) {
					$errors[] = 'Type de formation invalide';
				}

				if (count($errors) == 0) {
					$type = $this->model->createFormationType([
						'id_formation' => $req->body('id_formation'),
						'id_type' => $req->body('id_type')
					]);

					if ($type) {
						$this->objetRetour['success'] = true;
						$this->objetRetour['message'] = 'La formation a été liée à un type';
						$this->objetRetour['data'] = $type;
					}else {
						$this->objetRetour['message'] = 'Une erreur est survenue lors de l\'ajout de formation type';
					}
				}else {
					$this->objetRetour['message'] = implode('<br>', $errors);
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez remplir les champs requis';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Crée un type de formation (local, online ou par conférence)
		 * @param {Request} $req
		 * @param {Respsonse} $res
		 */
		public function createType($req, $res)
		{
			if ($req->bodyNotEmpty(['nom', 'description'])) {
				$errors = [];

				if (strlen($req->body('nom')) < 2) {
					$errors[] = 'Le nom du type minimum 2 caractères';
				}

				if (strlen($req->body('description')) < 10) {
					$errors[] = 'La description du type minimum 10 caractères';
				}

				if (count($errors) == 0) {
					$typeFormation = $this->model->createType([
						'nom' => $req->body('nom'),
						'description' => $req->body('description')
					]);

					if ($typeFormation) {
						$this->objetRetour['success'] = true;
						$this->objetRetour['message'] = 'Type de formation créée avec succès';
						$this->objetRetour['data'] = $typeFormation;
					}else {
						$this->objetRetour['message'] = 'Une erreur est survenue lors de la création de type de catégorie';
					}
				}else {
					$this->objetRetour['message'] = implode('<br>', $errors);
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez remplir les champs requis';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * @OA\Get(
		 * 		path="/formation/getFormation/{id_formation}",
		 * 		@OA\Parameter(
		 * 			name="id_formation",
		 * 			in="path",
		 * 			description="L'identifiant de la formation",
		 * 			required=true,
		 * 			@OA\Schema(type="integer")
		 * 		),
		 * 		@OA\Response(
		 * 			response="200",
		 * 			description="Renvoi les informations d'une formation",
		 * 			@OA\JsonContent()
		 * 		)
		 * )
		 */
		public function getFormation($req, $res)
		{
			if ($req->getNotEmpty(['id_formation'])) {
				$id = (int) $req->get('id_formation');

				if (is_int($id)) {
					$formation = $this->model->getFormationById($id);

					if ($formation) {
						$this->objetRetour['success'] = true;
						$this->objetRetour['message'] = "Une formation a été trouvée";
						$this->objetRetour['data'] = $formation;
					}else {
						$this->objetRetour['message'] = "Aucune formation trouvée";
					}
				}else {
					$this->objetRetour['message'] = "Id de la formation invalide";
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez renseigner l\'id de la formation';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Permet de renvoyer une formation
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getFraisFormation($req, $res)
		{
			if ($req->getNotEmpty(['id_session', 'id_formation'])) {
				$id_formation = (int) $req->get('id_formation');
				$id_session = (int) $req->get('id_session');

				if (is_int($id_formation) && is_int($id_session)) {
					$formation = $this->model->getFraisFormationBySession($id_formation, $id_session);

					if ($formation) {
						$this->objetRetour['success'] = true;
						$this->objetRetour['message'] = "Une formation a été trouvée";
						$this->objetRetour['data'] = $formation;
					}else {
						$this->objetRetour['message'] = "Aucune formation trouvée";
					}
				}else {
					$this->objetRetour['message'] = "id_formation ou id_session invalide";
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez renseigner l\'id de la formation';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Permet de renvoyer une formation
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getFormationDetail($req, $res)
		{
			if ($req->getNotEmpty(['id_formation'])) {
				$id = (int) $req->get('id_formation');

				if (is_int($id)) {
					$formation = $this->model->getFormationDetail($id);

					if ($formation) {
						$this->objetRetour['success'] = true;
						$this->objetRetour['message'] = "Une formation a été trouvée";
						$this->objetRetour['data'] = $formation;
					}else {
						$this->objetRetour['message'] = "Aucune formation trouvée";
					}
				}else {
					$this->objetRetour['message'] = "Id de la formation invalide";
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez renseigner l\'id de la formation';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Renvoi les sessions d'une formation
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getSessionsFormation($req, $res)
		{
			if ($req->getNotEmpty(['id_formation'])) {
				$id = (int) $req->get('id_formation');

				if (is_int($id)) {
					$formation = $this->model->getSessionsFormation($id);

					if ($formation) {
						$this->objetRetour['success'] = true;
						$this->objetRetour['message'] = "Une session a été trouvée";
						$this->objetRetour['data'] = $formation;
					}else {
						$this->objetRetour['message'] = "Aucune session trouvée";
					}
				}else {
					$this->objetRetour['message'] = "Id de la formation invalide";
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez renseigner l\'id de la formation';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Renvoi les formations bientot
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getFormationsSoon($req, $res)
		{
			$limit = $req->getNotEmpty(['limit']) ? $req->get('limit') : null;

			if ($limit) {
				$cond['limit'] = $limit;
			}

			$formationsSoon = $this->model->getFormationsSoon($cond);

			if ($formationsSoon) {
				$this->objetRetour['success'] = true;
				$this->objetRetour['data'] = $formationsSoon;
				$this->objetRetour['message'] = count($formationsSoon) > 1 ? count($formationsSoon).' formations trouvées' : 'Une formation trouvée' ;
			}else {
				$this->objetRetour['message'] = 'Aucune formation trouvée';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Renvoi les formations avenir
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getFormationsFeature($req, $res)
		{
			$limit = $req->getNotEmpty(['limit']) ? $req->get('limit') : null;
			$cond = ['order' => 'date_debut DESC'];

			if ($limit) {
				$cond['limit'] = $limit;
			}

			$formationsFeature = $this->model->getFormationsByDateDebut($cond);

			if ($formationsFeature) {
				$this->objetRetour['success'] = true;
				$this->objetRetour['message'] = count($formationsFeature).' formations trouvées';
				$this->objetRetour['data'] = $formationsFeature;
			}else {
				$this->objetRetour['message'] = 'Aucune formation future trouvée';
			}	

			$res->send($this->objetRetour);
		}

		/**
		 * Renvoi le nombre de formations se trouvant dans la base
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getCountFormations($req, $res)
		{
			$count = $this->model->countFormations();

			if ($count) {
				$this->objetRetour['success'] = true;
				$this->objetRetour['message'] = 'Nous avons trouvé '.$count.' formations';
				$this->objetRetour['data'] = ['nombre_formation' => $count];
			}else {
				$this->objetRetour['message'] = 'Aucune formation dans la base';
				$this->objetRetour['data'] = ['nombre_formation' => 0];
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Renvoi les formations similaires d'une formation
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getFormationsSimilaires($req, $res)
		{
			if ($req->paramsNotEmpty(['id_sous_cat', 'id_formation'])) {
				
				$similaires = $this->model->getFormationsSimilaires($req->params('id_formation'), $req->params('id_sous_cat'));

				if ($similaires) {
					$this->objetRetour['success'] = true;
					$this->objetRetour['message'] = count($similaires).' formations similaires trouvées';
					$this->objetRetour['data'] = $similaires;
				}else {
					$this->objetRetour['message'] = 'Aucune formation similaire';
				}

			}else {
				$this->objetRetour['message'] = 'Veuillez renseigner les données requises';
			}
			
			$res->send($this->objetRetour);
		}

		/**
		 * Création de type de frais
		 * @param {Request} $req
		 * @param {Repsonse} $res
		 */
		public function createFrais($req, $res)
		{
			if ($req->bodyNotEmpty(['type_frais', 'description'])) {
				$errors = [];

				if (strlen($req->body('type_frais')) < 3) {
					$errors[] = 'Type de frais minimum 3 caractères';
				}

				if (strlen($req->post('description')) < 10) {
					$errors[] = 'La description minimum 10 caractères';
				}

				if (count($errors) == 0) {
					$frais = $this->model->createFrais([
						'type_frais' => $req->body('type_frais'),
						'description' => $req->body('description')
					]);

					if ($frais) {
						$this->objetRetour['success'] = true;
						$this->objetRetour['message'] = 'Type frais créé avec succès';
						$this->objetRetour['data'] = $frais;
					}else {
						$this->objetRetour['message'] = 'Une erreur est survenue lors de la création du type de frais';
					}
				}else {
					$this->objetRetour['message'] = implode('<br>', $errors);
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez remplir les champs requis';
			}

			$res->send($this->objetRetour);
		}

		public function test($req, $res)
		{
			// $this->model->desactiveFormationExpire();
		}
	}