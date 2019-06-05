<?php 
	namespace Applications\Frontend\Models;

	use Applications\Frontend\Models\Entities\UsersEntity;

	/**
	 * UsersModel
	 */
	class UsersModel extends \Core\Model
	{
		use \Traits\TraitModel;
		
		public function __construct()
		{
			parent::__construct();
			$this->setTable('utilisateurs');
		}

		/**
		 * Enregistre un nouvel utilisateur
		 * @param {UsersEntity} $user L'objet de données de l'utilisateur
		 * @return {*} $user
		 */
		public function createUser(UsersEntity $user)
		{
			return $this->add([
				'nom' => $user->nom(),
				'email' => $user->email(),
				'prenom' => $user->prenom(),
				'password' => bcrypt_hash_password($user->password())
			]);
		}

		/**
		 * Renvoi les informations sur un utilisateurs partant de son email
		 * @param {String} $email L'email de l'utilisateur
		 * @return {Object} $user Les données de l'utilisateur
		 */
		public function getUserByEmail($email)
		{
			$q = $this->db->prepare('SELECT * FROM utilisateurs WHERE email = :email');
			$q->execute(['email' => $email]);
			return current($q->fetchAll(\PDO::FETCH_OBJ));
		}

		/**
		 * Permet de faire connecter un utilisateur
		 * @param {UsersEntity} $user L'objet de données de l'utilisateur
		 * @return {*} $user
		 */
		public function logInUser(UsersEntity $user)
		{
			return $this->findOne([
				'cond' => 'email='.$user->email().' AND password='.$user->password()
			]);

			// return $this->add([
			// 	'nom' => $user->nom(),
			// 	'email' => $user->email(),
			// 	'prenom' => $user->prenom(),
			// 	'telephone' => $user->telephone(),
			// 	'password' => $user->password()
			// ]);
		}
	}


 ?>