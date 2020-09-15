<?php

class Node
{
    /**
     * @var string
     */
    private $char;

    /**
     * @return string
     */
    public function getChar(): ?string
    {
        return $this->char;
    }

    /**
     * @var int
     */
    private $freq;
    /**
     * @var Node|null
     */
    private $left;

    /**
     * @return Node|null
     */
    public function getLeft(): ?Node
    {
        return $this->left;
    }

    /**
     * @return Node|null
     */
    public function getRight(): ?Node
    {
        return $this->right;
    }

    /**
     * @var Node|null
     */
    private $right;
    /**
     * @var string
     */
    private $code;
    /**
     * @var array
     */
    private $codes;

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @var array
     */
    private $priorities;
    /**
     * @var Node
     */
    private $tree;

    public function __construct($freq = 0, $char = null, $left = null, $right = null)
    {
        $this->char = $char;
        $this->freq = $freq;
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @return int
     */
    public function getFreq(): int
    {
        return $this->freq;
    }

    public function buildForest($phrase)
    {
        $length = strlen($phrase);
        $counts = [];
        for ($i = 0; $i < $length; ++$i) {
            $counts[$phrase[$i]] = $counts[$phrase[$i]] ?? 0;
            ++$counts[$phrase[$i]];
        }
        asort($counts);
        $priorities = [];
        foreach ($counts as $char => $count) {
            $priorities[$count][] = new Node($count, $char);
        }
        ksort($priorities);
        $this->priorities = $priorities;
    }

    /**
     * @return array
     */
    public function getPriorities(): array
    {
        return $this->priorities;
    }

    /**
     * @param array $priorities
     */
    public function setPriorities(array $priorities): void
    {
        $this->priorities = $priorities;
    }

    public function buildTree()
    {
        $leftPriorityChar = $this->getMinPriorityChar();
        $rightPriorityChar = $this->getMinPriorityChar();
        $node = $leftPriorityChar;
        while ($rightPriorityChar) {
            $priorities = $this->getPriorities();
            $freq = $leftPriorityChar->getFreq() + $rightPriorityChar->getFreq();
            $node = new Node($freq, null, $leftPriorityChar, $rightPriorityChar);
            $priorities[$freq][] = $node;
            ksort($priorities);
            $this->setPriorities($priorities);
            $leftPriorityChar = $this->getMinPriorityChar();
            $rightPriorityChar = $this->getMinPriorityChar();
        }
        $this->tree = $node;
        return $this->tree;
    }

    public function buildCharCodes()
    {
        $nodes = [$this->tree];
        $codes = [];
        /**
         * @var Node[] $nodes
         */
        while ($nodes) {
            $node = array_shift($nodes);
            $code = $node->getCode();
            if ($char = $node->getChar()) {
                $codes[$char] = $node->getCode();
            } else {
                $leftNode = $node->getLeft();
                $leftNode->setCode($code . '0');
                $nodes[] = $leftNode;
                $rightNode = $node->getRight();
                $rightNode->setCode($code . '1');
                $nodes[] = $rightNode;
            }
        }
        $this->codes = $codes;
        return $this->codes;
    }

    /**
     * @return Node|null
     */
    public function getMinPriorityChar()
    {
        $priorities = $this->getPriorities();
        $priorityChars = current($priorities);
        $priorityChar = false;
        if ($priorityChars) {
            $priorityIndex = key($priorities);
            $priorityChar = array_shift($priorityChars);
            if ($priorityChars) {
                $priorities[$priorityIndex] = $priorityChars;
            } else {
                unset($priorities[$priorityIndex]);
            }
        }
        $this->priorities = $priorities;
        return $priorityChar;
    }
}

$node = new Node();
$node->buildForest('abcdefghasasasasasas');
$node->buildTree();
var_dump($node->buildCharCodes());
die('asd');

