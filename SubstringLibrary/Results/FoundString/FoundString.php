<?php


namespace SubstringLibrary\Results\FoundString;


/**
 * Class FoundString
 *
 * Searching in file result
 *
 * @package SubstringLibrary\Results\FoundString
 */
class FoundString implements FoundStringInterface
{
    /**
     * @var int Number of a line where substring has been found
     */
    public int $stringNumber;

    /**
     * @var int Position of a substring in file
     */
    public int $stringPosition;

    /**
     * FoundString constructor.
     * @param int $stringNumber Number of a line
     * @param int $stringPosition Position of a substring
     */
    public function __construct(int $stringNumber, int $stringPosition)
    {
        $this->stringNumber = $stringNumber;
        $this->stringPosition = $stringPosition;
    }
}
