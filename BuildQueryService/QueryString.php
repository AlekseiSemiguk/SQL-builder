<?php

namespace FpDbTest\BuildQueryService;

use FpDbTest\BuildQueryService\Exceptions\NumberArgsException;
use FpDbTest\BuildQueryService\Param\ParamFactory;
use mysqli;

class QueryString
{
    public string $queryString;
    public string $resultString;
    public array $conditionalBlocks;
    public array $params;
    public array $paramsPdo;
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli, $queryString)
    {
        $this->mysqli = $mysqli;
        $this->queryString = $queryString;
        $this->resultString = $queryString;
        $this->paramsPdo = [];
        $this->setConditionalBlocks();
        $this->setParams();
    }

    private function setConditionalBlocks(): void
    {
        $conditionalBlocks = [];
        if (preg_match_all('/{[^}]+}/', $this->queryString, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $match) {
                $conditionalBlocks[] = new ConditionalBlock($match[0], $match[1]);
            }
        }
        $this->conditionalBlocks = $conditionalBlocks;
    }

    private function setParams(): void
    {
        $params = [];
        if (preg_match_all('/\?[d|f|a|#]?/', $this->queryString, $matches, PREG_OFFSET_CAPTURE)) {
            $paramFactory = new ParamFactory($this->mysqli);
            foreach ($matches[0] as $match) {
                $param = $paramFactory->getParam($match[0]);
                $param->setFirstSymbolPosition($match[1]);
                $params[] = $param;
            }
        }
        $this->params = $params;
    }

    public function bindArguments($args): void
    {
        if (count($args) !== count($this->params)) {
            throw new NumberArgsException();
        }

        foreach ($this->params as $key => $param) {
            $param->bindArg($args[$key]);
        }
    }

    public function getResultString(): string
    {
        return $this->resultString;
    }

    public function cleanConditionalBlocks(): void
    {
        foreach (array_reverse($this->conditionalBlocks) as $blockKey => $block) {
            $doCutBlock = false;
            $paramsInBlock = [];
            foreach ($this->params as $key => $param) {
                if ($param->firstSymbolPosition > $block->firstSymbolPosition && $param->firstSymbolPosition < $block->lastSymbolPosition) {
                    $paramsInBlock[] = $key;
                    if ($param->queryArg instanceof SkipArg) {
                        $doCutBlock = true;
                    }
                }
            }
            if ($doCutBlock == true) {
                foreach ($paramsInBlock as $value) {
                    unset ($this->params[$value]);
                }
                $this->resultString = $this->cutString($this->resultString, $block->firstSymbolPosition, $block->lastSymbolPosition);
            } else {
                $this->resultString = $this->cutString($this->resultString, $block->lastSymbolPosition);
                $this->resultString = $this->cutString($this->resultString, $block->firstSymbolPosition);
            }
            unset ($this->conditionalBlocks[$blockKey]);
        }
    }

    public function replaceParamsWithArgs(): void
    {
        if (defined('USE_PDO') && USE_PDO === 1) {
            foreach (array_reverse($this->params) as $param) {
                $this->resultString = substr_replace($this->resultString, $param->queryArg->string, $param->firstSymbolPosition, strlen($param->string));
                $this->paramsPdo = array_merge($param->queryArg->params, $this->paramsPdo);
            }
        } else {
            foreach (array_reverse($this->params) as $param) {
                $this->resultString = substr_replace($this->resultString, $param->queryArg, $param->firstSymbolPosition, strlen($param->string));
            }
        }
    }

    private function cutString ($string, $startPosition, $endPosition = null): string
    {
        if ($endPosition === null) {
            $endPosition = $startPosition;
        }
        foreach ($this->params as $key => $param) {
            if ($param->firstSymbolPosition > $endPosition) {
                $this->params[$key]->firstSymbolPosition = $this->params[$key]->firstSymbolPosition - ($endPosition - $startPosition + 1);
            }
        }
        return substr($string, 0, $startPosition) . substr($string, $endPosition + 1);
    }
}
