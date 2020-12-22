<?php


namespace SubstringLibrary\Results\ComparedHashes;


/**
 * Class ComparedHashes
 *
 * Class contains result of comparing files' hashes
 *
 * @package SubstringLibrary\Results\ComparedHashes
 */
class ComparedHashes implements ComparedHashesInterface
{
    /**
     * @var bool Result of compare
     */
    public bool $isEqual;

    /**
     * @var HashSumInterface First file hash-sum
     */
    public HashSumInterface $first;

    /**
     * @var HashSumInterface Second file hash-sum
     */
    public HashSumInterface $second;

    /**
     * ComparedHashes constructor.
     * @param bool $isEqual Result of compare
     * @param HashSumInterface $firstHashSum Hash-sum of a first file
     * @param HashSumInterface $secondHashSum Hash-sum of a second file
     */
    public function __construct(bool $isEqual, HashSumInterface $firstHashSum, HashSumInterface $secondHashSum)
    {
        $this->isEqual = $isEqual;
        $this->first = $firstHashSum;
        $this->second = $secondHashSum;
    }
}