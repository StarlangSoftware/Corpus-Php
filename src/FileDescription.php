<?php

namespace olcaytaner\Corpus;

class FileDescription
{
    private string $path;
    private string $extension;
    private int $index;

    /**
     * Constructor for the FileDescription object. FileDescription object is used to store sentence or tree file names
     * in a format of path/index.extension such as 'trees/0123.train' or 'sentences/0002.test'. At most 10000 file names
     * can be stored for an extension.
     * @param string $path Path of the file
     * @param string $fileName Raw file name of the string without path name, including the index of the file and the
     *                    extension. For example 0023.train, 3456.test, 0125.dev, 0000.train etc.
     */
    public function constructor1(string $path, string $fileName): void
    {
        $this->path = $path;
        $this->extension = substr($fileName, strrpos($fileName, '.') + 1);
        $this->index = substr($fileName, 0, strrpos($fileName, '.'));
    }

    /**
     * Another constructor for the FileDescription object. FileDescription object is used to store sentence or tree
     * file names in a format of path/index.extension such as 'trees/0123.train' or 'sentences/0002.test'. At most 10000
     * file names can be stored for an extension.
     * @param string $path Path of the file
     * @param string $extension Extension of the file such as train, test, dev etc.
     * @param int $index Index of the file, should be larger than or equal to 0 and less than 10000. 123, 0, 9999, etc.
     */
    public function constructor2(string $path, string $extension, int $index): void
    {
        $this->path = $path;
        $this->extension = $extension;
        $this->index = $index;
    }

    public function __construct(string $path, string $extension, ?int $index = null)
    {
        if ($index === null) {
            $this->constructor1($path, $extension);
        } else {
            $this->constructor2($path, $extension, $index);
        }
    }

    /**
     * Accessor for the path attribute.
     * @return string Path
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Accessor for the extension attribute.
     * @return string Extension
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Accessor for the index attribute.
     * @return int Index
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * Converts the file number to a 4 character string by embedding leading zeros.
     * @param int $index File number
     * @return string File number as a string with leading zeros
     */
    private function stringFormatted(int $index): string
    {
        if ($index < 10) {
            return str_repeat('0', 3) . $index;
        } else {
            if ($index < 100) {
                return str_repeat('0', 2) . $index;
            } else {
                if ($index < 1000) {
                    return str_repeat('0', 1) . $index;
                } else {
                    return $index;
                }
            }
        }
    }

    /**
     * Returns the filename with path, index, and extension are replaced with the given path, index, and extension.
     * @param ?string $thisPath New path
     * @param ?int $thisIndex New Index
     * @param ?string $thisExtension New extension
     * @return string The filename with path, index, and extension are replaced with the given path, index, and extension.
     */
    public function getFileName(?string $thisPath = null,
                                ?int    $thisIndex = null,
                                ?string $thisExtension = null): string
    {
        if ($thisPath === null) {
            $thisPath = $this->path;
        }
        if ($thisIndex === null) {
            $thisIndex = $this->index;
        }
        if ($thisExtension === null) {
            $thisExtension = $this->extension;
        }
        return $thisPath . "/" . $this->stringFormatted($thisIndex) . "." . $thisExtension;
    }

    /**
     * Returns the filename with extension replaced with the given extension.
     * @param string $extension New extension
     * @return string The filename with extension replaced with the given extension.
     */
    public function getFileNameWithExtension(string $extension): string{
        return $this->getFileName($this->path, $this->index, $extension);
    }

    /**
     * Returns only the filename without path as 'index.extension'.
     * @return string File name without path as 'index.extension'.
     */
    public function getRawFileName(): string{
        return $this->stringFormatted($this->index) . "." . $this->extension;
    }

    /**
     * Increments index by count
     * @param int $count Count to be incremented
     */
    public function addToIndex(int $count): void{
        $this->index += $count;
    }

    /**
     * Checks if the next file (found by changing the path and adding count to the index) exists or not. Returns true
     * if it exists, false otherwise.
     * @param ?string $thisPath New path
     * @param int $count Count to be incremented.
     * @return bool Returns true, if the next file (found by changing the path and adding count to the index) exists,
     * false otherwise.
     */
    public function nextFileExists(int $count, ?string $thisPath = null): bool{
        if ($thisPath === null) {
            $thisPath = $this->path;
        }
        return file_exists($this->getFileName($thisPath, $this->index + $count));
    }

    /**
     * Checks if the previous file (found by changing the path and subtracting count from the index) exists or not.
     * Returns true  if it exists, false otherwise.
     * @param ?string $thisPath New path
     * @param int $count Count to be decremented.
     * @return bool Returns true, if the previous file (found by changing the path and subtracting count to the index)
     * exists, false otherwise.
     */
    public function previousFileExists(int $count, ?string $thisPath = null): bool{
        if ($thisPath === null) {
            $thisPath = $this->path;
        }
        return file_exists($this->getFileName($thisPath, $this->index - $count));
    }
}