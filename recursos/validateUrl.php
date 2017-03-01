<?php
/**
 * @Property: gregoriobolivar.com.ve
 * @Author: Gregorio Bolívar
 * @email: elalconxvii@gmail.com
 * @Creation Date: 19/11/2016
 * @Audited by: Gregorio J Bolívar B
 * @Modified Date: 20/11/2016
 * @Description: Codigo que permite validar si un url es Valido y ademas extraer su meta tab.
 * @package: action.class.php
 * @version: 0.5
 * @webs: http://www.gregoriobolivar.com.ve/ 
 * @Blog: http://gbbolivar.wordpress.com/
 */
$typ=@$_POST['typ'];
$url=@$_POST['url'];
switch ($typ) {
	case 'validateUrl':
		// Procedimiento para validar si la url es valida y esta disponible
		$a=@fopen($url,"r");;
		if ($a){
			$validate = true;
			// Procedimiento para extraer los metatags de la url enviada
			$b=get_meta_tags($url);
			$responce['metatags'] = $b; 
			// Extraer el Titulo de la pagina
			$str = file_get_contents($url);
			if(strlen($str)>0){
		        preg_match("@<title>(.*)</title>@",$str,$title);
		        $responce['title'] = $title[1];
		    }
		}else{
			$validate = false;
		}
		$responce['validate'] = $validate; 
	break;

	default:
		$responce['validate'] = false;
	break;
}
echo json_encode($responce);