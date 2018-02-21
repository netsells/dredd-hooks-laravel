<?php

namespace Netsells\Dredd;

trait ResolvesArgumentTrait
{
    protected function resolveArgument($argument)
    {
        if (is_array($argument)) {
            $method = $argument[1];
            $argument = $argument[0];
        }

        if ($argument instanceof \Closure) {
            return function (&$transaction) use ($argument) {
                $argument($transaction);
            };
        }

        if (is_object($argument)) {
            $methodName = $method ?? 'handle';

            return function (&$transaction) use ($methodName, $argument) {
                $argument->{$methodName}($transaction);
            };
        }

        if (is_string($argument)) {
            $parts = explode('@', $argument);
            $class = $parts[0];
            $method = $parts[1] ?? 'handle';

            if (class_exists($class)) {
                return function (&$transaction) use ($class, $method) {
                    app($class)->$method($transaction);
                };
            } else {
                return function (&$transaction) use ($class) {
                    $this->$class($transaction);
                };
            }
        }

        throw new \InvalidArgumentException("Cannot run hook");
    }
}