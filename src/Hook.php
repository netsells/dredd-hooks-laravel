<?php

namespace Netsells\Dredd;

use Dredd\Hooks;

class Hook
{
    use ResolvesArgumentTrait;

    public $group;
    public $operation;
    public $path;
    public $status = 200;
    public $response = 'application/json';

    public function __construct($group = null, $path = null, $operation = null, $status = null, $response = null)
    {
        if ($group) {
            $this->group = $group;
        }

        if ($operation) {
            $this->operation = $operation;
        }

        if ($path) {
            $this->path = $path;
        }

        if ($status) {
            $this->status = $status;
        }

        if ($response) {
            $this->response = $response;
        }
    }

    public function __call($method, $args)
    {
        if (in_array($method, ['group', 'path', 'operation', 'status', 'response'])) {
            $hook = $this->reCreateSelf();

            $hook->{$method} = $args[0];

            if (isset($args[1])) {
                if ($callable = $hook->resolveArgument($args[1])) {
                    return $callable($hook);
                }
            }

            return $hook;
        }

        if (in_array($method, ['before'])) {
            $callable = $this->resolveArgument($args[0]);

            Hooks::$method($this->buildTransactionName(), function (&$transaction) use ($callable) {
                $transactionObject = new Transaction($transaction);
                $callable($transactionObject);
                $transaction = $transactionObject->getTransaction();
            });
        }
    }

    private function reCreateSelf()
    {
        return new static($this->group, $this->path, $this->operation, $this->status, $this->response);
    }

    private function buildTransactionName()
    {
        return implode(' > ', [
            $this->group,
            $this->path,
            $this->operation,
            $this->status,
            $this->response,
        ]);
    }
}