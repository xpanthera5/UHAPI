<?php
	namespace Traits;
	
	trait TraitController
	{
		/**
		 * Stock l'instance de la classe UploadFile et appelle sa méthode upload
		 * @param {FILES} $file Le fichier à uploader
		 * @param {String} $type Le type du fichier à uploader
		 * @param {String} $folder Le chemin de destination du fichier à uploader
		 * @return {Array} $resultUpload Le tableau du résultat de l'upload
		 */
		public static function uploadFile($file, $type, $folder)
		{
			$uploadFile = new \ZUpload\UploadFile($file);
			$resultUpload = $uploadFile->upload($type, $folder);

			return $resultUpload;
		}
	}