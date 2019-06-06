<?php
	namespace Applications\Frontend\Models\Entities;

	use \Core\Entity;

	/**
	 * Classe représentant l'entité users
	 */
	class UsersEntity extends Entity
	{
		protected $nom,
				  $etat,
				  $email,
				  $prenom,
				  $postnom,
				  $created,
				  $password,
				  $telephone;

		/**
		 * Modifie le nom
		 * @param {String} $nom Le nom de l'utilisateur à ajouter
		 * @return {void}
		 */
		public function setNom($nom)
		{
			if (!is_string($nom) || empty($nom)) {
				$this->errors[] = 'Nom invalide';
			}

			if (strlen($nom) < 3) {
				$this->errors[] = 'Le nom minimum 3 caractères';
			}

			$this->nom = $nom;
		}

		/**
		 * Modifie le prenom
		 * @param {String} $prenom Le prenom de l'utilisateur à ajouter
		 * @return {void}
		 */
		public function setPrenom($prenom)
		{
			if (!is_string($prenom) || empty($prenom)) {
				$this->errors[] = 'Prenom invalide';
			}

			if (strlen($prenom) < 3) {
				$this->errors[] = 'Le prenom minimum 3 caractères';
			}

			$this->prenom = $prenom;
		}

		/**
		 * Modifie le postnom
		 * @param {String} $postnom Le postnom de l'utilisateur à ajouter
		 * @return {void}
		 */
		public function setPostnom($postnom)
		{
			if (!is_string($postnom) || empty($postnom)) {
				$this->errors[] = 'Post-nom invalide';
			}

			if (strlen($postnom) < 3) {
				$this->errors[] = 'Le Post-nom minimum 3 caractères';
			}

			$this->postnom = $postnom;
		}

		/**
		 * Modifie le telephone
		 * @param {String} $telephone Le telephone de l'utilisateur à ajouter
		 * @return {void}
		 */
		public function setTelephone($telephone)
		{
			if (empty($telephone)) {
				$this->errors[] = 'Telephone invalide';
			}

			if (strlen($telephone) < 10) {
				$this->errors[] = 'Le telephone minimum 10 chiffres';
			}

			$this->telephone = $telephone;
		}

		/**
		 * Modifie le email
		 * @param {String} $email L' email de l'utilisateur à ajouter
		 * @return {void}
		 */
		public function setEmail($email)
		{
			if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->errors[] = 'Email invalide';
			}

			$this->email = $email;
		}

		/**
		 * Modifie le password
		 * @param {String} $password Le password de l'utilisateur à ajouter
		 * @return {void}
		 */
		public function setPassword($password)
		{
			if (empty($password)) {
				$this->errors[] = 'password invalide';
			}

			if (strlen($password) < 8) {
				$this->errors[] = 'Le password minimum 8 caractères';
			}

			$this->password = $password;
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

		// LES GETTERS //

		public function nom()
		{
			return $this->nom;
		}

		public function postnom()
		{
			return $this->postnom;
		}

		public function prenom()
		{
			return $this->prenom;
		}

		public function etat()
		{
			return $this->etat;
		}

		public function created()
		{
			return $this->created;
		}

		public function email()
		{
			return $this->email;
		}

		public function telephone()
		{
			return $this->telephone;
		}

		public function password()
		{
			return $this->password;
		}
	}