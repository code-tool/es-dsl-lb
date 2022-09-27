<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch;

use CodeTool\ElasticSearch\DSL\ElasticSearchQueryInterface;

class ElasticSearchScript implements ElasticSearchQueryInterface
{
    private string $script;

    private string $type = '';

    private string $lang = '';

    private array $params = [];

    public function __construct(string $script)
    {
        $this->script = $script;
    }

    public function script(string $script): self
    {
        $this->script = $script;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function lang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function param(string $name, $value): self
    {
        $this->params[$name] = $value;

        return $this;
    }

    /**
     * @return string|array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        if ('' === $this->type && '' === $this->lang && [] === $this->params) {
            return $this->script;
        }

        $result = [];
        if ('' === $this->type) {
            $result['source'] = $this->script;
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
