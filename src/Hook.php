<?php

namespace Netsells\Dredd;

use Dredd\Hooks;

class Hook
{
    use ResolvesArgumentTrait;

    protected $group;
    protected $operation;
    protected $path;
    protected $status = 200;
    protected $response = 'application/json';

    public function __call($method, $args)
    {
        if (in_array($method, ['group', 'path', 'operation', 'status', 'response'])) {
            $this->{$method} = $args[0];

            if (isset($args[1])) {
                if ($callable = $this->resolveArgument($args[1])) {
                    return $callable($this);
                }
            }

            return $this;
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