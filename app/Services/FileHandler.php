<?php namespace App\Services;

class FileHandler  {
    public $filePath;
    public $textToAdd;
    public $lineToWriteNumber;
    public $lineToWriteSpaces;

    public $linesToAdd;

    public function createFile() {
        $this->fileHandler = fopen($this->filePath, 'w');
        fclose($this->fileHandler);
    }

    public function resetTextVariables() {
        $this->textToAdd = '';
        $this->linesToAdd = [];
        $this->lineToWriteSpaces = 0;
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
        $this->textToAdd .= "\n";
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

    public function addSpace() {
        $this->textToAdd .= ' ';
    }

    public function addSpaces($count) {
        while ($count > 0) {
            $this->addSpace();
            $count = $count - 1;
        }
    }

    public function addLineToLineGroup($text, $tabCount = 0) {
        $this->linesToAdd[] = [
          'text'=>$text,
          'tabCount'=>$tabCount
        ];
    }

    public function emptyLine() {
        $this->linesToAdd[] = [
          'text'=>'',
          'tabCount'=>0
        ];
    }

    public function createRawTextToAdd($newFile = false) {
        foreach ($this->linesToAdd as $index=>$lineToAdd) {
            if ($index != 0 || !$newFile) {
                $this->addLine();
            }

            $this->addSpaces($this->lineToWriteSpaces);
            $this->addTabs($lineToAdd['tabCount']);
            $this->textToAdd = $this->textToAdd.$lineToAdd['text'];
        }
    }

    public function addLinesAboveText($text) {
       $this->findLineOfTextInFile($text);
        $this->createRawTextToAdd();

        $lineToEdit = $this->lineToWriteNumber - 1;
        $lines = file( $this->filePath , FILE_IGNORE_NEW_LINES );
        $lines[$lineToEdit] = $lines[$lineToEdit].$this->textToAdd;
        file_put_contents( $this->filePath , implode( "\n", $lines ) );
        $this->resetTextVariables();
    }

    public function writeToNewFile() {
        $this->createRawTextToAdd(true);
        shell_exec('sudo chmod -R 777 '.$this->filePath);
        file_put_contents( $this->filePath , $this->textToAdd);
        $this->resetTextVariables();
    }

    public function writeToLine() {
        $lineToEdit = $this->lineToWriteNumber - 1;
        $lines = file( $this->filePath , FILE_IGNORE_NEW_LINES );
        $lines[$lineToEdit] = $lines[$lineToEdit].$this->textToAdd;
        file_put_contents( $this->filePath , implode( "\n", $lines ) );
    }

    public function clearFileAndWriteNewText() {
        $file = fopen($this->filePath, 'w');
        ftruncate($file, 0);
        $this->createRawTextToAdd();
        file_put_contents( $this->filePath , $this->textToAdd);
        $this->resetTextVariables();
    }
}