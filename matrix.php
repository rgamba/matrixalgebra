<?php
/**
* matrix.php
* ---
* Matrix algebra class that performs and
* calculates all the basic matrix operations
* and functions
* 
* @author Ricardo Gamba
* @copyright Public
* @version 1.0
*/
class Matrix{
	public $matrix;
	private $decimal=4;
	
	public function __construct($matrix=""){
		if(!empty($matrix))
			$this->set($matrix);
		
	}
	
    /**
    * Check for a valid matrix instance
    * 
    * @param mixed $a
    */
	public function validate($a=NULL){
		if(is_null($a))
			return false;
		if(!is_array($a))
			return false;
		return true;
	}
	
    /**
    * Get the matrix size
    * 
    * @param mixed $a
    * @return mixed
    */
	public function size($a=NULL){
		if(!$this->validate($a))
			return false;
		$row_num=0;
		foreach($a as $i => $row){
			if(empty($row)){
				$row_num=count($row);
				continue;
			}
			if(count($row)>$row_num){
				$row_num=count($row);
			}
		}
		return array('rows'=>count($a),'cols'=>$row_num);
	}
	
    /**
    * Adjust the matrix parameter
    * 
    * @param mixed $a
    */
	public function adjust($a=NULL){
		$size=$this->size($a);
		if(!$size)
			return false;
		for($i=0;$i<=($size[0]-1);$i++){
			for($j=0;$j<=($size[1]-1);$j++){
				if(!isset($a[$i][$j])){
					$a[$i][$j]=0;
				}
			}
		}
		return $a;
	}
	
    /**
    * Print the matrix on an HTML table
    * 
    * @param mixed $a
    */
	public function plotHtml($a=NULL){
		if(!$this->validate($a))
			return false;
		$a=$this->adjust($a);
		$html='<table border="0" cellpadding="2">';
		foreach($a as $i => $col){
			$html.='<tr>';
			foreach($col as $j => $row){
				$html.='<td>'.$a[$i][$j].'</td>';
			}
			$html.='</tr>';
		}
		$html.='</table>';
		return $html;
	}
	
    /**
    * Print a raw text matrix
    * 
    * @param mixed $a
    * @param mixed $text
    */
	public function plotRaw($a=NULL,$text="Matrix"){
		if(!$this->validate($a))
			return false;
		$a=$this->adjust($a);
		$html='';
		foreach($a as $i => $col){
			foreach($col as $j => $row){
				$html.=((!is_null($this->decimal)) ? round($a[$i][$j],$this->decimal) : $a[$i][$j]) ."\t";
			}
			$html.="\n\r";
		}
		$size=$this->size($a);
		echo "\n\r$text ({$size[rows]}x{$size[cols]}):\n\r".$html."\n\r";
		return $html;
	}
	
    /**
    * Alias for plotRaw
    * 
    * @param mixed $a
    * @param mixed $text
    */
	public function plot($a,$text="Matriz"){
		return $this->plotRaw($a,$text);
	}
	
    /**
    * Create a new matrix
    * 
    * @param mixed $matrix
    * @return mixed
    */
	public function create($matrix=NULL){
		if(!is_array($matrix)){
			$matrix=str_replace(" ",",",$matrix);
			$mat_str=array();
			$tmp=array();
			preg_match('/\[(.*)\]/',$matrix,$mat_str);
			$mat_str=$mat_str[1];
			if(!empty($mat_str)){
				$rows=explode(";",$mat_str);
				foreach($rows as $i => $row){
					$cols=explode(",",$row);
					if(!empty($cols)){
						$sub=array();
						foreach($cols as $j => $col){
							preg_match('/\[(.*)\]/',$col,$sub);
							if(!empty($sub)){
								$tmp[$i][$j]=$this->create($col);	
							}else{
								$tmp[$i][$j]=$col;
							}
						}
					}
				}
			}
			$matrix=$tmp;
		}
		$matrix=$this->adjust($matrix);
		return $matrix;
	}
	
    /**
    * Perform SUM operation
    * 
    * @param mixed $a
    * @param mixed $b
    */
	public function sum($a=NULL,$b=NULL){
		if(!$this->validate($a))
			$this->error("sum()","Incorrect parameters");
		$a=$this->adjust($a);
		$sa=$this->size($a);
		if(is_array($b)){
			$b=$this->adjust($b);
			$sb=$this->size($b);
		}	
		if(is_array($b) && ($sa['cols']!=$sb['cols'] || $sa['rows']!=$sb['rows']))
			$this->error("sum()","El numero de filas y columnas de A y B deben ser iguales");
		$sum=array();
		for($i=0;$i<$sa['rows'];$i++){
			for($j=0;$j<$sa['cols'];$j++){
				$sum[$i][$j]=$a[$i][$j]+(is_array($b) ? $b[$i][$j] : $b);
			}
		}
		return $sum;
	}
    
    /**
    * Direct multiplication operation
    * 
    * @param mixed $a
    * @param mixed $b
    * @param mixed $plot
    */
	public function dmult($a=NULL,$b=NULL,$plot=true){
		if(!$this->validate($a))
			$this->error("dmult()","Incorrect parameters");
		$a=$this->adjust($a);
		$sa=$this->size($a);
		if(is_array($b)){
			$b=$this->adjust($b);
			$sb=$this->size($b);
		}	
		if(is_array($b) && ($sa['cols']!=$sb['cols'] || $sa['rows']!=$sb['rows']))
			$this->error("dmult()","El numero de filas y columnas de A y B deben ser iguales");
		$sum=array();
		for($i=0;$i<$sa['rows'];$i++){
			for($j=0;$j<$sa['cols'];$j++){
				$prod[$i][$j]=$a[$i][$j]*(is_array($b) ? $b[$i][$j] : $b);
			}
		}
		if($plot)
			$this->plotRaw($prod,"Direct multiplication");
		return $prod;
	}
	
    /**
    * Substraction
    * 
    * @param mixed $a
    * @param mixed $b
    */
	public function subs($a=NULL,$b=NULL){
		if(!$this->validate($a))
			$this->error("subs()","Incorrect parameters");
		$a=$this->adjust($a);
		$sa=$this->size($a);
		if(is_array($b)){
			$b=$this->adjust($b);
			$sb=$this->size($b);
		}	
		if(is_array($b) && ($sa['cols']!=$sb['cols'] || $sa['rows']!=$sb['rows']))
			$this->error("subs()","The number of columns and rows of A and B must be equal");
		$sum=array();
		for($i=0;$i<$sa['rows'];$i++){
			for($j=0;$j<$sa['cols'];$j++){
				$subs[$i][$j]=$a[$i][$j]-(is_array($b) ? $b[$i][$j] : $b);
			}
		}
		return $subs;
	}
	
    /**
    * Normal matrix multiplication
    * 
    * @param mixed $a
    * @param mixed $b
    */
	public function mult($a=NULL,$b=NULL){
		if(!$this->validate($a) || !$this->validate($b))
			$this->error("mult()","Incorrect parameters");
		$a=$this->adjust($a);	$b=$this->adjust($b);
		$sa=$this->size($a);	$sb=$this->size($b);
		$res=array();
		$tmp=0;
		if($sa['cols']!=$sb['rows'])
			$this->error("mult()","The number of columns of A must equal the number of rows of B");
		for($k=1;$k<=$sb['cols'];$k++){
			foreach($a as $i => $row){
				foreach($b as $j => $col){
					$tmp+=$a[$i][$j]*$b[$j][$k-1];
				}
				$res[$i][$k]=$tmp;
				$tmp=0;
			}
		}
		return $res;
	}
	
    /**
    * Matrix determinant
    * 
    * @param mixed $a
    * @return mixed
    */
	public function det($a=NULL){
		if(is_null($a))
			$this->error("det()","Incorrect parameters");
		if(!is_array($a))
			return $a;
		$a=$this->adjust($a);
		$sa=$this->size($a);
		$res=array();
		if($sa['rows']!=$sa['cols'])
			$this->error("det()","The matrix must be square");
		if($sa['cols']<2){
			return $a[0][0];
		}
		$res=0;
		for($i=0;$i<=($sa['rows']-1);$i++){
			// Remove row $i and column $i
			$pivot=((($i+1)&1) ? '' : '-').$a[0][$i];
			$tmp=$this->remCellCoords($a,array(0,$i));
			$tmp_det=$this->det($tmp); // Recursion
			$res+=$pivot*$tmp_det;
			
		}
		return $res;
	}
	
    // Array=fila,columna
	public function remCellCoords($a=NULL,$coords=array()){
		if(!$this->validate($a))
			$this->error("remCelCoords()","Parametro de matriz invalido");
		if(empty($coords))
			$this->error("remCelCoords()","Coordenadas invalidas");
		$size=$this->size($a);
		$num_i=0;
		$num_j=0;
		for($i=0;$i<=$size['rows'];$i++){
			if($i==$coords[0])
				continue;
			$avanza_i=false;
			$num_j=0;
			for($j=0;$j<=$size['cols'];$j++){
				if($j==$coords[1])
					continue;
				if(!empty($a[$i][$j])){
					$tmp[$num_i][$num_j]=$a[$i][$j];
					$avanza_i=true;
					$num_j++;
				}
			}
			if($avanza_i)
				$num_i++;
		}
		return $tmp;
	}
	
    /**
    * Determine if a matrix is square
    * 
    * @param mixed $a
    */
	public function isSquare($a=NULL){
		if(!$this->validate($a))
			return false;
		$s=$this->size($a);
		if($s['rows']==$s['cols'])
			return true;
		return false;
	}
	
    /**
    * Adjoint
    * 
    * @param mixed $a
    * @param mixed $plot
    */
	public function adj($a=NULL,$plot=true){
		if(!$this->isSquare($a))
			$this->error("adj()","The matrix must be square");
		$s=$this->size($a);
		for($i=0;$i<$s['rows'];$i++){
			for($j=0;$j<$s['cols'];$j++){
				$tmp=$this->remCellCoords($a,array($i,$j));
				$adj[$i][$j]=(((($i+$j)&1) ? '-' : '').'1')*$this->det($tmp);
			}
		}
		if($plot)
			$this->plotRaw($adj,"Adjacent");
		return $adj;
	}
	
    /**
    * Solve by Gauss Jordan algorythm
    * (Same function as rref() in Matlab)
    * 
    * @param mixed $a
    * @param mixed $b
    */
	public function solve($a=NULL,$b=NULL){
		if(!is_null($b))
			$a=$this->add($a,$b);
		if(!$this->validate($a))
			$this->error("solve()","Invalid matrix parameter");
		$size=$this->size($a);
		for($i=0;$i<$size['rows'];$i++){
			$temp=$a[$i][$i];
			for($j=0;$j<$size['cols'];$j++){
				if($temp!=0){
					$a[$i][$j]=$a[$i][$j]/$temp;
				}else{
					$a[$i][$j]=0;
				}
			}
			for($k=0;$k<$size['rows'];$k++){
				$temp=$a[$k][$i];
				if($i!=$k){
					for($j=0;$j<$size['cols'];$j++){
						$a[$k][$j]=$a[$k][$j]-($temp*$a[$i][$j]);
					}
				}
			}
		}
		return $a;	
	}
	
    /**
    * Determine the number of decimal precision
    * 
    * @param mixed $num
    */
	public function decimals($num=NULL){
		$this->decimal=$num;
		return;	
	}
	
    /**
    * Identity matrix
    * 
    * @param mixed $size
    * @return mixed
    */
	public function I($size=NULL){
		if(!is_array($size) && !is_null($size))
			$size=array($size,$size);
		if(!is_array($size) || is_null($size))
			$this->error("I()","The parameter must be an array");
		$arr='[';
		for($i=1;$i<=$size[1];$i++){
			for($j=1;$j<=$size[0];$j++){
				$arr.=($j==$i) ? '1' : '0';
				$arr.=($j==$size[0] && $i!=$size[1]) ? ';' : (($j!=$size[0]) ? ' ' : '');
			}
		}
		$arr.=']';
		return $this->create($arr);
	}
	
    /**
    * "Concatenate" matrices
    * 
    * @param mixed $a
    * @param mixed $b
    */
	public function add($a=NULL,$b=NULL){
		if(!$this->validate($a) || !$this->validate($b))
			$this->error("add()","Invalid matrix parameters");
		$sa=$this->size($a);
		$sb=$this->size($b);
		if($sa['rows']!=$sb['rows'])
			$this->error("add()","The number of rows must be the same");
		for($i=0;$i<$sb['cols'];$i++){
			for($j=0;$j<$sb['rows'];$j++){
				$a[$i][$sa['rows']+$j]=$b[$i][$j];
			}
		}
		return $a;
	}
	
    /**
    * Transpose 
    * 
    * @param mixed $a
    * @param mixed $plot
    */
	public function trans($a=NULL,$plot=true){
		if(!$this->validate($a))
			$this->error("trans()","Invalid matrix parameter");
		$s=$this->size($a);
		for($i=0;$i<$s['rows'];$i++){
			for($j=0;$j<$s['cols'];$j++){
				$r[$j][$i]=$a[$i][$j];
			}
		}
		if($plot)
			$this->plotRaw($r,"Transpose");
		return $r;
	}
	
    /**
    * Inverse matrix
    * 
    * @param mixed $a
    */
	public function inv($a=NULL){
		if(!$this->isSquare($a))
			$this->error("inv()","The matrix must be square");
		$adj=$this->adj($a,false);
		$det=$this->det($a);
		if($det==0)
			$this->error("inv()","The matrix determinant is cero");
		$res=$this->dmult($adj,(1/$det),false);
		return $res;
		
	}
	
	private function error($func,$msg){
		die("\n\r>> ERROR: [Matrix::$func]: $msg");
	}
}