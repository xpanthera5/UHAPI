<?php 
	namespace Applications\Frontend\Models;

	/**
	 * PagesModel
	 */
	class JobsModel extends \Core\Model
	{
		public function findJob($mot, $compagnie_id, $ville_id)
        {
           $sql = 'SELECT o.id as offre_id,
                          o.titre as offre_titre,
                          o.description as offre_description,
                          o.poste as offre_poste,
                          o.lien_postulation as offre_lien,
                          o.date_limite as offre_date_limite,
                          o.created_at as offre_date_creation,
                          c.id as offre_type_contrat_id,
                          c.slug as offre_type_contrat_slug,
                          c.duree as offre_type_contrat_duree,
                          v.id as offre_ville_id,
                          v.id_pays as offre_ville_pays_id,
                          v.slug as offre_ville_slug,
                          cp.id as offre_compagnie_id,
                          cp.nom as offre_compagnie_nom,
                          cp.statut as offre_compagnie_statut,
                          ne.id as offre_niveau_experience_id,
                          ne.begin as offre_niveau_experience_begin,
                          ne.unite as offre_niveau_experience_unite,
                          ne.end as offre_niveau_experience_end

                    FROM offres_demplois as o

                    LEFT JOIN type_contrat as c
                    ON o.type_contrat_id = c.id

                    LEFT JOIN villes as v
                    ON o.ville_id = v.id

                    LEFT JOIN compagnie as cp
                    ON o.compagnie_id = cp.id

                    LEFT JOIN niveau_experience as ne
                    ON o.niveau_experience_id = ne.id

                    ORDER BY o.created_at
            ';
            
    

           if (empty($mot) and empty($compagnie_id) and empty($ville_id)) {
               
               $q = $this->db->query($sql);
               
           } else {
               return "Non vide";
           }

           return $q->fetchAll(\PDO::FETCH_OBJ);

           
        }
	}


 ?>