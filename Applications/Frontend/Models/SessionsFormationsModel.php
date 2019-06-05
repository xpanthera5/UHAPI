<?php 
	namespace Applications\Frontend\Models;

	use \Core\Model;

	/**
	 * SessionsFormationsModel
	 */
	class SessionsFormationsModel extends Model
	{
		public function __construct()
		{
			parent::__construct();

			$this->setTable('sessions_formations');
			$this->desactiveSessionsExpires();
		}

		/**
		 * Renvoi toutes les sessions de toutes les formations
		 * @param {Integer} $limit La limite
		 * @param {String} $order La limite
		 * @return {Object} $sessions
		 */
		public function getAllSessions($limit = null, $order = null)
		{
			$sql = 'SELECT S.id AS id_session, S.etat,
						   S.date_debut AS date_debut_session,
						   S.date_fin 	AS date_fin_session,
						   F.id_sous_cat, F.titre, F.description, F.prerequis,
						   F.objectif, F.public_concerne, F.poster, F.lieu,
						   M.path AS media,
						   SC.nom AS sous_categorie
					FROM formations AS F,
						 sessions_formations AS S,
						 sous_categories_formation AS SC,
						 medias AS M
					WHERE M.id = F.id_media
					  AND F.id = S.id_formation
					  AND SC.id = F.id_sous_cat
					  AND SC.etat = "1"
					  AND S.etat = "1"
					  AND F.etat = "1"
					  AND M.etat = "1"';

			$sql .= $order ? ' ORDER BY '.$order : '';
			$sql .= $limit ? ' LIMIT '.$limit : '';

			// debug($sql);

			$q = $this->db->prepare($sql);
			$q->execute();
			$sessions = $q->fetchAll(\PDO::FETCH_OBJ);

			return $sessions;
		}

		/**
		 * Permet de désactiver les sessions qui ont atteintes leurs date de début
		 * @return {Boolean} true
		 */
		public function desactiveSessionsExpires()
		{
			$date = [
				'annee' => date('Y'),
				'mois'	=> date('m'),
				'jour'	=> date('d')
			];

			$sql = 'UPDATE sessions_formations SET etat = "0"
					WHERE etat="1"
					  AND YEAR(date_fin)  <='.$date['annee'].'
					  AND MONTH(date_fin) <='.$date['mois'].'
					  AND DAY(date_fin)   <='.$date['jour'];
			$q = $this->db->prepare($sql);
			$q->execute();

			return true;
		}
	}