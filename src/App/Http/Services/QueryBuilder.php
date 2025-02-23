<?php

class GraphQLQueryBuilder
{
    private string $operationType;
    private string $operationName;
    private string $rootField;
    private array $arguments = [];
    private array $fields = [];

    public function __construct(string $operationType, string $operationName = '')
    {
        $this->operationType = $operationType; // 'query', 'mutation', 'subscription'
        $this->operationName = $operationName;
    }

    public function setRootField(string $field): self
    {
        $this->rootField = $field;
        return $this;
    }

    public function addArgument(string $name, $value): self
    {
        $this->arguments[$name] = $value;
        return $this;
    }

    public function addFields(array $fields): self
    {
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }

    private function formatArguments(): string
    {
        if (empty($this->arguments)) return '';

        $args = array_map(function ($key, $value) {
            $formattedValue = is_string($value) ? '"' . $value . '"' : $value;
            return "$key: $formattedValue";
        }, array_keys($this->arguments), $this->arguments);

        return '(' . implode(', ', $args) . ')';
    }

    private function formatFields(array $fields): string
    {
        return implode("\n", array_map(function ($field) {
            if (is_array($field)) {
                $key = key($field);
                return "$key {" . $this->formatFields($field[$key]) . "}";
            }
            return $field;
        }, $fields));
    }

    public function build(): string
    {
        $args = $this->formatArguments();
        $fields = $this->formatFields($this->fields);

        $query = "{$this->operationType} {$this->operationName} {";
        $query .= "{$this->rootField}{$args} {\n$fields\n}";
        $query .= "}";

        return $query;
    }
}

// Example Usage:

// Create a query
$queryBuilder = new GraphQLQueryBuilder('query', 'GetUser');
$query = $queryBuilder
    ->setRootField('user')
    ->addArgument('id', 123)
    ->addFields(['id', 'name', 'email', 'posts' => ['title', 'content']])
    ->build();

echo $query;

// Create a mutation
$mutationBuilder = new GraphQLQueryBuilder('mutation', 'CreateUser');
$mutation = $mutationBuilder
    ->setRootField('createUser')
    ->addArgument('input', [
        'name' => 'Dinesh',
        'email' => 'dinesh@example.com'
    ])
    ->addFields(['id', 'name', 'email'])
    ->build();

echo $mutation;
