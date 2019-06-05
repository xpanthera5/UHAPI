<?php
	namespace Applications\Frontend\Controllers;

	/**
	 * Gère les partenaires
	 */
	class PartenairesController extends \Core\Controller
	{
		use \Traits\TraitController;

		/**
		 * Crée un partenaire
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function create($req, $res)
		{
			if ($req->postNotEmpty(['nom']) && $req->files('logo')) {
				$errors = [];

				if (strlen($req->post('nom')) < 2) {
					$errors[] = 'Le nom minimum 2 caractères';
				}

				if ($req->postNotEmpty(['website'])) {
					if (strlen($req->post('website')) < 5) {
						$errors[] = 'Le website trop court';
					}
				}

				if (count($errors) == 0) {
					$resultUpload = self::uploadFile($req->files('logo'), 'image', 'medias/partenaires');

					if ($resultUpload['success']) {
						$partenaire = $req->post();
						$partenaire['website'] = $req->postNotEmpty(['website']) ? $req->post('website') : null;
						$partenaire['logo'] = $resultUpload['data']['folder'];
						
						$result = $this->model->createPartenaire($partenaire);

						if ($partenaire) {
							$this->objetRetour['data'] = $result;
							$this->objetRetour['message'] = "Le partenaire a été bien enregistré";
						}else {
							$this->objetRetour['message'] = "Une erreur est survenue lors de l'enregistrement du partenaire";
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
		 * Renvoi tous les partenaires
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getAllPartenaires($req, $res)
		{
			$limit = $req->getNotEmpty(['limit']) ? $req->get('limit') : null;
			$partenaires = $this->model->getPartenaires($limit);

			if ($partenaires) {
				$this->objetRetour['success'] = true;
				$this->objetRetour['message'] = count($partenaires) > 1 ? count($partenaires).' partenaires trouvés' : '1 partenaire trouvé';
				$this->objetRetour['data'] = $partenaires;
			}else {
				$this->objetRetour['message'] = 'Aucun partenaire trouvé';
			}

			$res->send($this->objetRetour);
		}
	}