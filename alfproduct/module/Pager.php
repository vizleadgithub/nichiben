<?php
class Pager {
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	public $now_page;
	public $list_max;
	public $page_max;
	public $pager_max;
	public $pager_url;
	public $pager_url2;
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* コンストラクタ
	* ディレクトリ指定やデフォルト値の初期設定を行う
	*/
	public function __construct() {
		$this->now_page = 1;
		$this->list_max = 0;
		$this->page_max = 20;
		$this->pager_max = 4;
		$this->pager_url = 'index.php?page=';
		$this->pager_url2 = '';
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function setNowPage($now_page){
		$this->now_page = $now_page;
	}
	function setPageMax($page_max){
		$this->page_max = $page_max;
	}
	function setListMax($list_max){
		$this->list_max = $list_max;
	}
	function setPagerMax($pager_max){
		$this->pager_max = $pager_max;
	}
	function setPagerUrl($pager_url,$pager_url2=""){
		$this->pager_url = $pager_url;
		$this->pager_url2 = $pager_url2;
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function getOffsetStart(){
		$arr = array();
		if( ($this->now_page - 1)<0 ){
			$arr["start"] = 0;
		} else {
			$arr["start"] = ($this->now_page - 1) * $this->page_max;
		}
		if( ($arr["start"] + $this->page_max) > $this->list_max && $this->page_max>0){
			$arr["start"] = (int)($this->list_max / $this->page_max) * $this->page_max;
		}
		return $arr["start"]+1;
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function getOffsetEnd(){
		$arr = array();
		if( ($this->now_page - 1)<0 ){
			$arr["start"] = 0;
		} else {
			$arr["start"] = ($this->now_page - 1) * $this->page_max;
		}
		if( ($arr["start"] + $this->page_max) > $this->list_max && $this->page_max>0 ){
			$arr["start"] = (int)($this->list_max / $this->page_max) * $this->page_max;
		}
		if( ($arr["start"] + $this->page_max)<=$this->list_max ){
			return ($arr["start"] + $this->page_max);
		} else {
			return ($this->list_max);
		}
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function getOffset(){
		$arr = array();
		if( ($this->now_page - 1)<0 ){
			$arr["start"] = 0;
		} else {
			$arr["start"] = ($this->now_page - 1) * $this->page_max;
		}
		if( ($arr["start"] + $this->page_max) > $this->list_max && $this->page_max>0 ){
			$arr["start"] = (int)($this->list_max / $this->page_max) * $this->page_max;
		}
		if ($this->page_max == 0){
			return "";
		} else {
			return " LIMIT ".$arr["start"].",".$this->page_max. " ";
		}
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function getPager(){
		$arr = array();
		$arr["start"] = $this->now_page - 2;
		if( $arr["start"] < 1 ){
			$arr["start"] = 1;
		}
		if( $arr["start"] < 1 ){
			$arr["start"] = 1;
		}

		if ($this->list_max>0 && $this->page_max>0){
			$arr["max"] = (int)($this->list_max / $this->page_max);
			if( ($this->list_max % $this->page_max) > 0 ){
				$arr["max"] += 1;
			}
		} else {
			$arr["max"] = 1;
		}
		$arr["end"] = $arr["start"] + $this->pager_max;
		while($arr["end"]>$arr["max"]){
			$arr["end"]-=1;
		}
		$arr["ppage"] = $this->now_page - 1;
		if( $arr["ppage"]<1 ){
			$arr["ppage"] = 1;
		}
		$arr["npage"] = $this->now_page + 1;
		if( $arr["npage"]>$arr["max"] ){
			$arr["npage"] = $arr["max"];
		}

		for( $loop_i=0;$loop_i<($this->pager_max);$loop_i++ ){
			if( ($arr["start"]-1) > 1){
				if ($arr["end"]-$arr["start"]<$this->pager_max) {
					$arr["start"] -= 1;
				}
			}
		}

		$return = '';
		$return.= '<table style="clear:both;margin-left:auto;"><tr>';
		if ($this->now_page > 1){
			$return.= '<td style="padding:3px;font-size:12px;text-align:center;"><a href="'.$this->pager_url.'1'.$this->pager_url2.'">最初</a></td>'."\n";
			$return.= '<td style="padding:3px;font-size:12px;text-align:center;"><a href="'.$this->pager_url.$arr["ppage"].$this->pager_url2.'">&lt;</a></td>'."\n";
		}
		$temp_i = 0;
		while( ($arr["start"]+$temp_i) <= $arr["end"] ){
			if( ($arr["start"]+$temp_i) == $this->now_page ){
				$return.= '<th style="padding:3px;font-size:12px;text-align:center;">'.$this->now_page.'</th>';
			} else {
				$return.= '<td style="padding:3px;font-size:12px;text-align:center;"><a href="'.$this->pager_url.($arr["start"]+$temp_i).$this->pager_url2.'">'.($arr["start"]+$temp_i).'</a></td>';
			}
			$temp_i += 1;
		}
		
		//$return.= "\n";
		if ($this->now_page < $arr["max"]){
			$return.= '<td style="padding:3px;font-size:12px;text-align:center;"><a href="'.$this->pager_url.$arr["npage"].$this->pager_url2.'">&gt;</a></td>'."\n";
			$return.= '<td style="padding:3px;font-size:12px;text-align:center;"><a href="'.$this->pager_url.$arr["max"].$this->pager_url2.'">最後</a></td>'."\n";
		}
		$return.= '</tr></table>';
		return $return;
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
}
?>
