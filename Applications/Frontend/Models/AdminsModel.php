<?php 
	namespace Applications\Frontend\Models;

	use Applications\Frontend\Models\Entities\AdminsEntity;
	use \Core\Model;

	/**
	 * AdminsModel
	 */
	class AdminsModel extends Model
	{
		use \Traits\TraitModel;
		
		public function __construct()
		{
			parent::__construct();
			$this->setTable('admins');
		}

		/**
		 * Enregistre un nouvel admin
		 * @param {AdminsEntity} $admin L'objet de données de l'admin
		 * @return {*} $admin
		 */
		public function createAdmin(AdminsEntity $admin)
		{
			return $this->add([
				'nom' => $admin->nom(),
				'email' => $admin->email(),
				'prenom' => $admin->prenom(),
				'password' => bcrypt_hash_password($admin->password()),
				'type_admin' => $admin->type_admin()
			]);
		}

		/**
		 * Renvoi les informations sur un admin partant de son email
		 * @param {String} $email L'email de l'admin
		 * @return {Object} $admin Les données de l'admin
		 */
		public function getAdminByEmail($email)
		{
			$q = $this->db->prepare('SELECT * FROM admins WHERE email = :email');
			$q->execute(['email' => $email]);
			return current($q->fetchAll(\PDO::FETCH_OBJ));
		}
	}