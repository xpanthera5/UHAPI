<?php 
	namespace Applications\Frontend\Models;

	/**
	 * PartenairesModel
	 */
	class PartenairesModel extends \Core\Model
	{
		use \Traits\TraitModel;

		public function __construct()
		{
			parent::__construct();
			$this->setTable('partenaires');
		}

		/**
		 * Permet d'enregistrer un partenaire
		 * @param {Array} $partenaire Les données du partenaire à inserer
		 * @return {Object} $resPartenaire Les données du partenaire créé
		 */
		public function createPartenaire($partenaire)
		{
			return $this->add($partenaire);
		}

		/**
		 * Renvoi les différents partenairs
		 * @param {Int} $limit
		 * @return {Array} $partenaires
		 */
		public function getPartenaires($limit = null)
		{
			$cond = [];
			if ($limit) {
				$cond['limit'] = $limit;
			}

			return $this->find($cond);
		}
	}


 ?>