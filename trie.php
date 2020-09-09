<?php

class Node
{
    private $char;

    /**
     * @return mixed
     */
    public function getChar()
    {
        return $this->char;
    }

    /**
     * @var Node[]
     */
    private $nextNode = [];
    /**
     * @var Node|null
     */
    private $prev;

    /**
     * @return Node
     */
    public function getPrev(): ?Node
    {
        return $this->prev;
    }

    /**
     * @param Node $prev
     */
    public function setPrev(Node $prev): void
    {
        $this->prev = $prev;
    }

    public function __construct($char)
    {
        $this->char = $char;
    }

    public function fillTree($str)
    {
        $parts = explode(' ', $str);
        foreach ($parts as $word) {
            $length = strlen($word);
            $parent = $this;
            for ($i = 0; $i < $length; ++$i) {
                $char = $word[$i];
                $charCode = ord($char);
                $node = false;
                if ($parent->next($charCode)) {
                    $node = $parent->next($charCode);
                } else {
                    $node = new Node($char);
                    $parent->addNext($node);
                    $node->setPrev($parent);
                }
                $parent = $node;
            }
        }
    }

    private function next(int $charCode)
    {
        return $this->nextNode[$charCode] ?? false;
    }

    private function addNext(Node $node)
    {
        $this->nextNode[ord($node->getChar())] = $node;
    }

    public function isWordExist($word)
    {
        $length = strlen($word);
        $list = [];
        for ($i = 0; $i < $length; ++$i) {
            $list[] = $word[$i];
        }
        $word = $list;
        $node = $this->next(ord($word[0]));
        $isExist = false;
        for ($i = 0; $i < $length - 1; ++$i) {
            $node = $node->next(ord($word[$i + 1])) ?? false;
            $isExist = (bool)$node;
            if (!$isExist) {
                break;
            }
        }
        return $isExist;
    }

    public function getLargestWord()
    {
        $nodes = $this->nextNode;
        $lastNodes = false;
        $str = '';
        while ($nodes) {
            $lastNodes = $nodes;
            $nextNodes = [];
            foreach ($nodes as $node) {
                $nextNodes = array_merge($nextNodes, $node->getNextNode());
            }
            $nodes = $nextNodes;
        }
        foreach ($lastNodes as $node) {
            $currentNode = $node;
            $str = $currentNode->getChar() . $str;
            while ($currentNode = $currentNode->getPrev()) {
                $str = $currentNode->getChar() . $str;
            }
            $str .= '';
        }
        return $str;
    }

    /**
     * @return array
     */
    public function getNextNode(): array
    {
        return $this->nextNode;
    }
}

$root = new Node('');
$root->fillTree('she sells sea shells by the sea shore');
var_dump($root->getLargestWord());
die('asd');