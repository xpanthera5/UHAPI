<?php 
	namespace Applications\Frontend\Controllers;

	/**
	 * UsersController
	 */
	class CategoriesFormationController extends \Core\Controller
	{
		use \Traits\TraitController;

		public function index($req, $res)
		{
			
		}

		/**
		 * Permet de créer une nouvelle catégorie
		 * @param {Request} $req L'objet de requêtes http
		 * @param {Response} $res L'objet contenant toutes les reponses http
		 */
		public function createCategorie($req, $res)
		{
			// debug($req->post());
			if ($req->postNotEmpty(['nom', 'description']) && $req->files('media')) {
				$errors = [];

				if (strlen($req->post('nom')) < 2) {
					$errors[] = 'Le nom de la catégorie minimum 2 caractères';
				}

				if (strlen($req->post('description')) < 10) {
					$errors[] = 'La description de la catégorie minimum 10 caractères';
				}

				if (count($errors) == 0) {
					$resultUpload = self::uploadFile($req->files('media'), 'image', 'img/formations/categories');

					if ($resultUpload['success']) {
						// $_POST = array_merge($_POST, ['media' => 'photo.jpg']);
						$media = [
							'type' => 'image',
							'path' => $resultUpload['data']['folder']
						];
						
						$result = $this->model->create($req->post(), $media);

						if ($result) {
							$this->objetRetour['data'] = $result;
							$this->objetRetour['message'] = "La catégorie a été bien enregistrée";
						}else {
							$this->objetRetour['message'] = "Une erreur est survenue lors de la création de la catégorie";
						}
					}else {
						$this->objetRetour['message'] = $resultUpload['message'];
					}
				}else {
					$this->objetRetour['message'] = implode('<br>', $errors);
				}
			}else {
				$this->objetRetour['message'] = "Veuillez remplir tous les champs";
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Permet de renvoyer une catégorie partant de son id
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getCategorie($req, $res)
		{
			if ($req->getNotEmpty(['id_cat'])) {
				$cat = $this->model->getCategorieById($req->get('id_cat'));

				if ($cat) {
					$this->objetRetour['success'] = true;
					$this->objetRetour['data'] = $cat;
					$this->objetRetour['message'] = "La catégorie a été bien trouvée";
				}else {
					$this->objetRetour['message'] = "Aucune catégorie trouvée";
				}
			}else {
				$this->objetRetour['message'] = "L'id de la catégorie vide";
			}
			
			$res->send($this->objetRetour);
		}

		/**
		 * Permet de renvoyer toutes les catégories (à moins qu'il y ait une limite)
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getAllcategories($req, $res)
		{
			$limit = $req->getNotEmpty(['limit']) ? $req->get('limit') : null;
			$cats = $this->model->getAllcategories($limit);

			if ($cats) {
				$this->objetRetour['success'] = true;
				$this->objetRetour['data'] = $cats;
				$this->objetRetour['message'] = count($cats) . " catégories ont été trouvées";
			}else {
				$this->objetRetour['message'] = "Aucune categorie trouvée";
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Moddifie les données d'une catégorie
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function setCategorie($req, $res)
		{
			if ($req->getNotEmpty(['id_cat'])) {
				$cat = !empty($req->post()) ? $req->post() : null;
				$cat['id'] = $req->get('id_cat');
				$media = $errors = [];

				if ($req->files('media')) {
					$resultUpload = self::uploadFile($req->files('media'), 'image', 'img/formations/categories');

					if ($resultUpload['success']) {
						$media['path'] = $resultUpload['data']['folder'];
						$media['type'] = 'image';
					}else {
						$errors[] = $resultUpload['message'];
					}
				}

				if (count($errors) == 0) {
					$data = $this->model->setCategorie($cat, $media);
					
					$this->objetRetour['success'] = true;
					$this->objetRetour['data'] = $data;
					$this->objetRetour['message'] = "Catégorie bien modifiée";
				}else {
					$this->objetRetour['message'] = implode('<br>', $errors);
				}
			}else {
				$this->objetRetour['message'] = "Veuillez renseigner l'identifiant de la catégotie";
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Permet de créer une nouvelle sous catégorie
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function createSousCategorie($req, $res)
		{
			if ($req->postNotEmpty(['id_cat', 'nom', 'description']) && $req->files('media')) {
				if ($this->model->getCategorieById($req->post('id_cat'))) {
					$errors = [];

					if (strlen($req->post('nom')) < 2) {
						$errors[] = 'Le nom de la catégorie minimum 2 caractères';
					}

					if (strlen($req->post('description')) < 10) {
						$errors[] = 'La description de la catégorie minimum 10 caractères';
					}

					if (count($errors) == 0) {
						$resultUpload = self::uploadFile($req->files('media'), 'image', 'img/formations/souscategories');

						if ($resultUpload['success']) {
							$media = [
								'type' => 'image',
								'path' => $resultUpload['data']['folder']
							];

							$data = $this->model->createSousCategorie($req->post(), $media);
							$this->objetRetour['success'] = true;
							$this->objetRetour['data'] = $data;
							$this->objetRetour['message'] = "Sous catégorie a été bien créée";
						}else {
							$this->objetRetour['message'] = $resultUpload['message'];
						}
						
					}else {
						$this->objetRetour['message'] = implode('<br>', $errors);
					}
					
				}else {
					$this->objetRetour['message'] = "Veuillez spécifier l'id de la catégorie";
				}
			}else {
				$this->objetRetour['message'] = "Veuillez remplir tous les champs";
			}
			
			$res->send($this->objetRetour);
		}

		/**
		 * Permet de renvoyer une sous catégorie partant de son identifiant
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getSousCategorie($req, $res)
		{
			if ($req->getNotEmpty(['id_sous_cat'])) {
				$sousCat = $this->model->getSousCategorie($req->get('id_sous_cat'));

				if ($sousCat) {
					$this->objetRetour['success'] = true;
					$this->objetRetour['message'] = "une sous catégorie a été trouvée";
					$this->objetRetour['data'] = $sousCat;
				}else {
					$this->objetRetour['message'] = "Aucune sous catégorie trouvée";
				}
			}else {
				$this->objetRetour['message'] = "Veuillez passer l'id de la sous catégotie à chercher";
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Renvoi les sous catégories d'une formation partant de son identifiant
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getSousCategoriesByCat($req, $res)
		{
			if ($req->paramsNotEmpty(['id_cat'])) {
				$errors = [];

				if (!is_int((int) $req->params('id_cat'))) {
					$errors[] = 'Catégorie invalide';
				}

				if (count($errors) == 0) {
					$sousCategories = $this->model->getSousCategoriesByCat($req->params('id_cat'));

					if ($sousCategories) {
						$this->objetRetour['success'] = true;
						$this->objetRetour['message'] = count($sousCategories).' sous catégories trouvées';
						$this->objetRetour['data'] = $sousCategories;
					}else {
						$this->objetRetour['message'] = 'Aucune sous catégorie trouvée';
					}
				}else {
					$this->objetRetour['message'] = implode('<br>', $errors);
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez renseigner l\'id_cat';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Renvoi toutes les catégories avec leurs sous catégories et leurs formations
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getFull($req, $res)
		{
			$full = $this->model->getFull();

			if ($full) {
				$this->objetRetour['success'] = true;
				$this->objetRetour['message'] = 'Les données sont bien trouvées';
				$this->objetRetour['data'] = $full;
			}else {
				$this->objetRetour['message'] = 'Aucun résultat trouvé';
			}

			$res->send($this->objetRetour);
		}
	}