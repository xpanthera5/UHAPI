<?php
	namespace Applications\Frontend\Models\Entities;

	use \Core\Entity;

	/**
	 * Classe représentant l'entité admins
	 */
	class AdminsEntity extends Entity
	{
		protected $nom,
				  $etat,
				  $email,
				  $prenom,
				  $created,
				  $postnom,
				  $password,
				  $username,
				  $telephone,
				  $type_admin;

		/**
		 * Modifie le nom
		 * @param {String} $nom Le nom de l'admin à ajouter
		 * @return {void}
		 */
		public function setNom($nom)
		{
			if (!is_string($nom) || empty($nom)) {
				$this->errors[] = 'Nom invalide';
			}

			if (strlen($nom) < 3) {
				$this->errors[] = 'Le nom doit avoir minimum 3 caractères';
			}

			$this->nom = $nom;
		}

		/**
		 * Modifie le username
		 * @param {String} $username Le username de l'admin à ajouter
		 * @return {void}
		 */
		public function setUsername($username)
		{
			if (!is_string($username) || empty($username)) {
				$this->errors[] = 'username invalide';
			}

			if (strlen($username) < 3) {
				$this->errors[] = 'Le username minimum 3 caractères';
			}

			$this->username = $username;
		}

		/**
		 * Modifie le prenom
		 * @param {String} $prenom Le prenom de l'admin à ajouter
		 * @return {void}
		 */
		public function setPrenom($prenom)
		{
			if (!is_string($prenom) || empty($prenom)) {
				$this->errors[] = 'Prenom invalide';
			}

			if (strlen($prenom) < 3) {
				$this->errors[] = 'Le prenom doit avoir minimum 3 caractères';
			}

			$this->prenom = $prenom;
		}

		/**
		 * Modifie le postnom
		 * @param {String} $postnom Le postnom de l'admin à ajouter
		 * @return {void}
		 */
		public function setPostnom($postnom)
		{
			if (!is_string($postnom) || empty($postnom)) {
				$this->errors[] = 'postnom invalide';
			}

			if (strlen($postnom) < 3) {
				$this->errors[] = 'Le postnom doit avoir minimum 3 caractères';
			}

			$this->postnom = $postnom;
		}

		/**
		 * Modifie le telephone
		 * @param {String} $telephone Le telephone de l'admin à ajouter
		 * @return {void}
		 */
		public function setTelephone($telephone)
		{
			if (empty($telephone)) {
				$this->errors[] = 'telephone invalide';
			}

			if (strlen($telephone) < 10) {
				$this->errors[] = 'Le telephone doit avoir minimum 10 caractères';
			}

			$this->telephone = $telephone;
		}

		/**
		 * Modifie le email
		 * @param {String} $email L' email de l'admin à ajouter
		 * @return {void}
		 */
		public function setEmail($email)
		{
			if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->errors[] = 'email invalide';
			}

			if (strlen($email) < 10) {
				$this->errors[] = 'Le email doit avoir minimum 10 caractères';
			}

			$this->email = $email;
		}

		/**
		 * Modifie le password
		 * @param {String} $password Le password de l'admin à ajouter
		 * @return {void}
		 */
		public function setPassword($password)
		{
			if (empty($password)) {
				$this->errors[] = 'password invalide';
			}

			if (strlen($password) < 8) {
				$this->errors[] = 'Le password doit avoir minimum 8 caractères';
			}

			$this->password = $password;
		}

		/**
		 * Modifie le etat
		 * @param {String} $etat L'état de l'admin à ajouter
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
		 * @param {String} $created Le created de l'admin à ajouter
		 * @return {void}
		 */
		public function setCreated(DateTime $created)
		{
			if (!is_int($created) || ($created > 1 && $created < 0) ) {
				$this->errors[] = 'Etat invalide';
			}else {
				$this->etat = $etat;
			}
		}

		/**
		 * Modifie le type_admin
		 * @param {String} $type_admin Le type_admin de l'admin à ajouter
		 * @return {void}
		 */
		public function setType_admin($type_admin)
		{
			if (!is_string($type_admin) || empty($type_admin)) {
				$this->errors[] = 'Type admin invalide';
			}

			if (strlen($type_admin) < 5) {
				$this->errors[] = 'Le type admin minimum 5 caractères';
			}

			$this->type_admin = $type_admin;
		}

		// LES GETTERS //

		public function nom()
		{
			return $this->nom;
		}

		public function username()
		{
			return $this->username;
		}

		public function type_admin()
		{
			return $this->type_admin;
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