<?php
    require_once 'my_framework/config.php';

    class routeur {
        private $accueil = HOME;

        private function erreur($msg_erreur) {
            echo ('<p>'.$msg_erreur.'</p>');
        }

        private function creation_controleur($fichier) {
            // verifie que le controleur rechercher existe et le cree
            if (file_exists(DOSSIER_FRAMEWORK.'controleur.php')) {
                require_once DOSSIER_FRAMEWORK.'controleur.php';
                if (file_exists(DOSSIER_CONTROLEUR.$fichier.EXT_FICHIER_PHP_CONTROLEUR)) {
                    require_once DOSSIER_CONTROLEUR.$fichier.EXT_FICHIER_PHP_CONTROLEUR;
                    $nom_class = $fichier;
                    $nom_fonction = $fichier;
                    $parametre = [null];
                    $class = new $nom_class();
                    call_user_func_array([$class, $nom_fonction], $parametre);
                } else {
                    throw new Exception('la page correspondant à la requete n\'existe pas faute du controleur : '.$fichier.'');
                }
            } else {
                throw new Exception('la page correspondant à la requete n\'existe pas faute du controleur : controleur');
            }
        }

        public function redirection() {
            try {
                // verifie si une page peut etre rechercher
                if(isset($_GET['page'])) {
                    $fichier = $_GET['page'];
                } else if ($this->accueil != null) {
                    $fichier = $this->accueil;
                } else {
                    throw new Exception("aucune page n'est affecter a la requete");
                }
                $this->creation_controleur($fichier);
            } catch (Exception $e) {
                $this->erreur($e->getMessage());
                die();
            }
        }
    }
?>
