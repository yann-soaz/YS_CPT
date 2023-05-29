<?php

namespace YannSoaz\YsCpt;

class YS_Taxonomy {

  private string $slug = '';
  private array $args = [];
  private array $labels = [];
  private array $contentTypes = [];

  public function __construct (string $slug) {
    $this->slug = $this->verifySlug($slug);
    $this->labels = [
      'name' => $this->slug,
      'singular_name' => $this->slug,
      'search_items' =>  'Search '.$this->slug,
      'all_items' => 'All '.$this->slug,
      'parent_item' => 'Parent '.$this->slug,
      'parent_item_colon' => 'Parent '.$this->slug.':',
      'edit_item' => 'Edit '.$this->slug,
      'update_item' => 'Update '.$this->slug,
      'add_new_item' => 'Add New '.$this->slug,
      'new_item_name' => 'New '.$this->slug.' Name',
      'menu_name' => $this->slug,
    ];
    $this->args = [
      // Hierarchical taxonomy (like categories)
      'hierarchical' => true,
      'show_in_rest' => true,
      // Control the slugs used for this taxonomy
      'rewrite' => [
        'slug' => $this->slug, // This controls the base slug that will display before each term
        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
      ]
    ];
  }


  /**
   * Met à jour la liste des labels pour la création de la taxonomie
   */
  public function setLabels (array $labels): YS_Taxonomy {
    $this->labels = array_merge($this->labels, $labels);
    return $this;
  }

  /**
   * Met à jour la liste des arguments pour la création de la taxonomie
   */
  public function setArgs (array $args): YS_Taxonomy {
    $this->labels = array_merge($this->args, $args);
    return $this;
  }

  /**
   * Ajoute un ou plusieurs post type à la taxonomie créée
   * @param string[] $post_slugs
   */
  public function addPostTypes (string ...$post_slugs): YS_Taxonomy {
    foreach ($post_slugs as $slug) {
      if (!in_array($slug, $this->contentTypes))
        $this->contentTypes[] = $slug;
    }
    return $this;
  }

  /**
   * Supprime l'aspect hierarchique de la taxonomie
   */
  public function isTag (): YS_Taxonomy {
    $this->args['hierarchical'] = false;
    return $this;
  }

  /**
   * Récupère le slug de la taxonomie
   */
  public function getSlug (): string {
    return $this->slug;
  }


  /**
   * Helper permettant la génération des labels
   * @param string singular
   * @param ?string plural écriture du nom au pluriel, ajoute un s à la fin du singulier par défaut
   * @param string faminin si le mot est féminin pour ajuster les labels
   */
  public function generateLabels (string $singular, ?string $plural = null, bool $feminin = false): YS_Taxonomy {
    $plural = (is_null($plural)) ? $singular.'s' : $plural;
    $this->labels = [
      'name' => $plural,
      'singular_name' => $singular,
      'search_items' =>  'rechercher des '.$plural,
      'all_items' => 'Toutes les '.$plural,
      'parent_item' => sprintf("%s %s", $singular, ( ($feminin) ? 'parente' : 'parent' ) ),
      'parent_item_colon' => sprintf("%s %s :", $singular, ( ($feminin) ? 'parente' : 'parent' ) ),
      'edit_item' => sprintf( 'Modifier %s %s.', ( ($feminin) ? 'la' : 'le' ), $singular ),
      'update_item' => sprintf( 'Mettre à jour %s %s.', ( ($feminin) ? 'la' : 'le' ), $singular ),
      'add_new_item' => sprintf("Ajouter %s %s.",( ($feminin) ? 'une nouvelle' : 'un nouveau' ), $singular),
      'new_item_name' => 'Nouveau nom de '.$singular,
      'menu_name' => $plural,
      'popular_items' => $plural.' populaires',
      'separate_items_with_commas' => 'Séparez les '.$plural.' par des virgules.',
      'add_or_remove_items' => 'Ajouter ou supprimer des '.$plural,
      'choose_from_most_used' => sprintf( 'Choisissez les %s les plus %s.', $plural, ( ($feminin) ? 'utilisées' : 'utilisés' )),
      'not_found' => sprintf( '%s %s %s.', ( ($feminin) ? 'aucunes' : 'aucun' ), $singular, ( ($feminin) ? 'Trouvée' : 'trouvé' ) ),
    ];
    return $this;
  }

  /**
   * Enregistre la taxonomie
   */
  public function register (): void {
    $params = $this->args;
    $params['labels'] = $this->labels;
    // Add new "Locations" taxonomy to Posts
    register_taxonomy($this->slug, $this->contentTypes, $params);
  }

    /**
   * Valide le format du slug du type de contenu
   * @param string text
   * @return string
   */
  private function verifySlug (string $text): string {
    $slugText = $this->slugify($text);
    if ($text !== $slugText) {
      $text = $slugText;
      // trigger_error('Le slug renseigné est invalide, il sera remplacé par : '.$text, E_USER_WARNING);
    }
    return $text;
  }

  /**
   * Standardise le slug si le format est invalide
   * @param string text
   * @param string divider
   * @return string
   */
  private function slugify(string $text, string $divider = '-'): string {
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // trim
    $text = trim($text, $divider);
    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);
    // lowercase
    $text = strtolower($text);
    if (empty($text)) {
      return 'n-a';
    }
    return $text;
  }
}