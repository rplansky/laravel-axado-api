<?php
namespace Axado;

class Response
{
    /**
     * If the request is ok.
     *
     * @var boolean
     */
    protected $isOk;

    /**
     * Error_id sended by Axado.
     *
     * @var boolean
     */
    protected $errorId;

    /**
     * The error message sended by Axado.
     *
     * @var boolean
     */
    protected $errorMessage;

    /**
     * Array of Axado\Quotation.
     *
     * @var array
     */
    protected $quotations = [];

    /**
     * The token sended by Axado.
     *
     * @var string
     */
    protected $quotation_token;

    /**
     * Getter for quotations.
     *
     * @return array
     */
    public function quotations()
    {
        return $this->quotations;
    }

    /**
     * Returns if the response was Ok.
     *
     * @return boolean
     */
    public function isOk()
    {
        return (boolean)$this->isOk;
    }

    /**
     * Parse the raw response to this object.
     *
     * @param  array $raw
     * @return null
     */
    public function parse($raw = null)
    {
        $arrayResponse = (array)$raw;

        if (! $this->isError($arrayResponse)) {
            $this->parseQuotations($arrayResponse);
            $this->isOk = true;
        } else {
            $this->isOk = false;
        }
    }

    /**
     * Parse the response into Quotation objects.
     *
     * @param  array $arrayResponse
     * @return null
     */
    protected function parseQuotations(array $arrayResponse)
    {
        $quotationsArray = [];

        if (isset($arrayResponse['cotacoes'])) {
            $quotationsArray = $arrayResponse['cotacoes'];
        }

        if (isset($arrayResponse["consulta_token"])) {
            $this->quotation_token = $arrayResponse["consulta_token"];
        }

        foreach ($quotationsArray as $quotationArray) {
            $quotation = new Quotation;
            $quotation->fill($quotationArray);
            $this->quotations[] = $quotation;
        }
    }

    /**
     * Verify if this Response has a error.
     *
     * @param  array  $arrayResponse
     * @return boolean
     */
    protected function isError($arrayResponse)
    {
        if (isset($arrayResponse['erro_id'])) {
            $this->errorId      = $arrayResponse['erro_id'];
            $this->errorMessage = $arrayResponse['erro_msg'];

            return true;
        }

        if (! $arrayResponse) {
            return true;
        }

        return false;
    }

    /**
     * Returns the quotation token.
     *
     * @return string
     */
    public function getQuotationToken()
    {
        return $this->quotation_token;
    }
}
