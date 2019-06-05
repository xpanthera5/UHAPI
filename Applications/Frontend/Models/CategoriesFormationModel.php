<?php 
	namespace Applications\Frontend\Models;


	/**
	 * UsersModel
	 */
	class CategoriesFormationModel extends \Core\Model
	{
		use \Traits\TraitModel;

		public function __construct()
		{
			parent::__construct();
			$this->setTable('categories_formations');
		}

		/**
		 * Permet de créer une catégorie
		 * @param {Object} $datas Les données de la catégorie à ajouter
		 */
		public function create($datas, $media)
		{
			$resMedia = $this->createMedia($media['type'], $media['path']);
			$datas['id_media'] = $resMedia ? $resMedia->id : null;
			$resCat = $this->add($datas, 'categories_formations');
			$this->setPrimaryKey('id');
			$dataUpdate = [
				'id' => $resCat->id,
				'id_media' => $resMedia->id
			];

			$this->setTable('categories_formations');
			$this->update($dataUpdate);
			$categorieReturn = $this->findOne(['cond' => 'id='.$resCat->id]);
			$categorieReturn->media = $resMedia->path;

			return $categorieReturn;
		}

		/**
		 * Renvoi une catégorie par rapport à l'identifiant passé en paramètre
		 * @param {*} $id L'identifiant de la catégorie à renvoyer
		 */
		public function getCategorieById($id)
		{
			$cat = $this->findById($id);

			if ($cat) {
				$cat->media = $cat->id_media ? $this->findById($cat->id_media, 'medias')->path : null;
			}

			return $cat;
		}

		/**
		 * Renvoi toutes les catégories
		 * @param {Integer} $limit
		 */
		public function getAllcategories($limit = null)
		{
			$sql = "SELECT C.id AS id_cat, 
						   C.created AS created_cat,
						   C.nom, 
						   C.description, 
						   C.id_media, 
						   C.etat AS etat_cat,
						   M.path AS media
					FROM categories_formations AS C,
						 medias AS M
					WHERE C.id_media = M.id
					  AND C.etat = '1'";

			$sql .= $limit ? ' LIMIT ' . $limit : '';
			
			$req = $this->db->prepare($sql);
			$req->execute();

			return $req->fetchAll(\PDO::FETCH_OBJ);
		}

		/**
		 * Modifie une catégorie dont l'id est passé en paramètre
		 * @param {Array} $cat Contient les données de la catégorie qu'on veut modier
		 * @param {Array} $media Les données du media
		 */
		public function setCategorie($cat, $media)
		{
			$dataMedia = $media ? $this->createMedia($media['type'], $media['path']) : null;
			$categorie = $cat;

			if ($dataMedia) {
				$categorie['id_media'] = $dataMedia->id;
			}

			if (count($categorie) > 1) {
				$this->update($categorie, 'categories_formations');
			}

			return $this->findOne(['cond' => 'id='.$cat['id']], 'categories_formations');
		}

		/**
		 * @param {Array} $sousCat le tableau de la sous catégorie à créer
		 * @param {String} $media Le folder du fichier
		 */
		public function createSousCategorie($sousCat, $media)
		{
			$resSousCat = $this->add($sousCat, 'sous_categories_formation');
			$resMedia = $this->createMedia($media['type'], $media['path']);
			$this->setPrimaryKey('id');
			$dataUpdate = [
				'id' => $resSousCat->id,
				'id_media' => $resMedia->id
			];

			$this->setTable('sous_categories_formation');
			$this->update($dataUpdate);

			return $this->findOne(['cond' => 'id='.$resSousCat->id]);		
		}

		/**
		 * Renvoi toutes les sous catégories existantes
		 * @param {Int} $limit La limite de ces sous catégories
		 * @return {*} $sous_categories
		 */
		public function getAllSousCategories($limit = null)
		{
			$sql = "SELECT S.id AS id_sous_cat, 
						   S.id_cat,
						   S.created AS created_cat,
						   S.nom, 
						   S.description, 
						   S.id_media, 
						   S.etat AS etat_cat,
						   M.path AS media
					FROM sous_categories_formation AS S,
						 medias AS M
					WHERE S.id_media = M.id
					  AND S.etat = '1'";

			$sql .= $limit ? ' LIMIT ' . $limit : '';
			
			$req = $this->db->prepare($sql);
			$req->execute();

			return $req->fetchAll(\PDO::FETCH_OBJ);
		}

		/**
		 * Renvoi toutes les sous catégories d'une catégorie
		 * @param {Int} $limit La limite de ces sous catégories
		 * @return {*} $sous_categories
		 */
		public function getSousCategoriesByCat($id_cat)
		{
			$sql = "SELECT S.id AS id_sous_cat, 
						   S.id_cat,
						   S.created AS created_cat,
						   S.nom, 
						   S.description, 
						   S.id_media, 
						   S.etat AS etat_cat,
						   M.path AS media,
						   C.nom AS categorie
					FROM sous_categories_formation AS S,
						 categories_formations AS C,
						 medias AS M
					WHERE S.id_media = M.id
					  AND S.etat = '1'
					  AND C.id = S.id_cat
					  AND S.id_cat = ".$id_cat;
			
			$req = $this->db->prepare($sql);
			$req->execute();

			$sous_categories = $req->fetchAll(\PDO::FETCH_OBJ);
			$result = [];

			foreach ($sous_categories as $sous) {
				$formations = $this->getFormationsBySousCat($sous->id_sous_cat);
				$sous->slug = parse_slug($sous->categorie.'_'.$sous->id_cat.'/'.$sous->nom.'_'.$sous->id_sous_cat);
				$sous->formations = $formations;
				$result[] = $sous;
			}

			return $result;
		}

		public function getFormationsBySousCat($id_sous_cat)
		{
			$result = [];
			$formations = $this->find([
				'cond' => 'id_sous_cat='.$id_sous_cat
			], 'formations');

			foreach ($formations as $formation) {
				// $otherInfos = $this->getOtherFormationInfos($formation->id);

				// foreach ($otherInfos as $key => $value) {
				// 	$formation->$key = $value;
				// }

				$result[] = $formation;
			}

			return $result;
		}

		/**
		 * Renvoi toutes les données concernant toutes les catégories
		 */
		public function getFull()
		{
			$cats = $this->getAllcategories();
			$sous_cats = $this->getAllSousCategories();
			$formations = $this->find([], 'formations');
			$result = [];

			foreach ($cats as $cat) {
				foreach ($sous_cats as $sous) {
					foreach ($formations as $formation) {
						if ($formation->id_sous_cat == $sous->id_sous_cat) {
							$sous->formations[] = $formation;
						}
					}

					if ($cat->id_cat == $sous->id_cat) {
						$cat->sous_categories[] = $sous;
					}
				}

				$result[] = $cat;
			}


			return $result;
		}
	}