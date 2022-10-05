<?php
class YS_PostType {
  private array $menu_order = [
    'dashboard' => 2,
    'posts' => 5,
    'media' => 10,
    'links' => 15,
    'pages' => 20,
    'comments' => 25,
    'appearances' => 60,
    'plugins' => 65,
    'users' => 70,
    'tools' => 75,
    'settings' => 80,
  ];
  private string $slug = '';
  private array $labels = [];
  private array $args = [];
  private ?callable $onSave = null;

  /**
   * Construit les types de contenus
   * @param string slug
   * @param array args
   * @return string
   */
  public function __construct (string $slug) {
    $this->slug = $this->verifySlug($slug);
    $this->labels = [
      'name'               => "$this->slug",
      'singular_name'      => "$this->slug",
      'add_new'            => "Ajouter un $this->slug",
      'add_new_item'       => "Ajouter un $this->slug",
      'edit_item'          => "Modifier $this->slug",
      'new_item'           => "Nouveau $this->slug",
      'view_item'          => "Voir $this->slug",
      'search_items'       => "Chercher $this->slug",
      'not_found'          => "aucun $this->slug trouvé",
      'not_found_in_trash' => "aucun $this->slug trouvé dans la corbeille'",
      'parent_item_colon'  => "$this->slug parent :'",
      'menu_name'          => "$this->slug trouvé'",
    ];
    $this->args = [
		'hierarchical'        => true,
		'description'         => 'description',
		'taxonomies'          => [],
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
    'show_in_rest'        => true,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post', 
		'supports'            => [
        'title', 'editor', 'author', 'thumbnail', 
        'custom-fields', 'trackbacks', 'comments', 
        'revisions', 'page-attributes', 'post-formats'
      ],
    ];
  }

  /**
   * Helper permettant la génération des labels
   * @param string singular
   * @param ?string plural ajoute un s à la fin du mot par défaut
   * @param string faminin si le mot est féminin pour ajuster les labels
   */
  public function generateLabels (string $singular, ?string $plural, bool $feminin = false) {
    $this->labels = [
      'name'               => (is_null($plural)) ? $singular+'s' : $plural,
      'singular_name'      => $singular,
      'add_new'            => sprintf("Ajouter %s %s.",( ($feminin) ? 'une nouvelle' : 'un nouveau' ), $singular),
      'add_new_item'       => sprintf("Ajouter %s %s.",( ($feminin) ? 'une nouvelle' : 'un nouveau' ), $singular),
      'edit_item'          => sprintf( 'Modifier %s %s.', ( ($feminin) ? 'la' : 'le' ), $singular ),
      'new_item'           => sprintf("%s %s.",( ($feminin) ? 'Nouvelle' : 'Nouveau' ), $singular),
      'view_item'          => sprintf("Voir %s %s %s.",( ($feminin) ? 'la' : 'le' ), $singular),
      'search_items'       => sprintf( 'Rechercher des %s.', ((is_null($plural)) ? $singular+'s' : $plural) ),
      'not_found'          => sprintf("%s %s %s.",( ($feminin) ? 'Aucune' : 'Aucun' ), $singular, (($feminin) ? 'trouvée' : 'trouvé') ),
      'not_found_in_trash' => sprintf("%s %s %s dans la corbeille.",( ($feminin) ? 'Aucune' : 'Aucun' ), $singular, (($feminin) ? 'trouvée' : 'trouvé') ),
      'parent_item_colon'  => sprintf("%s %s :", $singular, ( ($feminin) ? 'parente' : 'parent' ) ),
      'menu_name'          => sprintf("%s %s.", ((is_null($plural)) ? $singular+'s' : $plural), (($feminin) ? 'trouvées' : 'trouvés') ),
    ];
    return $this;
  }

  /**
   * Renseigne les labels de wordpress
   */
  public function setLabels (array $labels) {
    $this->label = array_merge($this->labels, $labels);
    return $this;
  }

  /**
   * Renseigne les arguments de base des CPTs de wordpress
   */
  public function setArgs (array $labels) {
    $this->label = array_merge($this->args, $labels);
    return $this;
  }

  /**
   * Pose le menu de contenu avant un élément de l'admin wordpress
   * @param string $item __ dashboard,posts,media,links,pages,comments,appearances,plugins,users,tools,settings
   */
  public function menuBefore (string $item) {
    if (!in_array($item, array_keys($this->menu_order)))
      $item = 'post';

    $this->args['menu_position'] = $this->menu_order[$item] - 1;
    return $this;
  }

  /**
   * Pose le menu de contenu après un élément de l'admin wordpress
   * @param string $item __ dashboard,posts,media,links,pages,comments,appearances,plugins,users,tools,settings
   */
  public function menuAfter (string $item) {
    if (!in_array($item, array_keys($this->menu_order)))
      $item = 'post';

    $this->args['menu_position'] = $this->menu_order[$item] + 1;
    return $this;
  }

  /**
   * Ajoute une icone au Custom Post Type
   * @param string $item __ dashboard,posts,media,links,pages,comments,appearances,plugins,users,tools,settings
   */
  public function menuIcon (string $icon) {
    $this->args['menu_icon'] = $icon;
    return $this;
  }


  /**
   * Valide le format du slug du type de contenu
   * @param string text
   * @return string
   */
  private function verifySlug (string $text): string {
    if (!preg_match('/[a-z]{1}[a-z0-9\-]*/i', $text)) {
      $text = $this->slugify($text);
      trigger_error('Le slug renseigné est invalide, il sera remplacé par : '.$text, E_USER_WARNING);
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

  public function register () {
    $params = $this->args;
    $params['labels'] = $this->labels;
    register_post_type( $this->slug, $params );
  }

}