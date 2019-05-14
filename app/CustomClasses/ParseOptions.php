<?php

namespace App\CustomClasses;

use Illuminate\Http\Request;

class ParseOptions
{
    private $filters;
    private $orderedBy;
    private $paginate;
    private $from;
    private $options = [];

    /**
     * Create a new ParseOptions instance
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $filtersStr = $request->filter ? $request->filter : "";
        $this->filters = $this->parseFilters($filtersStr);
        $this->options['filter'] = $filtersStr;

        $orderedByStr = $request->orderedBy ? $request->orderedBy : "";
        $this->orderedBy = $this->parseOrderedBy($orderedByStr);
        $this->options['ordered_by'] = $orderedByStr;

        $paginateStr = $request->paginate ? $request->paginate : "";
        $this->paginate = $this->parsePaginate($paginateStr);
        $this->options['paginate'] = $paginateStr;

        $fromStr = $request->from ? $request->from : "";
        $this->from = $this->parseFrom($fromStr);
        $this->options['from'] = $fromStr;
    }

    /**
     * Return filter options parsed from filter string
     *
     * @param  string  $filtersStr
     * @return array
     */
    private function parseFilters(string $filtersStr)
    {
        if(!empty($filtersStr)) {
            if(true) {
                $filters = explode(":", $filtersStr);
                if(count($filters) == 3) {
                    $filters[1] = $this->getOperator($filters[1]);
                    if($filters[1] == "like") {
                        $filters[2] = "%".$filters[2]."%";
                    }
                    return $filters;
                } elseif(count($filters) == 2) {
                    return $filters;
                }
                return [];
            }
        } else {
            return [];
        }
    }

    /**
     * Return ordered by options parsed from ordered by string
     *
     * @param  string  $orderedByStr
     * @return array
     */
    private function parseOrderedBy(string $orderedByStr)
    {
        if(!empty($orderedByStr)) {
            $orderBy = explode(":", $orderedByStr);
            if(count($orderBy) == 2) {
                return $orderBy;
            } elseif(count($orderBy) == 1) {
                return [$orderBy[0], 'desc'];
            }
            return ['created_at', 'desc'];
        } else {
            return ['created_at', 'desc'];
        }
    }

    /**
     * Return pagination options parsed from pagination string
     *
     * @param  string  $paginateStr
     * @return int
     */
    private function parsePaginate(string $paginateStr)
    {
        if(!empty($paginateStr)) {
            return (int) $paginateStr;
        } else {
            return 25;
        }
    }

    /**
     * Return from options parsed from from string
     *
     * @param  string  $fromStr
     * @return array
     */
    private function parseFrom(string $fromStr)
    {
        if(!empty($fromStr)) {
            $from = explode(":", $fromStr);
            if(count($from) == 4) {
              return $from;
            } else {
              return [];
            }
        } else {
            return [];
        }
    }

    /**
     * Return valid operation from operator string
     *
     * @param  string  $strOp
     * @return string
     */
    private function getOperator(string $strOp) {
        if($strOp == "eq") {
            return "=";
        } elseif($strOp == "gt") {
            return ">";
        } elseif($strOp == "lt") {
            return "<";
        } elseif($strOp == "gteq") {
            return ">=";
        } elseif($strOp == "lteq") {
            return "<=";
        } elseif($strOp == "like") {
            return "like";
        } else {
            return "=";
        }
    }

    /**
     * Return filters array (accessor function for private property)
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Return ordered by array (accessor function for private property)
     *
     * @return array
     */
    public function getOrderedBy()
    {
        return $this->orderedBy;
    }

    /**
     * Return pagination integer (accessor function for private property)
     *
     * @return int
     */
    public function getPaginate()
    {
        return $this->paginate;
    }

    /**
     * Return from string/url (accessor function for private property)
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Return options array (accessor function for private property)
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
