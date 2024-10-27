<?php 
    class modele {
        private $bdd;

        private function connexion_bdd ($fichier_connexion) {
            try {
                if (file_exists($fichier_connexion)) {
                    require_once $fichier_connexion;
                } else {
                    throw new Exception("fichier de connexion non trouver");
                    
                }
                $this->bdd = new PDO(NOM_BDD, BASIC_UTILISATEUR, BASIC_MDP);
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage() . "<br/>";
                    die();
            }
        }

        protected function obtenir_bdd() {
            $fichier_connexion = DOSSIER_FRAMEWORK."acces_bdd.php";
            if (!isset($this->bdd)) {
                $this->connexion_bdd($fichier_connexion);
            }
            return ($this->bdd);
        }
    }
?>
