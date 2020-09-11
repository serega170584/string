<?php


class TSTNode
{
    /**
     * @var TSTNode
     */
    private $root;

    /**
     * @return TSTNode
     */
    public function getRoot(): TSTNode
    {
        return $this->root;
    }

    /**
     * @param TSTNode $root
     */
    public function setRoot(TSTNode $root): void
    {
        $this->root = $root;
    }

    private $char;
    /**
     * @var TSTNode
     */
    private $middle;
    /**
     * @var TSTNode
     */
    private $left;
    /**
     * @var TSTNode
     */
    private $right;
    /**
     * @var TSTNode
     */
    private $prev;

    /**
     * @return TSTNode
     */
    public function getPrev(): ?TSTNode
    {
        return $this->prev;
    }

    /**
     * @param TSTNode $prev
     */
    public function setPrev(?TSTNode $prev): void
    {
        $this->prev = $prev;
    }

    public function __construct()
    {

    }

    public function buildTree($str)
    {
        $parts = explode(' ', $str);
        foreach ($parts as $part) {
            $length = strlen($part);
            if (!$this->root) {
                $this->root = new TSTNode();
                $this->root->setChar($part[0]);
            }
            /**
             * @var TSTNode $node
             */
            $node = $this->root;
            for ($i = 0; $i < $length - 1; ++$i) {
                $node = $this->generateNode($node, $part[$i]);
                $nextNode = $node->getMiddle();
                if (!$nextNode) {
                    $nextNode = new TSTNode();
                    $nextNode->setChar($part[$i + 1]);
                    $nextNode->setPrev($node);
                    $node->setMiddle($nextNode);
                }
                $node = $nextNode;
            }
        }
    }

    /**
     * @param TSTNode $node
     * @param $char
     * @return TSTNode
     */
    private function generateNode($node, $char)
    {
        $prevNode = false;
        $isLeft = false;
        $isRight = false;
        while ($node) {
            $prevNode = $node;
            if ($node->getChar() == $char) {
                break;
            } elseif ($node->getChar() > $char) {
                $node = $node->getLeft();
                $isLeft = true;
            } elseif ($node->getChar() < $char) {
                $node = $node->getRight();
                $isRight = true;
            }
        }
        if (!$node) {
            $node = new TSTNode();
            $node->setChar($char);
            $node->setPrev($prevNode);
            if ($isLeft) {
                $prevNode->setLeft($node);
            }
            if ($isRight) {
                $prevNode->setRight($node);
            }
        }
        return $node;
    }

    /**
     * @param $node
     * @param $char
     * @return TSTNode
     */
    private function searchNode($node, $char)
    {
        while ($node) {
            if ($node->getChar() == $char) {
                break;
            } elseif ($node->getChar() > $char) {
                $node = $node->getLeft();
            } elseif ($node->getChar() < $char) {
                $node = $node->getRight();
            }
        }
        return $node;
    }

    private function getChar()
    {
        return $this->char;
    }

    /**
     * @param mixed $char
     */
    public function setChar($char): void
    {
        $this->char = $char;
    }

    public function getMiddle()
    {
        return $this->middle;
    }

    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param TSTNode $middle
     */
    public function setMiddle(TSTNode $middle): void
    {
        $this->middle = $middle;
    }

    /**
     * @param TSTNode $left
     */
    public function setLeft(TSTNode $left): void
    {
        $this->left = $left;
    }

    /**
     * @param TSTNode $right
     */
    public function setRight(TSTNode $right): void
    {
        $this->right = $right;
    }

    private function getRight()
    {
        return $this->right;
    }

    public function isWordExisted($word)
    {
        $length = strlen($word);
        $node = $this->root;
        $isExisted = true;
        for ($i = 0; $i < $length; ++$i) {
            $node = $this->searchNode($node, $word[$i]);
            if (!$node) {
                $isExisted = false;
                break;
            }
            $node = $node->getMiddle();
        }
        return $isExisted;
    }

    public function getLongestWords()
    {
        $nodes = [$this->root];
        $prevNodes = [];
        while ($nodes) {
            $nextNodes = [];
            while ($node = current($nodes)) {
                if ($node->getLeft()) {
                    $nodes[] = $node->getLeft();
                }
                if ($node->getRight()) {
                    $nodes[] = $node->getRight();
                }
                if ($node->getMiddle()) {
                    $nextNodes[] = $node->getMiddle();
                }
                next($nodes);
            }
            $prevNodes = $nodes;
            $nodes = $nextNodes;
        }
        $words = [];
        foreach ($prevNodes as $node) {
            $treeNode = $node;
            $word = '';
            while ($treeNode) {
                $word = $treeNode->getChar() . $word;
                $treeNode = $this->getPrevCharNode($treeNode);
            }
            $words[] = $word;
        }
        return $words;
    }

    /**
     * @param TSTNode $node
     */
    public function getPrevCharNode($node)
    {
        $prev = $node->getPrev();
        while ($prev && $prev->getMiddle() !== $node) {
            $node = $prev;
            $prev = $node->getPrev();
        }
        return $prev;
    }
}

$tree = new TSTNode();
$tree->buildTree('shells ells shores');
//$tree->getRoot()->getLeft()->setPrev(null);
var_dump($tree->getLongestWords());