<?php 
	namespace Applications\Frontend\Controllers;

	use \Core\Controller;

	/**
	 * Controller de toutes les sessions des formations
	 */
	class SessionsFormationsController extends Controller
	{
		/**
		 * Renvoi toutes les sessions
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getAllSessions($req, $res)
		{
			$limit = $req->paramsNotEmpty(['limit']) ? $req->params('limit') : null;

			$sessions = $this->model->getAllSessions($limit);

			// debug($sessions);

			if ($sessions) {
				$this->objetRetour['success'] = true;
				$this->objetRetour['message'] = count($sessions)." sessions trouvées";
				$this->objetRetour['data'] = $sessions;
			}else {
				$this->objetRetour['message'] = "Aucune session disponible";
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Renvoi toutes les sessions proches
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getSessions($req, $res)
		{
			$limit = $req->paramsNotEmpty(['limit']) ? $req->params('limit') : null;

			$sessions = $this->model->getAllSessions($limit);

			// debug($sessions);

			if ($sessions) {
				$this->objetRetour['success'] = true;
				$this->objetRetour['message'] = count($sessions)." sessions trouvées";
				$this->objetRetour['data'] = $sessions;
			}else {
				$this->objetRetour['message'] = "Aucune session disponible";
			}

			$res->send($this->objetRetour);
		}

		/**
		 * Renvoi toutes les sessions proches
		 * @param {Request} $req
		 * @param {Response} $res
		 */
		public function getSessionsProche($req, $res)
		{
			$limit = $req->paramsNotEmpty(['limit']) ? $req->params('limit') : null;

			$sessions = $this->model->getAllSessions($limit, 'date_debut');

			// debug($sessions);

			if ($sessions) {
				$this->objetRetour['success'] = true;
				$this->objetRetour['message'] = "Une session a été trouvée";
				$this->objetRetour['data'] = $sessions;
			}else {
				$this->objetRetour['message'] = "Aucune session trouvée";
			}

			$res->send($this->objetRetour);
		}
	}


 ?>