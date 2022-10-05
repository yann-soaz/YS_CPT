<?php
use YS_PostType;

/**
 * Class a instanciation unique pour gérer la création de "custom post type" dans les thèmes et plugins wordpress
 */
class YS_PostTypeManager {
    /**
   * @var Ys_PostType
   * @access private
   * @static
   */
  private static ?YS_PostTypeManager $_instance = null;
  private array $postTypes = [];

  /**
   * Constructeur
   * La construction ne peut se faire d'en interne.
   */
  private function __construct () {
    add_action( 'init', [$this, 'registerCTP'] );
  }

   /**
    * Méthode qui crée l'unique instance de la classe
    * si elle n'existe pas encore puis la retourne.
    *
    * @param void
    * @return Ys_PostType
    */
    public static function get () {
    if (is_null(self::$_instance)) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  /**
   * Ajoute un type de contenu a enregistrer
   * @param string $slug
   * @return Ys_PostType
   */
  public function addPostType (string $slug): YS_PostType {
    $this->postTypes[] = new YS_PostType($slug);
    return $this->postTypes[(sizeof($this->postTypes) - 1)];
  }

  private function registerCTP () {
    foreach($this->postTypes as $cpt) {
      $cpt->register();
    }
  }
}