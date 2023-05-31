# yann-soaz/YS_CPT

Plugin permettant de générer avec aisance des types de contenu personnalisés sur wordpress


## Installation

Installez le projet avec composer dans votre thème ou votre plugin.

```bash
  composer require yann-soaz/ys_cpt
```

Si ce n'est pas déjà fait, importez l'autoload :

```php
  require('vendor/autoload.php');
```

## Utilisation

Appelez le manager :

```php
  $cpt = YS_PostTypeManager::get();
```

### création d'un type de contenu :

#### basique :
```php
    $cpt->addPostType('projets')
```

#### avancée :
**générer les labels (en français) :**
```php
$projets = ($cpt->addPostType('projets'))
                ->generateLabels('projet');
```

**générer des labels avec un pluriel autre que l'ajout d'un "s" en fin de mot**
```php
$cadeaux = ($cpt->addPostType('cadeau'))
                ->generateLabels('cadeau', 'cadeaux');
```

**générer des labels féminins**
```php
$eaux = ($cpt->addPostType('eaux'))
                ->generateLabels('eau', 'eaux', true);
```

**saisir un ou plusieurs labels manuellement :**
```php
$projets = ($cpt->addPostType('eau'))
                ->setLabels([
                    'not_found' => 'aucune eau n\'a été trouvée... Bonne désidratation !',
                    'add_new' => 'Créer un nouvelle eau'
                ]);
```
*Note : Les labels remplaceront ceux générés automatiquement mais ne supprimeront pas ceux qui ne sont pas passés en argument.*

**positionner dans le menu d'admin de wordpress**
```php
$cadeaux = ($cpt->addPostType('cadeau'))
                ->menuBefore('post'); // positionne avant le contenu article
```

```php
$cadeaux = ($cpt->addPostType('cadeau'))
                ->menuAfter('post'); // positionne après le contenu article
```

*la liste des positions disponnibles sont :
    'dashboard',
    'posts',
    'media',
    'links',
    'pages',
    'comments',
    'appearances',
    'plugins',
    'users',
    'tools',
    'settings'*

**changer l'icone du menu admin**
```php
$cadeaux = ($cpt->addPostType('cadeau'))
                ->menuIcon('dashicons-buddicons-community');
```
*liste des icones disponnible : https://developer.wordpress.org/resource/dashicons* 

**récupérer le slug d'un CPT**
```php
$cadeaux = $cpt->addPostType('cadeau');
$cadeaux->getSlug();
```

**Ajouter une ou plusieurs action à la sauvegarde d'un contenu**
```php
$cadeaux = $cpt->addPostType('cadeau');
$cadeaux->getSlug();
```

**paramétrage personnalisé du type de contenu**
```php
$eaux = ($cpt->addPostType('eaux'))
                ->setArgs([
                    'taxonomies' => ['categories', 'tags']
                ]);
```

les arguments de base des contenus sont : 
```php
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
```


### création d'une taxonomie :

#### basique

```php
    $cpt->addTaxonomy('type_projet')
```

#### avancée

**Ajouter à un type de contenu**
```php
    $cadeaux = $cpt->addPostType('cadeau');

    $type_projet = $cpt->addTaxonomy('type_projet');
    $type_projet->addPostTypes('page', 'post', $cadeaux->getSlug());
```

**Retirer la hiérarchisation des termes de taxonomie**
```php
    $type_projet = $cpt->addTaxonomy('type_projet');
    $type_projet->isTag();
```

**Générer les labels**
La génération des labels se fait de la même mannière que pour les types de contenus

```php
    $methodo = $cpt->addTaxonomy('methodo');
    $methodo->generateLabels('méthodologie', 'méthodologies', true); // singulier, pluriel, féminin (bool)
```
Personnaliser les labels
```php
    $methodo = $cpt->addTaxonomy('methodo');
    $methodo->setLabels([
      'new_item_name' => 'Nom de la nouvelle méthodologie.'
    ]);
```
Labels par défaut :
```php
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
```

**Personnaliser la taxonomie**
```php
    $methodo = $cpt->addTaxonomy('methodo');
    $methodo->setArgs([
      'show_in_rest' => false
    ]);
```
Arguments par défaut :
```php
    [
      // Hierarchical taxonomy (like categories)
      'hierarchical' => true,
      'show_in_rest' => true,
      // Control the slugs used for this taxonomy
      'rewrite' => [
        'slug' => $this->slug, // This controls the base slug that will display before each term
        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
      ]
    ];
```
