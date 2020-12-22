<?php


namespace SubstringLibrary\Results\ComparedHashes;


/**
 * Class HashSum
 *
 * Class contains result of calculating file's hash
 *
 * @package SubstringLibrary\Results\ComparedHashes
 */
class HashSum implements HashSumInterface
{
    /**
     * @var string A name of file
     */
    public string $fileName;

    /**
     * @var string Hash-sum of file
     */
    public string $hash;

    /**
     * HashSum constructor.
     * @param string $fileName A name of file
     * @param string $hash File hash-sum
     */
    public function __construct(string $fileName, string $hash)
    {
        $this->fileName = $fileName;
        $this->hash = $hash;
    }
}