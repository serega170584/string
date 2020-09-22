<?php

class LzwNode
{
    /**
     * @var string
     */
    private $phrase;
    /**
     * @var LzwNode
     */
    private $left;
    /**
     * @var string[]
     */
    private $codes = [
        'a' => '41',
        'b' => '42',
        'c' => '43',
        'd' => '44',
        'r' => '52'
    ];
    /**
     * @var string[]
     */
    private $codeDigits = [
        '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'
    ];
    /**
     * @var string
     */
    private $code = '';
    private $prefixCodes = ['8'];
    private $compressedCodes = [];

    /**
     * @return string
     */
    public function getCode(): string
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
     * @return string[]
     */
    public function getCodes(): array
    {
        return $this->codes;
    }

    /**
     * @param string[] $codes
     */
    public function setCodes(array $codes): void
    {
        $this->codes = $codes;
    }

    /**
     * @param LzwNode $left
     */
    public function setLeft(LzwNode $left): void
    {
        $this->left = $left;
    }

    /**
     * @param LzwNode $right
     */
    public function setRight(LzwNode $right): void
    {
        $this->right = $right;
    }

    /**
     * @return LzwNode
     */
    public function getLeft(): ?LzwNode
    {
        return $this->left;
    }

    /**
     * @return LzwNode
     */
    public function getRight(): ?LzwNode
    {
        return $this->right;
    }

    /**
     * @var LzwNode
     */
    private $right;
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
     * @param string $char
     */
    public function setChar(string $char): void
    {
        $this->char = $char;
    }

    /**
     * @var LzwNode
     */
    private $middle;

    /**
     * @return LzwNode
     */
    public function getMiddle(): ?LzwNode
    {
        return $this->middle;
    }

    public function __construct($phrase = '')
    {
        $this->phrase = $phrase;
    }

    public function buildTree()
    {
        $phrase = $this->phrase;
        $length = strlen($this->phrase);
        $node = $this;
        $code = '';
        for ($i = 0; $i < $length; ++$i) {
            $searchNode = $node->middle;
            $nextNode = false;
            $prevNode = $node;
            while (!$nextNode) {
                if ($searchNode) {
                    $prevNode = $searchNode;
                    if ($searchNode->getChar() == $phrase[$i]) {
                        $nextNode = $searchNode;
                    } elseif ($searchNode->getChar() < $phrase[$i]) {
                        $searchNode = $searchNode->getRight();
                    } else {
                        $searchNode = $searchNode->getLeft();
                    }
                } else {
                    if ($code) {
                        $this->compressedCodes[] = $code;
                    }
                    $nextNode = new LzwNode();
                    $nextNode->setChar($phrase[$i]);
                    $this->executeCoding($node, $nextNode, $phrase[$i]);
                    if ($prevNode === $node) {
                        $node->middle = $nextNode;
                    } elseif ($prevNode->getChar() < $phrase[$i]) {
                        $prevNode->setRight($nextNode);
                    } else {
                        $prevNode->setLeft($nextNode);
                    }
                    $nextCharNode = $this->searchNextCharNode($phrase[$i]);
                    if ($nextCharNode->getChar() == $phrase[$i]) {
                        $nextNode = $nextCharNode;
                    } else {
                        $nextNode = new LzwNode();
                        $nextNode->setChar($phrase[$i]);
                        $this->executeCoding($this, $nextNode, $phrase[$i]);
                        if ($nextCharNode->getChar() < $phrase[$i]) {
                            $nextCharNode->setRight($nextNode);
                        } else {
                            $nextCharNode->setLeft($nextNode);
                        }
                    }
                }
            }
            $node = $nextNode;
            $code = $nextNode->getCode();
        }
        $this->compressedCodes[] = $code;
    }

    /**
     * @param LzwNode $node
     * @param LzwNode $nextNode
     * @param $char
     */
    private function executeCoding($node, $nextNode, $char)
    {
        $code = $node->getCode();
        if ($code) {
            $codeDigit = current($this->codeDigits);
            if (!$codeDigit) {
                next($this->prefixCodes);
                reset($this->codeDigits);
            }
            $prefixCode = current($this->prefixCodes);
            $code = $prefixCode . $codeDigit;
            $this->prefixCodes[] = $code;
            $nextNode->setCode($code);
            next($this->codeDigits);
        } else {
            $code = $this->codes[$char];
            $nextNode->setCode($code);
        }
    }

    /**
     * @return array
     */
    public function getCompressedCodes(): array
    {
        return $this->compressedCodes;
    }

    /**
     * @param $char
     * @return LzwNode
     */
    private function searchNextCharNode($char)
    {
        $node = $this->middle;
        $prevNode = false;
        while ($node) {
            $prevNode = $node;
            if ($node->getChar() == $char) {
                break;
            } elseif ($node->getChar() < $char) {
                $node = $node->getRight();
            } else {
                $node = $node->getLeft();
            }
        }
        return $node ?: $prevNode;
    }
}

$node = new LzwNode('abacadabacad');
$node->buildTree();
var_dump($node->getCompressedCodes());