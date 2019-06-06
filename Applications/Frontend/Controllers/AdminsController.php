<?php 
	namespace Applications\Frontend\Controllers;

	use \Core\Controller;
	use Applications\Frontend\Models\Entities\AdminsEntity;

	/**
	 * Gère les admins
	 */
	class AdminsController extends Controller
	{
		/**
		 * L'inscription d'un nouvel admin
		 * @param {Request} $req @see \Core\Request
		 * @param {Response} $res @see \Core\Response
		 */
		public function createAdmin($req, $res)
		{
			if ($req->bodyNotEmpty(['email', 'nom', 'prenom', 'password', 'type_admin'])) {
				$admin = new AdminsEntity($req->body());

				if ($this->model->isAlreadyUsed('email', $req->body('email'), 'admins')) {
					$admin->addError('Email déjà utilisé');
				}

				if ($admin->isValid()) {

					$result = $this->model->createAdmin($admin);

					$this->objetRetour['success'] = true;
					$this->objetRetour['message'] = 'Administrateur bien ajouté';
					$this->objetRetour['data'] = $result;
				}else {
					$this->objetRetour['message'] = implode(' <br> ', $admin->errors());
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez remplir tous les champs';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * L'inscription d'un nouvel admin
		 * @param {Request} $req @see \Core\Request
		 * @param {Response} $res @see \Core\Response
		 */
		public function login($req, $res)
		{
			if ($req->bodyNotEmpty(['email', 'password'])) {
				$admin = new AdminsEntity($req->body());

				if ($admin->isValid()) {

					if ($this->model->isAlreadyUsed('email', $req->body('email'), 'admins')) {

						$result = $this->model->getAdminByEmail($req->body('email'));

						if (bcrypt_verify_password($req->body('password'), $result->password)) {
							$this->objetRetour['success'] = true;
							$this->objetRetour['message'] = 'Admin bien connecté';
							$this->objetRetour['data'] = $result;
						}else {
							$this->objetRetour['message'] = 'Mot de passe invalide';
						}
						
					}else {
						$this->objetRetour['message'] = 'Email invalide';					}
				}else {
					$this->objetRetour['message'] = implode(' <br> ', $admin->errors());
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez remplir tous les champs';
			}

			$res->send($this->objetRetour);
		}

		// public function mail($req, $res)
		// {
		// 	// Create the Transport
		// 	$transport = (new \Swift_SmtpTransport('smtp.example.org', 25))
		// 	  ->setadminname('don')
		// 	  ->setPassword('123456789')
		// 	;

		// 	// Create the Mailer using your created Transport
		// 	$mailer = new \Swift_Mailer($transport);

		// 	// Create a message
		// 	$message = (new \Swift_Message('Wonderful Subject'))
		// 	  ->setFrom(['dondedieubolenge@gmail.com' => 'Don de Dieu Bolenge'])
		// 	  ->setTo(['tobienshmoliso@gmail.com', 'gmail'])
		// 	  ->setBody('Here is the message itself')
		// 	  ;

		// 	// Send the message
		// 	$result = $mailer->send($message);

		// 	$res->send($result);
		// }
	}


 ?>