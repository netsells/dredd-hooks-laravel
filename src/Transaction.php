<?php

namespace Netsells\Dredd;

class Transaction
{
    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function replaceInPath($old, $new)
    {
        $this->transaction->request->uri = str_replace($old, $new, $this->transaction->request->uri);
        $this->transaction->fullPath = str_replace($old, $new, $this->transaction->fullPath);
    }

    /**
     * Sets a HTTP header
     * @param $header
     * @param $value
     */
    public function setHeader($header, $value)
    {
        $this->transaction->request->headers->{$header} = $value;
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     * @return Transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }
}
