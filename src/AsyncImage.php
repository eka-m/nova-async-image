<?php

namespace Eka\AsyncImage;

use Laravel\Nova\Fields\Field;

class AsyncImage extends Field
{

    public string $path = "/";
    public string $disk = "public";

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
        $this->withMeta(['folder' => $this->path, 'disk' => $this->disk]);
    }

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'async-image';

    public function path(string $path = "menu_items")
    {
        return $this->withMeta(['folder' => $path]);
    }

    public function disk(string $disk = "public")
    {
        return $this->withMeta(['disk' => $disk]);
    }
}
