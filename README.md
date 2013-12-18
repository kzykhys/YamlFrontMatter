YAML Front-matter Parser/Dumper for PHP
=======================================

A PHP [YAML Front-matter](http://jekyllrb.com/docs/frontmatter/) parser/dumper using symfony/yaml.

Requirements
------------

* PHP5.3+

Installation
------------

Create or update your composer.json and run `composer update`

``` json
{
    "require": {
        "kzykhys/front-matter": ">=1.0"
    }
}
```

Usage
-----

### FrontMatter::parse(string $input)

``` php
<?php

require __DIR__ . '/vendor/autoload.php';

use KzykHys\FrontMatter\FrontMatter;

// Parse a document
$document = FrontMatter::parse(<<<EOF
---
title: Hello World
category: blog
layout: post
tags:
    - technology
    - PHP
vars:
    pingback_url: http://example.com/pingback?id={{post.id}}
    description: ~
extra:
    markdown:
        gfm: true
        textile: true
    template: twig
---

{% block content %}
    Lorem Ipsum ...
{% endblock %}
EOF
);

var_dump($document); // An instance of `Document`
var_dump($document->getConfig()); // An array {title:"Hello World", category: "blog"....}
var_dump($document->getContent()); // "{% block content %}...."
var_dump((string) $document); // "{% block content %}...."
var_dump($document["layout"]); // "post"
var_dump($document["extra"]["template"]); // "twig"

foreach ($document as $key => $value) {
    var_dump($key, $value); // "title", "Hello World"
    break;
}
```

### FrontMatter::dump(Document $document)

``` php
<?php

use KzykHys\FrontMatter\Document;
use KzykHys\FrontMatter\FrontMatter;

require __DIR__ . '/vendor/autoload.php';

$document = new Document();
$document['title'] = 'Hello!';
$document['tags'] = array('uncategorized', 'test');
$document->setContent('<p>Hello World!</p>');

echo FrontMatter::dump($document);
```

will output:

```
---
title: Hello!
tags:
    - uncategorized
    - test
---
<p>Hello World!</p>
```

### Document::inherit(Document $parent)

``` php
$parent = FrontMatter::parse(<<<EOF
---
extra:
    template: twig
category: blog
tags:
    - PHP
---

Lorem Ipsum ...
EOF
);

$document = FrontMatter::parse(<<<EOF
---
extra:
    template: smarty
tags:
    - HHVM
    - PHP5.5
---

Lorem Ipsum ...
EOF
);

$document->inherit($parent);
var_dump($document->getConfig());
```

```
array(2) {
  'extra' => array(1) {
    'template' => string(6) "smarty"
  }
  'category' => string(4) "blog"
  'tags' => array(3) {
    [0] => string(3) "PHP"
    [1] => string(4) "HHVM"
    [2] => string(6) "PHP5.5"
  }
}
```

License
-------

The MIT License

Author
------

Kazuyuki Hayashi (@kzykhys)