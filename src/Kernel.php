<?php

namespace Netsells\Dredd;

use Dredd\Hooks;

abstract class Kernel
{
    use ResolvesArgumentTrait;

    abstract public function handle(Hook $hook);

    public function run()
    {
        $this->handle(new Hook());
    }

    public function __call($method, $args)
    {
        if (in_array($method, ['beforeAll', 'beforeEach', 'beforeEachValidation', 'afterEach', 'afterAll'])) {
            $callable = $this->resolveArgument($args[0]);

            Hooks::$method(function (&$transaction) use ($callable) {
                $transactionObject = new Transaction($transaction);
                $callable($transactionObject);
                $transaction = $transactionObject->getTransaction();
            });
        }

        if (in_array($method, ['before', 'beforeValidation', 'after'])) {
            $callable = $this->resolveArgument($args[1]);

            Hooks::$method($args[0], function (&$transaction) use ($callable) {
                $transactionObject = new Transaction($transaction);
                $callable($transactionObject);
                $transaction = $transactionObject->getTransaction();
            });
        }
    }
}
