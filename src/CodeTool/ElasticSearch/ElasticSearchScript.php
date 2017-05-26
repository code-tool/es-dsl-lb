<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch;

use CodeTool\ElasticSearch\DSL\ElasticSearchQueryInterface;

class ElasticSearchScript implements ElasticSearchQueryInterface
{
    private $script = '';

    private $type = '';

    private $lang = '';

    private $params = [];

    public function __construct(string $script)
    {
        $this->script = $script;
    }

    public function script(string $script)
    {
        $this->script = $script;

        return $this;
    }

    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function lang(string $lang)
    {
        $this->lang = $lang;

        return $this;
    }

    public function param(string $name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    public function toArray(): array
    {
        if ('' === $this->type && '' === $this->lang && [] === $this->params) {
            return $this->script;
        }

        $result = [];
        if ('' === $this->type) {
            $result['inline'] = $this->script;
        } else {
            $result[$this->type] = $this->script;
        }

        if ('' !== $this->lang) {
            $result['lang'] = $this->lang;
        }

        if ([] !== $this->params) {
            $result['params'] = $this->params;
        }

        return $result;
    }
}
