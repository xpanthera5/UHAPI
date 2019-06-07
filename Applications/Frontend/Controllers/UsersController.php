<?php 
	namespace Applications\Frontend\Controllers;

	use \Core\Controller;
	use Applications\Frontend\Models\Entities\UsersEntity;
	use OpenApi\Annotations AS OA;
	

	/**
	 * Gère les utilisateurs
	 */
	class UsersController extends Controller
	{
		public function index($req, $res)
		{
			$res->send(['nom' => 'moliso']);
		}

		
		public function register($req, $res)
		{
			if ($req->bodyNotEmpty(['email', 'nom', 'prenom', 'password'])) {
				$user = new UsersEntity($req->body());

				if ($this->model->isAlreadyUsed('email', $req->body('email'), 'utilisateurs')) {
					$user->addError('Email déjà utilisé');
				}

				if ($user->isValid()) {

					$result = $this->model->createUser($user);

					$this->objetRetour['success'] = true;
					$this->objetRetour['message'] = 'Utilisateur bien ajouté';
					$this->objetRetour['data'] = $result;
				}else {
					$this->objetRetour['message'] = implode(' <br> ', $user->errors());
				}
			}else {
				$this->objetRetour['message'] = 'Veuillez remplir tous les champs';
			}

			$res->send($this->objetRetour);
		}

		/**
		 * L'inscription d'un nouvel utilisateur
		 * @see \Core\Request
		 * @see \Core\Response
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function login($req, $res)
		{
			if ($req->bodyNotEmpty(['email', 'password'])) {
				$user = new UsersEntity($req->body());

				if ($user->isValid()) {

					if ($this->model->isAlreadyUsed('email', $req->body('email'), 'utilisateurs')) {

						$result = $this->model->getUserByEmail($req->body('email'));

						// debug($result);

						if (bcrypt_verify_password($req->body('password'), $result->password)) {
							$this->objetRetour['success'] = true;
							$this->objetRetour['message'] = 'Utilisateur bien connecté';
							$this->objetRetour['data'] = $result;
						}else {
							$this->objetRetour['message'] = 'Mot de passe invalide';
						}
						
					}else {
						$this->objetRetour['message'] = 'Email invalide';					}
				}else {
						$this->objetRetour['message'] = implode(' <br> ', $user->errors());
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
		// 	  ->setUsername('don')
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