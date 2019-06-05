<?php
	namespace Core;

	/**
	 * Classe parente des entités représentant les tables
	 */
	abstract class Entity
	{
		protected $errors = [],
				  $id;

		public function __construct(array $datas = [])
		{
			if (!empty($datas)) {
				$this->hydrate($datas);
			}
		}

		/**
		 * Vérifie si c'est une nouvelle entité
		 * @return {Boolean}
		 */
		public function isNew()
		{
			return empty($this->id);
		}

		/**
		 * Vérifie si l'entité est valide
		 * @return {Boolean}
		 */
		public function isValid()
		{
			return count($this->errors) == 0;
		}

		/**
		 * Renvoi les erreurs
		 * @return {Array} $errors
		 */
		public function errors()
		{
			return $this->errors;
		}

		/**
		 * Ajoute une erreur dans le tableau d'erreurs
		 * @return {void}
		 */
		public function addError($error)
		{
			$this->errors[] = $error;
		}

		/**
		 * Renvoi l'id de l'entité
		 * @return {*} $id
		 */
		public function id()
		{
			return $this->id;
		}

		/**
		 * Permet d'hydrater les attributs de l'entité
		 * @param {Array} $datas Les données à insérer
		 * @return {void}
		 */
		public function hydrate(array $datas)
		{
			foreach ($datas as $attr => $value) {

				$method = 'set'.ucfirst($attr);

				if (is_callable([$this, $method])) {
					$this->$method($value);
				}
			}
		}
	}