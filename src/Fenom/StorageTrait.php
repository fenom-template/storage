<?php

namespace Fenom;

trait StorageTrait
{
    protected array $_vars = [];

    public function assign(string $name, mixed $variable): static
    {
        $this->_vars[$name] = $variable;
        return $this;
    }

    public function assignKey(string $name, mixed $key, mixed $variable): static
    {
        if (!is_array($this->_vars[$name] ?? null)) {
            $this->_vars[$name] = (array)($this->_vars[$name] ?? null);
        }
        $this->_vars[$name][$key] = $variable;
        return $this;
    }

    public function assignByRef(string $name, mixed &$variable): static
    {
        $this->_vars[$name] = &$variable;
        return $this;
    }

    public function assignAll(array $vars, bool $merge = true): static
    {
        if ($merge) {
            $this->_vars = $vars + $this->_vars;
        } else {
            $this->_vars = $vars;
        }
        return $this;
    }

    public function prepend(string $name, mixed $variable): static
    {
        if (!isset($this->_vars[$name])) {
            $this->_vars[$name] = [];
        }
        if (!is_array($this->_vars[$name])) {
            $this->_vars[$name] = (array)$this->_vars[$name];
        }
        array_unshift($this->_vars[$name], $variable);
        return $this;
    }

    public function append(string $name, mixed $variable): static
    {
        if (!isset($this->_vars[$name])) {
            $this->_vars[$name] = [];
        }
        if (!is_array($this->_vars[$name])) {
            $this->_vars[$name] = (array)$this->_vars[$name];
        }
        $this->_vars[$name][] = $variable;
        return $this;
    }

    public function getVars(): array
    {
        return $this->_vars;
    }

    public function resetVars(): static
    {
        $this->_vars = [];
        return $this;
    }

    public function display(array|string $template, array $vars = []): array
    {
        $scope = $vars ?: $this->_vars;
        $this->_vars = $scope;
        return $this->getTemplate($template)->display($scope);
    }

    public function fetch(array|string $template, array $vars = []): mixed
    {
        $scope = $vars ?: $this->_vars;
        $this->_vars = $scope;
        $tpl = $this->getTemplate($template);
        ob_start();
        try {
            $tpl->display($scope);
            return ob_get_clean();
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }

    public function pipe(array|string $template, callable $callback, array $vars = [], int $chunk = 1_000_000): array
    {
        $scope = $vars ?: $this->_vars;
        $this->_vars = $scope;
        ob_start($callback, $chunk, PHP_OUTPUT_HANDLER_STDFLAGS);
        try {
            $result = $this->getTemplate($template)->display($scope);
            ob_end_flush();
            return $result ?? [];
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }
}