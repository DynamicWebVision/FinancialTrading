<?php namespace App\Services;

class FileHandler  {
    public $filePath;
    public $textToAdd;
    public $lineToWriteNumber;
    public $lineToWriteSpaces;

    public $linesToAdd;

    public function setFile($file) {
        $this->fileHandler = fopen($file, 'w');
    }

    public function findLineOfTextInFile($text) {
        $handle = fopen($this->filePath, "r");
        $lineNumber = 0;
        $correctLine = false;
        while (($line = fgets($handle)) !== false) {
            if (strpos($line, $text) !== false) {
                $this->lineToWriteNumber = $lineNumber;
                $this->lineToWriteSpaces = strlen($line)-strlen(ltrim($line));
            }
            $lineNumber++;
        }
        fclose($handle);
        return $correctLine;
    }

    public function addLine() {
        $this->textToAdd .= '\n';
    }

    public function addTab() {
        $this->textToAdd .= '    ';
    }

    public function addTabs($count) {
        while ($count > 0) {
            $this->addTab();
            $count = $count - 1;
        }
    }

    public function addLineToLineGroup($text, $tabCount = 0) {
        $this->linesToAdd[] = [
          'text'=>$text,
          'tabCount'=>$tabCount
        ];
    }

    public function addLinesBelowText($text) {
       $this->findLineOfTextInFile($text);
        $this->createRawTextToAdd();
    }
}