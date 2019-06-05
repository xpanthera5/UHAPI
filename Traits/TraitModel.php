<?php
	namespace Traits;

	/**
	 * Contient des méthodes du projet exclu du Model principal
	 */
	trait TraitModel
	{
		/**
		 * Permet de créer un enregistrement de media
		 * @param {String} $type Le type du fichier
		 * @param {String} $path Le path du fichier
		 * @return {Media} $media renvoi les données du média qu'on vient d'ajouter
		 */
		public function createMedia($type, $path)
		{
			return $this->add([
				'type' => $type,
				'path' => $path
			], 'medias');
		}

		
		/**
		 * Permet de rechercher une sous catégorie partant de son identifiant
		 * @param {*} $id_sous_cat L'identifiant de la sous catégorie à rechercher
		 * @return {Object} $sousCat L'objet contenant la sous catégorie trouvée
		 */
		public function getSousCategorie($id_sous_cat)
		{
			$sousCat = $this->findById($id_sous_cat, 'sous_categories_formation');

			if ($sousCat) {
				$sousCat->media = $sousCat->id_media ? $this->findById($sousCat->id_media, 'medias')->path : null;
			}

			return $sousCat;
		}

		/**
		 * vérifie si la valeur d'un champ est déjà utilisé
		 * @param {String} $table
		 * @param {String} $field
		 * @param {String} $value
		 */
		public function isAlreadyUsed($field, $value, $table)
		{
			$q = $this->db->prepare('SELECT '.$field.' FROM '.$table.' WHERE '.$field.' = :value');
			$q->execute(['value' => $value]);

			return (bool) $q->fetchAll(\PDO::FETCH_OBJ);
		}
	}