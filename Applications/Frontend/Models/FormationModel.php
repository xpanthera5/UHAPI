<?php
	namespace Applications\Frontend\Models;

	/**
	 * Gère les intéractions de formation avec la db
	 */
	class FormationModel extends \Core\Model
	{
		use \Traits\TraitModel;

		public function __construct()
		{
			parent::__construct();
			$this->setTable('formations');
		}

		/**
		 * Permet de créer une nouvelle formation
		 * @param {Array} $dataFormation Les données de la formation à créer
		 * @param {String} $video Le path de la vidéo
		 */
		public function createFormation($dataFormation, $video = null)
		{
			if ($video) {
				$videoRes = $this->createMedia($video['type'], $video['path']);
			}

			$dataFormation['id_media'] = !empty($videoRes) ? $videoRes->id : null;

			$fields = $values = $params = [];
			foreach ($dataFormation as $key => $value) {
				$fields[] = $key;
				$values[":$key"] = $value;
				$params[] = ':'.$key;
			}

			$sql = 'INSERT INTO formations(' . implode(',', $fields) . ') VALUES('.implode(',', $params).')';
			$req = $this->db->prepare($sql);
			$req->execute($values);

			$formationRes = $this->findById($this->db->lastInsertId(), 'formations');
			$formationRes->video = !empty($videoRes) ? $videoRes->path : null;

			return $formationRes;
		}

		/**
		 * Crée un type de formation (local, online ou par conférence)
		 * @param {Array} $data Les données du type à ajouter
		 * @return {Object} $type Les données enregistreés du type ajouté
		 */
		public function createType($data)
		{
			return $this->add($data, 'types');
		}

		/**
		 * Relie une formation à un type (local, online...)
		 * @param {Array} $data Les données à enregistrer
		 * @return {Object} $typeFormation
		 */
		public function createFormationType($data)
		{
			return $this->add($data, 'formation_type');
		}

		/**
		 * Renvoi une formation par rapport à son identifiant
		 * @param {*} $id L'identifiant en question
		 * @return {Object} $formation La formation trouvée
		 */
		public function getFormationById($id)
		{
			$formation = $this->findOne([
				'cond' => 'id='.$id
			]);
			if ($formation) {
				$mediaFormation = !empty($formation->id_media) ? $this->findById($formation->id_media, 'medias') : null;
				$formation->media = $mediaFormation->path;
			}
			
			return $formation;
		}

		/**
		 * Renvoi le détail d'une formation partant de son identifiant
		 * @param {*} $id L'identifiant en question
		 * @return {Object} $formation La formation trouvée
		 */
		public function getFormationDetail($id)
		{
			$formation = $this->findOne(['cond' => 'id='.$id]);
			if ($formation) {
				$mediaFormation = !empty($formation->id_media) ? $this->findById($formation->id_media, 'medias') : null;
				$formation->media = $mediaFormation->path;
				$formation->type = $this->getFormationType($id);
				$formation->sessions = $this->getSessionsFormation($id);
			}
			
			return $formation;
		}

		/**
		 * Renvoi le type formation par rapport à l'identifiant de la formation
		 * @param {*} $id L'identifiant en question
		 * @return {*} $type_formation
		 */
		public function getFormationType($id)
		{
			$q = $this->db->prepare('SELECT FT.id AS id_form_type,
											FT.id_type, 
											TP.nom AS nom_type,
											TP.description AS desc_type,
											TP.etat AS etat_type,
											TP.created AS created_type
									 FROM types AS TP, formation_type AS FT
									 WHERE FT.id_formation = :id
									   AND TP.id = FT.id_type');
			$q->execute(['id' => $id]);

			return $q->fetchAll(\PDO::FETCH_OBJ);
		}

		public function getFraisFormationBySession($id_formation, $id_session)
		{
			$typesFormation = $this->getFormationType($id_formation);
			$result = [];

			foreach ($typesFormation as $type) {
				$q = $this->db->prepare('SELECT FS.montant,
												FR.type_frais,
												FR.description AS desc_frais
										 FROM frais_session AS FS, frais AS FR
										 WHERE FS.id_session = :session
										 AND   FS.id_form_type = :form_type
										 AND   FS.id_frais = FR.id');
				$q->execute([
					'session' => $id_session,
					'form_type' => $type->id_form_type
				]);
				$fr = $q->fetchAll(\PDO::FETCH_OBJ);

				$type->frais = $fr ? $fr : null;
				$result[] = $type;
			}

			return $result;
		}

		/**
		 * Renvoi les sessions d'une formation partant de son identifiant
		 * @param {*} $id L'identifiant en question
		 * @return {Object} $sessions
		 */
		public function getSessionsFormation($id)
		{
			return $this->find([
				'cond' => 'id_formation='.$id.' AND etat="1"'
			], 'sessios_formations');
		}


		/**
		 * Renvoi les formations par rapport à leurs date de début
		 * @param {Array} $contraintes Contraintes pour trouver
		 * @return {Array} $formations Les formations trouvées
		 */
		public function getFormationsByDateDebut($contraintes = [])
		{
			if ($contraintes) {
				$cond = $contraintes;
			}else{
				$cond = [
					'cond'	=> 'etat="1"',
					'order' => 'date_debut'
				];

				if ($limit) {
					// $cond['limit'] = $limit;
					$cond['limit'] = 5;
				}
			}

			$formations = $this->findAll($cond);

			return $formations;
		}

		/**
		 * Renvoi les formations similaires à une formation quelconque
		 * @param {*} $id_formation L'identifiant de la formation
		 * @param {*} $id_sous_cat L'identifiant de la sous catégorie que fait partir ces autres formations
		 * @return {*} $formations Les formations similaires trouvées
		 */
		public function getFormationsSimilaires($id_formation, $id_sous_cat)
		{
			return $this->find(['cond' => 'id != '.$id_formation.' AND id_sous_cat='.$id_sous_cat]);
		}

		/**
		 * Compte le nombre des formations que contient la database
		 * @return {Int} $count Le nombre de formation trouvé
		 */
		public function countFormations()
		{
			return $this->count();
		}

		public function getFormationsSoon($cond)
		{

			$formationsSoon = $this->getFormationsByDateDebut($cond);
			$result;

			foreach ($formationsSoon as $formation) {
				$formation->sous_cat = $this->getSousCategorie($formation->id_sous_cat)->nom;
				$result[] = $formation;
			}

			return $result;
		}

		/**
		 * Création de type de frais
		 * @param {Array} $data
		 * @return {Object} $frais
		 */
		public function createFrais($data)
		{
			return $this->add($data, 'frais');
		}

		/**
		 * Renvois les autres infos concernant une formation
		 * @param {*} $id_formation
		 * @return {*} $result
		 */
		public function getOtherFormationInfos($id_formation)
		{
			$sql = 'SELECT S.montant, S.date_debut, S.date_fin,
						   S.etat AS etat_session,
						   T.nom AS type, T.description, T.etat AS etat_type,
						   FR.type_frais, FR.description AS desc_type_frais
					FROM formation_type AS F, 
						 sessios_formations AS S, types AS T, frais AS FR
					WHERE S.id_form_type = F.id
					  AND T.id = F.id_type
					  AND FR.id = S.id_frais
					  AND F.id_formation = '.$id_formation;
			$q = $this->db->prepare($sql);
			$q->execute();

			return $q->fetchAll(\PDO::FETCH_OBJ);
		}
	}