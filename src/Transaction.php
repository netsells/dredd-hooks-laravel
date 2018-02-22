<?php

namespace Netsells\Dredd;

class Transaction
{
    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function removeQueryStringFromUrl()
    {
        $pathParts = explode('?', $this->transaction->fullPath);
        $this->transaction->fullPath = $pathParts[0];
    }

    public function replaceInPath($old, $new)
    {
        $this->transaction->request->uri = str_replace($old, $new, $this->transaction->request->uri);
        $this->transaction->fullPath = str_replace($old, $new, $this->transaction->fullPath);
    }

    public function alterBody(\Closure $closure)
    {
        $requestBody = json_decode($this->transaction->request->body);
        $closure($requestBody);
        $this->transaction->request->body = json_encode($requestBody);
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
