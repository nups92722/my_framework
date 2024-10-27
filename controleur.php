<?php
    class controleur{
        private $action_autorise = [];
        private $table_de_coincidence = [];

        protected function ajout_action_autorise($action_autorise) {
            foreach ($action_autorise as $action) {
                if (function_exists($action)) {
                    $this->action_autorise[] = $action;
                }
            }
        }

        protected function executer_action() {
            if (isset($_GET['action'])) {
                if (in_array($_GET['action'], $this->action_autorise)) {
                    $_GET['action']();
                } else {
                    throw new Exception('l\'action : '.$action.', n\'est pas autorisé ou n\'existe pas');
                }
            }
        }

        protected function insertion_modele($fichiers) {
            $nom_fichier = null;
            $class_modeles = [];

            if (file_exists(DOSSIER_FRAMEWORK.'modele.php')) {
                require_once DOSSIER_FRAMEWORK.'modele.php';
                foreach ($fichiers as $fichier) {
                    $nom_fichier = DOSSIER_MODELE.$fichier.EXT_FICHIER_PHP_MODELE;
                    if (file_exists($nom_fichier)) {
                        require_once $nom_fichier;
                        $class_modeles[] = new $fichier();
                    } else {
                        throw new Exception('le fichier modele : '.$fichier.', n\'existe pas');
                    }
                }
            } else {
                throw new Exception('le fichier modele.php n\'existe pas');
            }
            $this->collecte_nom_fonction_modele_disponible($class_modeles);
        }

        private function collecte_nom_fonction_modele_disponible ($class_modeles) {
            foreach ($class_modeles as $class_modele) {
                $class_fonctions = get_class_methods($class_modele);
                foreach ($class_fonctions as $class_fonction) {
                    $this->table_de_coincidence[$class_fonction] = $class_modele;
                }
            }
        }

        protected function executer_modeles($modeles) {
            $resultats = [];
            
            foreach ($modeles as $modele) {
                if (isset($this->table_de_coincidence[$modele["fonction"]])) {
                    $resultat = $this->table_de_coincidence[$modele["fonction"]]->{$modele["fonction"]}($modele["variables"]);
                    if ($resultat) {
                        $resultats += $resultat;
                    }
                } else {
                    throw new Exception('le modele : '.$modele["fonction"].', n\'existe pas ou n\'est pas contenu dans les fichiers modeles charger');
                }
            }
            return ($resultats);
        }

        protected function preparation_modele($fonction, $variables = null) {
            return (array('fonction' => $fonction, 'variables' => $variables));
        }

        protected function creation_vue($fichiers, $donnees) {
            extract($donnees);
            ob_start();
            foreach ($fichiers as $fichier) {
                $nom_fichier = DOSSIER_VUE.$fichier.EXT_FICHIER_PHP_VUE;
                if (file_exists($nom_fichier)) {
                    require_once $nom_fichier;
                } else {
                    throw new Exception('le fichier vu : '.$fichier.', n\'existe pas');
                }
            }
            $contenu = ob_get_clean();
            return ($contenu);
        }
    }
?>
