<?php
	namespace Core;
	
	/**
	 * @OA\Schema(
	 * 		schema="Entity",
	 * 		description="Représente les entités de la base de donnée",
	 * 		@OA\Property(type="integer", property="id"),
	 * 		@OA\Property(type="string", property="etat"),
	 * 		@OA\Property(type="array", property="errors", @OA\Items()),
	 * 		@OA\Property(type="string", format="date-time", property="created")
	 * )
	 */
	abstract class Entity
	{
		protected $id,
				  $etat,
				  $created,
				  $errors = [];

		public function __construct(array $datas = [])
		{
			if (!empty($datas)) {
				$this->hydrate($datas);
			}
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
		 * Modifie le etat
		 * @param {String} $etat L'état de l'utilisateur à ajouter
		 * @return {void}
		 */
		public function setEtat($etat)
		{
			if (!is_int($etat) || ($etat > 1 && $etat < 0) ) {
				$this->errors[] = 'Etat invalide';
			}else {
				$this->etat = $etat;
			}
		}

		/**
		 * Modifie le created
		 * @param {String} $created Le created de l'utilisateur à ajouter
		 * @return {void}
		 */
		public function setCreated(DateTime $created)
		{
			if (!is_int($created) || ($created > 1 && $created < 0) ) {
				$this->errors[] = 'Date création invalide';
			}else {
				$this->etat = $etat;
			}
		}

		/**
		 * Renvoi l'id de l'entité
		 * @return {*} $id
		 */
		public function id()
		{
			return $this->id;
		}

		public function etat()
		{
			return $this->etat;
		}

		public function created()
		{
			return $this->created;
		}
	}