# yann-soaz/YS_CPT

Plugin permettant de générer avec aisance des types de contenu personnalisés sur wordpress


## Installation

Installez le projet avec composer dans votre thème ou votre plugin.

```bash
  npm install my-project
  cd my-project
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


**paramétrage du type de contenu**
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

**récupérer le slug d'un CPT**
```php
$cadeaux = $cpt->addPostType('cadeau');
$cadeaux->getSlug();
```

### création d'une taxonomie :

```php
    $cpt->addTaxonomy('type_projet')
```
