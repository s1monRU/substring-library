<?php


namespace SubstringLibrary;


require '../vendor/autoload.php';

use Exception;
use SubstringLibrary\Results\ComparedHashes\ComparedHashes;
use SubstringLibrary\Results\ComparedHashes\ComparedHashesInterface;
use SubstringLibrary\Results\ComparedHashes\HashSum;
use SubstringLibrary\Results\FoundString\FoundString;
use SubstringLibrary\Results\FoundString\FoundStringInterface;

/**
 * Class SubstringLibrary
 *
 * A library that can search for a substring in a file or compare local or remote files' hash-sums
 *
 * Example usage:
 * $library = new SubstringLibrary('lorem.txt');
 * $library->findString('feugiat'); // find a line and position of provided string in file
 * $library->compareHashSums('lorem-different.txt'); // check if hash-sum of provided file is the same as the first one
 *
 * @package SubstringLibrary
 * @author Ivan Semenov <developer.ivan.semenov@gmail.com>
 */
class SubstringLibrary
{
    /**
     * A config containing restrictions for a file to search in
     */
    const FILE_RESTRICTIONS_CONFIG = 'config/file-restrictions.json';

    /**
     * @var string Local or remote file to search in
     * @see SubstringLibrary::splitFileByStrings()
     */
    private string $filename;

    /**
     * @var array Strings gotten from a file
     */
    private array $fileStrings;

    /**
     * SubstringLibrary constructor.
     *
     * @param string $filename File to search in
     * @throws Exception
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;

        $this->validateFile($filename);
        $this->fileStrings = $this->splitFileByStrings($filename);

        return $this;
    }

    /**
     * Find provided substring in a file
     *
     * @param string $substring Substring to search
     * @return FoundStringInterface Line and a position of the substring in a file
     * @throws Exception
     */
    public function findString(string $substring): FoundStringInterface
    {
        foreach ($this->fileStrings as $line => $string) {
            $strpos = strpos($string, $substring);

            if (is_int($strpos)) {
                return new FoundString($line+1, $strpos+1);
            }
        }

        throw new Exception('No matches. Try a different string!');
    }

    /**
     * Check if hash-sum of provided file is the same with a file in construct
     *
     * @param string $secondFilename A file to compare
     * @param string $algo Hashing algorithm
     * @return ComparedHashesInterface Object contains result of comparing and files' hashes
     * @throws Exception
     */
    public function compareHashSums(string $secondFilename, string $algo = 'sha256'): ComparedHashesInterface
    {
        $this->validateFile($secondFilename);

        $firstHash = new HashSum($this->filename, hash_file($algo, $this->filename));
        $secondHash = new HashSum($secondFilename, hash_file($algo, $secondFilename));
        $equal = $firstHash->hash === $secondHash->hash;

        return new ComparedHashes($equal, $firstHash, $secondHash);
    }

    /**
     * Open a file and split it into strings
     *
     * @param string $filename A filename on a local system or FTP-connection string
     *
     * Example usage:
     * $this->splitFileByStrings('lorem.txt');
     * $this->splitFileByStrings('ftp://username:password@8.8.8.8/var/www/html/SubstringLibrary/lorem.txt')
     *
     * @return array
     */
    private function splitFileByStrings(string $filename): array
    {
        $regexp = "/.+/";
        $matches = [];
        $resource = fopen($filename, 'r');
        $file = fread($resource, filesize($filename));
        preg_match_all($regexp, $file, $matches);

        return $matches[0];
    }

    /**
     * Validate file
     *
     * @param string $filename A file to validate
     * @return bool Validation result
     * @throws Exception
     */
    private function validateFile(string $filename): bool
    {
        if (!file_exists($filename)) {
            throw new Exception('No file provided!');
        }

        $filesize = filesize($filename);
        $mimetype = mime_content_type($filename);

        $configJson = file_get_contents(self::FILE_RESTRICTIONS_CONFIG);
        $config = json_decode($configJson, false);

        if ($filesize > $config->maxSize) {
            throw new Exception("Maximum filesize of $config->maxSize bytes exceeded");
        }

        if (!in_array($mimetype, $config->mimeTypes)) {
            throw new Exception('Unsupported file type');
        }

        return true;
    }

}
