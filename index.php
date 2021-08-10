<?php

class Block{
    // private $index;
    // private $timestamp;
    // private $data;
    // private $previousHash;
    protected $nonce;
    public function __construct($index,$timestamp,$data,$previousHash=""){
        $this->index = $index;
        $this->timestamp = $timestamp;
        $this->data = $data;
        $this->previousHash = $previousHash;
        $this->hash = $this->calculateHash();
        $this->nonce = 0;
    }

   function calculateHash(){
        return hash('sha256', $this->index.$this->timestamp.json_encode($this->data).$this->previousHash.$this->nonce);
    }

   function mineBlock($difficulty) {
		while (substr($this->hash,0,$difficulty) !== str_repeat('0',$difficulty)) {
			
            $this->nonce++;
			$this->hash = $this->calculateHash();
		}
		var_dump("BLOCK MINED: ". $this->hash);
	}
}

class Blockchain{
   public function __construct(){
        $this->chain = array($this->createGenesisBlock());
        $this->difficulty =3;
    }

    function createGenesisBlock(){
        return new Block(0,"10/10/2021",array('amount'=>20),0);
    }

    function getLatestBlock(){
        return $this->chain[count($this->chain) -1];
    }
    function addBlock($newBlock){
        $newBlock->previousHash = $this->getLatestBlock()->hash;
        // $newBlock->hash = $newBlock->calculateHash();
        $newBlock->mineBlock($this->difficulty);
        array_push($this->chain, $newBlock);
    }

   function isChainValid(){
        for($i = 1; $i < count($this->chain); $i++){
            $currentBlock = $this->chain[$i];
            $previousBlock=$this->chain[$i-1];

            if($currentBlock->hash !== $currentBlock->calculateHash()){
                return false;
            }
            if($currentBlock->previousHash !== $previousBlock->hash){
                return false;
            }
        }
        return true;
    }

}

$block = new Blockchain();
printf('this is mind 1 <br>');
$block->addBlock(new Block(1,"11/11/2021", array('amount'=>10)));
printf('<br>this is mind 2 <br>');
$block->addBlock(new Block(2,"11/11/2021",array('amount'=>101)));


// var_dump($block->isChainValid());
// var_dump($block);
?>