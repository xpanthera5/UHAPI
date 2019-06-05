<?php 
	namespace Applications\Frontend\Controllers;

	/**
	 * Utilisé pour certains fichiers qui n'ont pas de catégorie
	 * comme des fichiers pour l'accueil
	 */
	class JobsController extends \Core\Controller
	{
		public function index($req, $res)
		{
		   $mot = $req->post('mot') ? $req->post('mot') : null;
           $compagnie_id = $req->post('compagnie_id') ? $req->post('compagnie_id') : null;
           $ville_id = $req->post('ville_id') ? $req->post('ville_id') : null;

           $jobs = $this->model->findJob($mot, $compagnie_id, $ville_id);
        
           $res->send($jobs);
		}
	}


 ?>