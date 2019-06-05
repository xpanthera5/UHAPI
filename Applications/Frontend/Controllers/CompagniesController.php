<?php 
	namespace Applications\Frontend\Controllers;

	/**
	 * Utilisé pour certains fichiers qui n'ont pas de catégorie
	 * comme des fichiers pour l'accueil
	 */
	class CompagniesController extends \Core\Controller
	{
        //Recupere toutes les entreprises
		public function index($req, $res)
		{
           $this->model->setTable('compagnie');
           $compagnies = $this->model->find();
        
           $res->send($compagnies);
		}
	}


 ?>