<?php 
	namespace Applications\Frontend\Controllers;

	
	class VillesController extends \Core\Controller
	{
        //Recupere toutes les villes
		public function index($req, $res)
		{
           $this->model->setTable('villes');
           $villes = $this->model->find();
        
           $res->send($villes);
		}
	}


 ?>