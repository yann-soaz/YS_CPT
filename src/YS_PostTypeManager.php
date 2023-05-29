<?php
namespace YannSoaz\YsCpt;
use YannSoaz\YsCpt\YS_PostType;

/**
 * Class à instanciation unique pour gérer la création de "custom post type" dans les thèmes et plugins wordpress
 */
class YS_PostTypeManager {
    /**
   * @var Ys_PostType
   * @access private
   * @static
   */
  private static ?YS_PostTypeManager $_instance = null;
  private array $postTypes = [];
  private array $taxs = [];

  /**
   * Constructeur
   * La construction ne peut se faire d'en interne.
   */
  private function __construct () {
    if (function_exists('add_action')) {
      add_action( 'init', [$this, 'registerCTP'] );
    }
  }

   /**
    * Méthode qui crée l'unique instance de la classe
    * si elle n'existe pas encore puis la retourne.
    *
    * @param void
    * @return YS_PostTypeManager
    */
    public static function get (): YS_PostTypeManager {
    if (is_null(self::$_instance)) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  /**
   * Ajoute un type de contenu à enregistrer
   * @param string $slug
   * @return YS_Taxonomy
   */
  public function addTaxonomy (string $slug): YS_Taxonomy {
    $this->taxs[] = new YS_Taxonomy($slug);
    return $this->taxs[(sizeof($this->taxs) - 1)];
  }

  /**
   * Ajoute une Taxonomie à enregistrer
   * @param string $slug
   * @return Ys_PostType
   */
  public function addPostType (string $slug): YS_PostType {
    $this->postTypes[] = new YS_PostType($slug);
    return $this->postTypes[(sizeof($this->postTypes) - 1)];
  }

  /**
   * Enregistrement automatique des post types renseigné dans le hook init
   */
  public function registerCTP (): void {
    foreach($this->postTypes as $cpt) {
      $cpt->register();
    }
    foreach($this->taxs as $tax) {
      $tax->register();
    }
  }
}