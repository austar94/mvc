<?php
namespace APP\Dto;

class BoardResult implements \JsonSerializable
{
	private $boardList;

	public function setBoardList($boardList){
		$this->$boardList		=	$boardList;
	}

	public function getBoardList(){
		return $this->boardList;
	}

	public function jsonSerialize()
    {
        return[
            'boardList' => $this->boardList
        ];
	}
	
}