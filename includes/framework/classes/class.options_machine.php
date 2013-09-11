<?php 
/**
 * SMOF Options Machine Class
 *
 * @package     WordPress
 * @subpackage  SMOF
 * @since       1.0.0
 * @author      Syamil MJ
 */

class Options_Machine {

	/**
	 * PHP5 contructor
	 *
	 * @since 1.0.0
	 */
	function __construct($options) {
		
		$return = $this->optionsframework_machine($options);
		
		$this->Inputs = $return[0];
		$this->Menu = $return[1];
		$this->Defaults = $return[2];
		
	}


	/**
	 * Process options data and build option fields
	 *
	 * @uses get_option()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function optionsframework_machine($options) {
	
	    $data = get_option(OPTIONS);
		
		$defaults = array();   
	    $counter = 0;
		$menu = '';
		$output = '';

		foreach ($options as $value) {
		
			$counter++;
			$val = '';

			/***** Updated by Gtwebsite *****/
			if(!isset($value['std'])){
				$value['std'] = '';
			}
			/********************************/
			
			//create array of defaults		
			if ($value['type'] == 'multicheck'){
				if (is_array($value['std'])){
					foreach($value['std'] as $i=>$key){
						$defaults[$value['id']][$key] = true;
					}
				} else {
						$defaults[$value['id']][$value['std']] = true;
				}
			}
			
			//Start Heading
			if ( $value['type'] != 'heading' ) { 
			 	$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
				
				//hide items in checkbox group
				$fold='';
				if (array_key_exists("fold",$value)) {
					/***** Updated by Gtwebsite *****/
					$folds = $value['fold'];
					if (is_array($folds)) {
						foreach($folds as $v){
							$fold .= 'f_'.$v.' ';
						}
						$fold .= ' temphide ';
					}else{
						if (isset($data[$value['fold']])) {
							$fold='f_'.$value['fold'].' ';
						} else {
							$fold='f_'.$value['fold'].' temphide ';
						}
					}
					/*
					if ($data[$value['fold']]) {
						$fold="f_".$value['fold']." ";
					} else {
						$fold="f_".$value['fold']." temphide ";
					}
					*/
				}
				
				/***** Updated by Gtwebsite *****/
				if( $value['type']=='group_begin' ){
					$output .= '<div class="metabox-holder"><div class="postbox">'."\n";
					if($value['name']) $output .= '<h3 class="hndle"><span>'. $value['name'] .'</span></h3>'."\n";
					$output .= '<div class="inside">'."\n";
				}elseif( $value['type']=='group_end' ){
					$output .= '</div></div></div>'."\n";
				}else{
					$output .= '<div class="'.$fold.'section section-'.$value['type'].' '. $class .'">'."\n";
					//only show header if 'name' value exists
					if(isset($value['name'])) $output .= '<div class="label">'. $value['name'] .'</div>'."\n";
					$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";
				}
			 } 
			 //End Heading
			
			//switch statement to handle various options type                              
			switch ( $value['type'] ) {
			
	
				//text input
				case 'text':
					/***** Updated by Gtwebsite *****/
					$textarea_stored = isset($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					/********************************/
					$textarea_stored = stripslashes($textarea_stored);
					
					$mini ='';
					if(!isset($value['mod'])) $value['mod'] = '';
					if($value['mod'] == 'mini') { $mini = 'mini';}
					if(!isset($value['placeholder'])) $value['placeholder'] = '';
					
					/***** Updated by Gtwebsite *****/
					$output .= '<input class="of-input '.$mini.'" name="'.$value['id'].'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $textarea_stored .'" placeholder="'. $value['placeholder'] .'" />';
					// $output .= '<input class="of-input '.$mini.'" name="'.$value['id'].'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $t_value .'" />';
					/********************************/
				break;
				
				//select option
				case 'select':
					$mini ='';
					/***** Updated by Gtwebsite *****/
					$select_stored = isset($data[$value['id']]) ? $data[$value['id']] : $value['std'];

					if (array_key_exists("folds",$value)) $fold="fld ";
					if(!isset($value['mod'])) $value['mod'] = '';
					if($value['mod'] == 'mini') { $mini = 'mini';}
					$output .= '<div class="select_wrapper ' . $mini . '">';
					$output .= '<select class="'.$fold.'select of-input" name="'.$value['id'].'" id="'. $value['id'] .'">';
					foreach ($value['options'] as $select_ID => $option) {	
						/***** Updated by Gtwebsite *****/
						$output .= '<option value="' . $select_ID . '" ' . selected($select_stored, $select_ID, false) . ' />'.$option.'</option>';	 
						//$output .= '<option id="' . $select_ID . '" value="'.$option.'" ' . selected($data[$value['id']], $option, false) . ' />'.$option.'</option>';	 		
					 } 
					$output .= '</select></div>';
				break;
				
				//textarea option
				case 'textarea':	
					/***** Updated by Gtwebsite *****/
					$textarea_stored = isset($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					/********************************/
					$cols = '8';
						
						$textarea_stored = stripslashes($textarea_stored);			
						$output .= '<textarea class="of-input" name="'.$value['id'].'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$textarea_stored.'</textarea>';		
				break;
				
				//radiobox option
				case "radio":
					
					/***** Updated by Gtwebsite *****/
					$radio_stored = isset($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					/********************************/

					 foreach($value['options'] as $option=>$name) {
						 /***** Updated by Gtwebsite *****/
						$output .= '<label class="radio"><input class="of-input of-radio" name="'.$value['id'].'" type="radio" value="'.$option.'" ' . checked($radio_stored, $option, false) . ' /> '.$name.'</label><br/>';				
						//$output .= '<input class="of-input of-radio" name="'.$value['id'].'" type="radio" value="'.$option.'" ' . checked($data[$value['id']], $option, false) . ' /><label class="radio">'.$name.'</label><br/>';				
						/********************************/
					}
				break;
				
				//checkbox option
				case 'checkbox':
					/***** Updated by Gtwebsite *****/
					$checkbox_stored = isset($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					/********************************/
					
					$fold = '';
					if (array_key_exists("folds",$value)) $fold="fld ";
		
					$output .= '<input type="hidden" class="'.$fold.'checkbox aq-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="0"/>';
					$output .= '<label><input type="checkbox" class="'.$fold.'checkbox of-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="1" '. checked($checkbox_stored, 1, false) .' /> '.$value['desc'].'</label>';
					//$output .= '<input type="checkbox" class="'.$fold.'checkbox of-input" name="'.$value['id'].'" id="'. $value['id'] .'" value="1" '. checked($data[$value['id']], 1, false) .' />';
				break;
				
				//multiple checkbox option
				case 'multicheck': 			
					(isset($data[$value['id']]))? $multi_stored = $data[$value['id']] : $multi_stored='';
								
					foreach ($value['options'] as $key => $option) {
						if (!isset($multi_stored[$key])) {$multi_stored[$key] = '';}
						$of_key_string = $value['id'] . '_' . $key;
						$output .= '<input type="checkbox" class="checkbox of-input" name="'.$value['id'].'['.$key.']'.'" id="'. $of_key_string .'" value="1" '. checked($multi_stored[$key], 1, false) .' /><label class="multicheck" for="'. $of_key_string .'">'. $option .'</label><br />';								
					}			 
				break;
				
				//ajax image upload option
				case 'upload':
					if(!isset($value['mod'])) $value['mod'] = '';
					$output .= Options_Machine::optionsframework_uploader_function($value['id'],$value['std'],$value['mod']);			
				break;
				
				// native media library uploader - @uses optionsframework_media_uploader_function()
				case 'media':
					$_id = strip_tags( strtolower($value['id']) );
					$int = '';
					$int = optionsframework_mlu_get_silentpost( $_id );
					if(!isset($value['mod'])) $value['mod'] = '';
					/***** Updated by Gtwebsite *****/
					$output .= Options_Machine::optionsframework_media_uploader_function( $value['id'], $value['std'], $int, $value['mod'], $value['placeholder'] ); // New AJAX Uploader using Media Library			
					//$output .= Options_Machine::optionsframework_media_uploader_function( $value['id'], $value['std'], $int, $value['mod'] ); // New AJAX Uploader using Media Library			
					/********************************/
				break;
				
				//colorpicker option
				case 'color':		
					/***** Updated by Gtwebsite *****/
					$color_stored = isset($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					/********************************/
					$output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div style="background-color: '.$color_stored.'"></div></div>';
					$output .= '<input class="of-color" name="'.$value['id'].'" id="'. $value['id'] .'" type="text" value="'. $color_stored .'" />';
				break;
				
				//typography option	
				case 'typography':
				
					$typography_stored = isset($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					
					/* Font Size */
					
					if(isset($typography_stored['size'])) {
						$output .= '<div class="select_wrapper typography-size" original-title="Font size">';
						$output .= '<select class="of-typography of-typography-size select" name="'.$value['id'].'[size]" id="'. $value['id'].'_size">';
							/***** Updated by Gtwebsite *****/
							for ($i = 10; $i < 31; $i++){ 
							//for ($i = 9; $i < 20; $i++){ 
							/********************************/
								$test = $i.'px';
								$output .= '<option value="'. $i .'px" ' . selected($typography_stored['size'], $test, false) . '>'. $i .'px</option>'; 
								}
				
						$output .= '</select></div>';
					
					}
					
					/* Line Height */
					if(isset($typography_stored['height'])) {
					
						$output .= '<div class="select_wrapper typography-height" original-title="Line height">';
						$output .= '<select class="of-typography of-typography-height select" name="'.$value['id'].'[height]" id="'. $value['id'].'_height">';
							for ($i = 20; $i < 38; $i++){ 
								$test = $i.'px';
								$output .= '<option value="'. $i .'px" ' . selected($typography_stored['height'], $test, false) . '>'. $i .'px</option>'; 
								}
				
						$output .= '</select></div>';
					
					}
						
					/* Font Face */
					if(isset($typography_stored['face'])) {
					
						$output .= '<div class="select_wrapper typography-face" original-title="Font family">';
						$output .= '<select class="of-typography of-typography-face select" name="'.$value['id'].'[face]" id="'. $value['id'].'_face">';
						
						$faces = array('Arial, serif'=>'Arial',
										/***** Updated by Gtwebsite *****/
										'\'Calibri\', Arial, sans-serif'=>'Calibri',
										'\'Century Gothic\', Arial, sans-serif'=>'Century Gothic',
										'\'Lucida Grande\', \'Lucida Sans Unicode\', Arial, sans-serif'=>'Lucida Grande',
										'Helvetica, sans-serif'=>'Helvetica',		
										'\'Segoe UI\', Arial, sans-serif'=>'Segoe UI',		
										'\'Segoe UI Light\', Arial, sans-serif'=>'Segoe UI Light',		
										'Trebuchet, sans-serif'=>'Trebuchet',
										'Verdana, sans-serif'=>'Verdana, Geneva',
										'Georgia, serif' =>'Georgia',
										'Palatino, serif'=>'Palatino',
										'Times New Roman, serif'=>'Times New Roman',
										'Tahoma, Geneva, serif'=>'Tahoma, Geneva',
										''=>'-------',		
										'google_font'=>'Show Google Fonts' );
										/********************************/			
						foreach ($faces as $i=>$face) {
							$output .= '<option value="'. $i .'" ' . selected($typography_stored['face'], $i, false) . '>'. $face .'</option>';
						}			
										
						$output .= '</select></div>';
					
					}
					
					/* Font Weight */
					if(isset($typography_stored['style'])) {
					
						$output .= '<div class="select_wrapper typography-style" original-title="Font style">';
						$output .= '<select class="of-typography of-typography-style select" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
						$styles = array('normal'=>'Normal',
										'italic'=>'Italic',
										'bold'=>'Bold',
										'bold italic'=>'Bold Italic');
										
						foreach ($styles as $i=>$style){
						
							$output .= '<option value="'. $i .'" ' . selected($typography_stored['style'], $i, false) . '>'. $style .'</option>';		
						}
						$output .= '</select></div>';
					
					}
					
					/* Font Color */
					if(isset($typography_stored['color'])) {
					
						$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector typography-color"><div style="background-color: '.$typography_stored['color'].'"></div></div>';
						$output .= '<input class="of-color of-typography of-typography-color" original-title="Font color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $typography_stored['color'] .'" />';
					
					}
					
					/***** Updated by Gtwebsite *****/
					if(isset($typography_stored['face'])) {
						$output .= '<div class="select_wrapper typography-google" original-title="Font style" style="margin-right:6px;">';
						$output .= '<select class="of-typography of-typography-google select" name="'.$value['id'].'[google]" id="'. $value['id'].'_google">';
						$google_fonts = 
array('ABeeZee'=>'ABeeZee','Abel'=>'Abel','Abril Fatface'=>'Abril Fatface','Aclonica'=>'Aclonica','Acme'=>'Acme','Actor'=>'Actor','Adamina'=>'Adamina','Advent Pro'=>'Advent Pro','Aguafina Script'=>'Aguafina Script','Akronim'=>'Akronim','Aladin'=>'Aladin','Aldrich'=>'Aldrich','Alef'=>'Alef','Alegreya'=>'Alegreya','Alegreya SC'=>'Alegreya SC','Alex Brush'=>'Alex Brush','Alfa Slab One'=>'Alfa Slab One','Alice'=>'Alice','Alike'=>'Alike','Alike Angular'=>'Alike Angular','Allan'=>'Allan','Allerta'=>'Allerta','Allerta Stencil'=>'Allerta Stencil','Allura'=>'Allura','Almendra'=>'Almendra','Almendra Display'=>'Almendra Display','Almendra SC'=>'Almendra SC','Amarante'=>'Amarante','Amaranth'=>'Amaranth','Amatic SC'=>'Amatic SC','Amethysta'=>'Amethysta','Anaheim'=>'Anaheim','Andada'=>'Andada','Andika'=>'Andika','Angkor'=>'Angkor','Annie Use Your Telescope'=>'Annie Use Your Telescope','Anonymous Pro'=>'Anonymous Pro','Antic'=>'Antic','Antic Didone'=>'Antic Didone','Antic Slab'=>'Antic Slab','Anton'=>'Anton','Arapey'=>'Arapey','Arbutus'=>'Arbutus','Arbutus Slab'=>'Arbutus Slab','Architects Daughter'=>'Architects Daughter','Archivo Black'=>'Archivo Black','Archivo Narrow'=>'Archivo Narrow','Arimo'=>'Arimo','Arizonia'=>'Arizonia','Armata'=>'Armata','Artifika'=>'Artifika','Arvo'=>'Arvo','Asap'=>'Asap','Asset'=>'Asset','Astloch'=>'Astloch','Asul'=>'Asul','Atomic Age'=>'Atomic Age','Aubrey'=>'Aubrey','Audiowide'=>'Audiowide','Autour One'=>'Autour One','Average'=>'Average','Average Sans'=>'Average Sans','Averia Gruesa Libre'=>'Averia Gruesa Libre','Averia Libre'=>'Averia Libre','Averia Sans Libre'=>'Averia Sans Libre','Averia Serif Libre'=>'Averia Serif Libre','Bad Script'=>'Bad Script','Balthazar'=>'Balthazar','Bangers'=>'Bangers','Basic'=>'Basic','Battambang'=>'Battambang','Baumans'=>'Baumans','Bayon'=>'Bayon','Belgrano'=>'Belgrano','Belleza'=>'Belleza','BenchNine'=>'BenchNine','Bentham'=>'Bentham','Berkshire Swash'=>'Berkshire Swash','Bevan'=>'Bevan','Bigelow Rules'=>'Bigelow Rules','Bigshot One'=>'Bigshot One','Bilbo'=>'Bilbo','Bilbo Swash Caps'=>'Bilbo Swash Caps','Bitter'=>'Bitter','Black Ops One'=>'Black Ops One','Bokor'=>'Bokor','Bonbon'=>'Bonbon','Boogaloo'=>'Boogaloo','Bowlby One'=>'Bowlby One','Bowlby One SC'=>'Bowlby One SC','Brawler'=>'Brawler','Bree Serif'=>'Bree Serif','Bubblegum Sans'=>'Bubblegum Sans','Bubbler One'=>'Bubbler One','Buda'=>'Buda','Buenard'=>'Buenard','Butcherman'=>'Butcherman','Butterfly Kids'=>'Butterfly Kids','Cabin'=>'Cabin','Cabin Condensed'=>'Cabin Condensed','Cabin Sketch'=>'Cabin Sketch','Caesar Dressing'=>'Caesar Dressing','Cagliostro'=>'Cagliostro','Calligraffitti'=>'Calligraffitti','Cambo'=>'Cambo','Candal'=>'Candal','Cantarell'=>'Cantarell','Cantata One'=>'Cantata One','Cantora One'=>'Cantora One','Capriola'=>'Capriola','Cardo'=>'Cardo','Carme'=>'Carme','Carrois Gothic'=>'Carrois Gothic','Carrois Gothic SC'=>'Carrois Gothic SC','Carter One'=>'Carter One','Caudex'=>'Caudex','Cedarville Cursive'=>'Cedarville Cursive','Ceviche One'=>'Ceviche One','Changa One'=>'Changa One','Chango'=>'Chango','Chau Philomene One'=>'Chau Philomene One','Chela One'=>'Chela One','Chelsea Market'=>'Chelsea Market','Chenla'=>'Chenla','Cherry Cream Soda'=>'Cherry Cream Soda','Cherry Swash'=>'Cherry Swash','Chewy'=>'Chewy','Chicle'=>'Chicle','Chivo'=>'Chivo','Cinzel'=>'Cinzel','Cinzel Decorative'=>'Cinzel Decorative','Clicker Script'=>'Clicker Script','Coda'=>'Coda','Coda Caption'=>'Coda Caption','Codystar'=>'Codystar','Combo'=>'Combo','Comfortaa'=>'Comfortaa','Coming Soon'=>'Coming Soon','Concert One'=>'Concert One','Condiment'=>'Condiment','Content'=>'Content','Contrail One'=>'Contrail One','Convergence'=>'Convergence','Cookie'=>'Cookie','Copse'=>'Copse','Corben'=>'Corben','Courgette'=>'Courgette','Cousine'=>'Cousine','Coustard'=>'Coustard','Covered By Your Grace'=>'Covered By Your Grace','Crafty Girls'=>'Crafty Girls','Creepster'=>'Creepster','Crete Round'=>'Crete Round','Crimson Text'=>'Crimson Text','Croissant One'=>'Croissant One','Crushed'=>'Crushed','Cuprum'=>'Cuprum','Cutive'=>'Cutive','Cutive Mono'=>'Cutive Mono','Damion'=>'Damion','Dancing Script'=>'Dancing Script','Dangrek'=>'Dangrek','Dawning of a New Day'=>'Dawning of a New Day','Days One'=>'Days One','Delius'=>'Delius','Delius Swash Caps'=>'Delius Swash Caps','Delius Unicase'=>'Delius Unicase','Della Respira'=>'Della Respira','Denk One'=>'Denk One','Devonshire'=>'Devonshire','Didact Gothic'=>'Didact Gothic','Diplomata'=>'Diplomata','Diplomata SC'=>'Diplomata SC','Domine'=>'Domine','Donegal One'=>'Donegal One','Doppio One'=>'Doppio One','Dorsa'=>'Dorsa','Dosis'=>'Dosis','Dr Sugiyama'=>'Dr Sugiyama','Droid Sans'=>'Droid Sans','Droid Sans Mono'=>'Droid Sans Mono','Droid Serif'=>'Droid Serif','Duru Sans'=>'Duru Sans','Dynalight'=>'Dynalight','Eagle Lake'=>'Eagle Lake','Eater'=>'Eater','EB Garamond'=>'EB Garamond','Economica'=>'Economica','Electrolize'=>'Electrolize','Elsie'=>'Elsie','Elsie Swash Caps'=>'Elsie Swash Caps','Emblema One'=>'Emblema One','Emilys Candy'=>'Emilys Candy','Engagement'=>'Engagement','Englebert'=>'Englebert','Enriqueta'=>'Enriqueta','Erica One'=>'Erica One','Esteban'=>'Esteban','Euphoria Script'=>'Euphoria Script','Ewert'=>'Ewert','Exo'=>'Exo','Expletus Sans'=>'Expletus Sans','Fanwood Text'=>'Fanwood Text','Fascinate'=>'Fascinate','Fascinate Inline'=>'Fascinate Inline','Faster One'=>'Faster One','Fasthand'=>'Fasthand','Fauna One'=>'Fauna One','Federant'=>'Federant','Federo'=>'Federo','Felipa'=>'Felipa','Fenix'=>'Fenix','Finger Paint'=>'Finger Paint','Fjalla One'=>'Fjalla One','Fjord One'=>'Fjord One','Flamenco'=>'Flamenco','Flavors'=>'Flavors','Fondamento'=>'Fondamento','Fontdiner Swanky'=>'Fontdiner Swanky','Forum'=>'Forum','Francois One'=>'Francois One','Freckle Face'=>'Freckle Face','Fredericka the Great'=>'Fredericka the Great','Fredoka One'=>'Fredoka One','Freehand'=>'Freehand','Fresca'=>'Fresca','Frijole'=>'Frijole','Fruktur'=>'Fruktur','Fugaz One'=>'Fugaz One','Gabriela'=>'Gabriela','Gafata'=>'Gafata','Galdeano'=>'Galdeano','Galindo'=>'Galindo','Gentium Basic'=>'Gentium Basic','Gentium Book Basic'=>'Gentium Book Basic','Geo'=>'Geo','Geostar'=>'Geostar','Geostar Fill'=>'Geostar Fill','Germania One'=>'Germania One','GFS Didot'=>'GFS Didot','GFS Neohellenic'=>'GFS Neohellenic','Gilda Display'=>'Gilda Display','Give You Glory'=>'Give You Glory','Glass Antiqua'=>'Glass Antiqua','Glegoo'=>'Glegoo','Gloria Hallelujah'=>'Gloria Hallelujah','Goblin One'=>'Goblin One','Gochi Hand'=>'Gochi Hand','Gorditas'=>'Gorditas','Goudy Bookletter 1911'=>'Goudy Bookletter 1911','Graduate'=>'Graduate','Grand Hotel'=>'Grand Hotel','Gravitas One'=>'Gravitas One','Great Vibes'=>'Great Vibes','Griffy'=>'Griffy','Gruppo'=>'Gruppo','Gudea'=>'Gudea','Habibi'=>'Habibi','Hammersmith One'=>'Hammersmith One','Hanalei'=>'Hanalei','Hanalei Fill'=>'Hanalei Fill','Handlee'=>'Handlee','Hanuman'=>'Hanuman','Happy Monkey'=>'Happy Monkey','Headland One'=>'Headland One','Henny Penny'=>'Henny Penny','Herr Von Muellerhoff'=>'Herr Von Muellerhoff','Holtwood One SC'=>'Holtwood One SC','Homemade Apple'=>'Homemade Apple','Homenaje'=>'Homenaje','Iceberg'=>'Iceberg','Iceland'=>'Iceland','IM Fell Double Pica'=>'IM Fell Double Pica','IM Fell Double Pica SC'=>'IM Fell Double Pica SC','IM Fell DW Pica'=>'IM Fell DW Pica','IM Fell DW Pica SC'=>'IM Fell DW Pica SC','IM Fell English'=>'IM Fell English','IM Fell English SC'=>'IM Fell English SC','IM Fell French Canon'=>'IM Fell French Canon','IM Fell French Canon SC'=>'IM Fell French Canon SC','IM Fell Great Primer'=>'IM Fell Great Primer','IM Fell Great Primer SC'=>'IM Fell Great Primer SC','Imprima'=>'Imprima','Inconsolata'=>'Inconsolata','Inder'=>'Inder','Indie Flower'=>'Indie Flower','Inika'=>'Inika','Irish Grover'=>'Irish Grover','Istok Web'=>'Istok Web','Italiana'=>'Italiana','Italianno'=>'Italianno','Jacques Francois'=>'Jacques Francois','Jacques Francois Shadow'=>'Jacques Francois Shadow','Jim Nightshade'=>'Jim Nightshade','Jockey One'=>'Jockey One','Jolly Lodger'=>'Jolly Lodger','Josefin Sans'=>'Josefin Sans','Josefin Slab'=>'Josefin Slab','Joti One'=>'Joti One','Judson'=>'Judson','Julee'=>'Julee','Julius Sans One'=>'Julius Sans One','Junge'=>'Junge','Jura'=>'Jura','Just Another Hand'=>'Just Another Hand','Just Me Again Down Here'=>'Just Me Again Down Here','Kameron'=>'Kameron','Karla'=>'Karla','Kaushan Script'=>'Kaushan Script','Kavoon'=>'Kavoon','Keania One'=>'Keania One','Kelly Slab'=>'Kelly Slab','Kenia'=>'Kenia','Khmer'=>'Khmer','Kite One'=>'Kite One','Knewave'=>'Knewave','Kotta One'=>'Kotta One','Koulen'=>'Koulen','Kranky'=>'Kranky','Kreon'=>'Kreon','Kristi'=>'Kristi','Krona One'=>'Krona One','La Belle Aurore'=>'La Belle Aurore','Lancelot'=>'Lancelot','Lato'=>'Lato','League Script'=>'League Script','Leckerli One'=>'Leckerli One','Ledger'=>'Ledger','Lekton'=>'Lekton','Lemon'=>'Lemon','Libre Baskerville'=>'Libre Baskerville','Life Savers'=>'Life Savers','Lilita One'=>'Lilita One','Lily Script One'=>'Lily Script One','Limelight'=>'Limelight','Linden Hill'=>'Linden Hill','Lobster'=>'Lobster','Lobster Two'=>'Lobster Two','Londrina Outline'=>'Londrina Outline','Londrina Shadow'=>'Londrina Shadow','Londrina Sketch'=>'Londrina Sketch','Londrina Solid'=>'Londrina Solid','Lora'=>'Lora','Love Ya Like A Sister'=>'Love Ya Like A Sister','Loved by the King'=>'Loved by the King','Lovers Quarrel'=>'Lovers Quarrel','Luckiest Guy'=>'Luckiest Guy','Lusitana'=>'Lusitana','Lustria'=>'Lustria','Macondo'=>'Macondo','Macondo Swash Caps'=>'Macondo Swash Caps','Magra'=>'Magra','Maiden Orange'=>'Maiden Orange','Mako'=>'Mako','Marcellus'=>'Marcellus','Marcellus SC'=>'Marcellus SC','Marck Script'=>'Marck Script','Margarine'=>'Margarine','Marko One'=>'Marko One','Marmelad'=>'Marmelad','Marvel'=>'Marvel','Mate'=>'Mate','Mate SC'=>'Mate SC','Maven Pro'=>'Maven Pro','McLaren'=>'McLaren','Meddon'=>'Meddon','MedievalSharp'=>'MedievalSharp','Medula One'=>'Medula One','Megrim'=>'Megrim','Meie Script'=>'Meie Script','Merienda'=>'Merienda','Merienda One'=>'Merienda One','Merriweather'=>'Merriweather','Merriweather Sans'=>'Merriweather Sans','Metal'=>'Metal','Metal Mania'=>'Metal Mania','Metamorphous'=>'Metamorphous','Metrophobic'=>'Metrophobic','Michroma'=>'Michroma','Milonga'=>'Milonga','Miltonian'=>'Miltonian','Miltonian Tattoo'=>'Miltonian Tattoo','Miniver'=>'Miniver','Miss Fajardose'=>'Miss Fajardose','Modern Antiqua'=>'Modern Antiqua','Molengo'=>'Molengo','Molle'=>'Molle','Monda'=>'Monda','Monofett'=>'Monofett','Monoton'=>'Monoton','Monsieur La Doulaise'=>'Monsieur La Doulaise','Montaga'=>'Montaga','Montez'=>'Montez','Montserrat'=>'Montserrat','Montserrat Alternates'=>'Montserrat Alternates','Montserrat Subrayada'=>'Montserrat Subrayada','Moul'=>'Moul','Moulpali'=>'Moulpali','Mountains of Christmas'=>'Mountains of Christmas','Mouse Memoirs'=>'Mouse Memoirs','Mr Bedfort'=>'Mr Bedfort','Mr Dafoe'=>'Mr Dafoe','Mr De Haviland'=>'Mr De Haviland','Mrs Saint Delafield'=>'Mrs Saint Delafield','Mrs Sheppards'=>'Mrs Sheppards','Muli'=>'Muli','Mystery Quest'=>'Mystery Quest','Neucha'=>'Neucha','Neuton'=>'Neuton','New Rocker'=>'New Rocker','News Cycle'=>'News Cycle','Niconne'=>'Niconne','Nixie One'=>'Nixie One','Nobile'=>'Nobile','Nokora'=>'Nokora','Norican'=>'Norican','Nosifer'=>'Nosifer','Nothing You Could Do'=>'Nothing You Could Do','Noticia Text'=>'Noticia Text','Noto Sans'=>'Noto Sans','Noto Serif'=>'Noto Serif','Nova Cut'=>'Nova Cut','Nova Flat'=>'Nova Flat','Nova Mono'=>'Nova Mono','Nova Oval'=>'Nova Oval','Nova Round'=>'Nova Round','Nova Script'=>'Nova Script','Nova Slim'=>'Nova Slim','Nova Square'=>'Nova Square','Numans'=>'Numans','Nunito'=>'Nunito','Odor Mean Chey'=>'Odor Mean Chey','Offside'=>'Offside','Old Standard TT'=>'Old Standard TT','Oldenburg'=>'Oldenburg','Oleo Script'=>'Oleo Script','Oleo Script Swash Caps'=>'Oleo Script Swash Caps','Open Sans'=>'Open Sans','Open Sans Condensed'=>'Open Sans Condensed','Oranienbaum'=>'Oranienbaum','Orbitron'=>'Orbitron','Oregano'=>'Oregano','Orienta'=>'Orienta','Original Surfer'=>'Original Surfer','Oswald'=>'Oswald','Over the Rainbow'=>'Over the Rainbow','Overlock'=>'Overlock','Overlock SC'=>'Overlock SC','Ovo'=>'Ovo','Oxygen'=>'Oxygen','Oxygen Mono'=>'Oxygen Mono','Pacifico'=>'Pacifico','Paprika'=>'Paprika','Parisienne'=>'Parisienne','Passero One'=>'Passero One','Passion One'=>'Passion One','Pathway Gothic One'=>'Pathway Gothic One','Patrick Hand'=>'Patrick Hand','Patrick Hand SC'=>'Patrick Hand SC','Patua One'=>'Patua One','Paytone One'=>'Paytone One','Peralta'=>'Peralta','Permanent Marker'=>'Permanent Marker','Petit Formal Script'=>'Petit Formal Script','Petrona'=>'Petrona','Philosopher'=>'Philosopher','Piedra'=>'Piedra','Pinyon Script'=>'Pinyon Script','Pirata One'=>'Pirata One','Plaster'=>'Plaster','Play'=>'Play','Playball'=>'Playball','Playfair Display'=>'Playfair Display','Playfair Display SC'=>'Playfair Display SC','Podkova'=>'Podkova','Poiret One'=>'Poiret One','Poller One'=>'Poller One','Poly'=>'Poly','Pompiere'=>'Pompiere','Pontano Sans'=>'Pontano Sans','Port Lligat Sans'=>'Port Lligat Sans','Port Lligat Slab'=>'Port Lligat Slab','Prata'=>'Prata','Preahvihear'=>'Preahvihear','Press Start 2P'=>'Press Start 2P','Princess Sofia'=>'Princess Sofia','Prociono'=>'Prociono','Prosto One'=>'Prosto One','PT Mono'=>'PT Mono','PT Sans'=>'PT Sans','PT Sans Caption'=>'PT Sans Caption','PT Sans Narrow'=>'PT Sans Narrow','PT Serif'=>'PT Serif','PT Serif Caption'=>'PT Serif Caption','Puritan'=>'Puritan','Purple Purse'=>'Purple Purse','Quando'=>'Quando','Quantico'=>'Quantico','Quattrocento'=>'Quattrocento','Quattrocento Sans'=>'Quattrocento Sans','Questrial'=>'Questrial','Quicksand'=>'Quicksand','Quintessential'=>'Quintessential','Qwigley'=>'Qwigley','Racing Sans One'=>'Racing Sans One','Radley'=>'Radley','Raleway'=>'Raleway','Raleway Dots'=>'Raleway Dots','Rambla'=>'Rambla','Rammetto One'=>'Rammetto One','Ranchers'=>'Ranchers','Rancho'=>'Rancho','Rationale'=>'Rationale','Redressed'=>'Redressed','Reenie Beanie'=>'Reenie Beanie','Revalia'=>'Revalia','Ribeye'=>'Ribeye','Ribeye Marrow'=>'Ribeye Marrow','Righteous'=>'Righteous','Risque'=>'Risque','Roboto'=>'Roboto','Roboto Condensed'=>'Roboto Condensed','Roboto Slab'=>'Roboto Slab','Rochester'=>'Rochester','Rock Salt'=>'Rock Salt','Rokkitt'=>'Rokkitt','Romanesco'=>'Romanesco','Ropa Sans'=>'Ropa Sans','Rosario'=>'Rosario','Rosarivo'=>'Rosarivo','Rouge Script'=>'Rouge Script','Ruda'=>'Ruda','Rufina'=>'Rufina','Ruge Boogie'=>'Ruge Boogie','Ruluko'=>'Ruluko','Rum Raisin'=>'Rum Raisin','Ruslan Display'=>'Ruslan Display','Russo One'=>'Russo One','Ruthie'=>'Ruthie','Rye'=>'Rye','Sacramento'=>'Sacramento','Sail'=>'Sail','Salsa'=>'Salsa','Sanchez'=>'Sanchez','Sancreek'=>'Sancreek','Sansita One'=>'Sansita One','Sarina'=>'Sarina','Satisfy'=>'Satisfy','Scada'=>'Scada','Schoolbell'=>'Schoolbell','Seaweed Script'=>'Seaweed Script','Sevillana'=>'Sevillana','Seymour One'=>'Seymour One','Shadows Into Light'=>'Shadows Into Light','Shadows Into Light Two'=>'Shadows Into Light Two','Shanti'=>'Shanti','Share'=>'Share','Share Tech'=>'Share Tech','Share Tech Mono'=>'Share Tech Mono','Shojumaru'=>'Shojumaru','Short Stack'=>'Short Stack','Siemreap'=>'Siemreap','Sigmar One'=>'Sigmar One','Signika'=>'Signika','Signika Negative'=>'Signika Negative','Simonetta'=>'Simonetta','Sintony'=>'Sintony','Sirin Stencil'=>'Sirin Stencil','Six Caps'=>'Six Caps','Skranji'=>'Skranji','Slackey'=>'Slackey','Smokum'=>'Smokum','Smythe'=>'Smythe','Sniglet'=>'Sniglet','Snippet'=>'Snippet','Snowburst One'=>'Snowburst One','Sofadi One'=>'Sofadi One','Sofia'=>'Sofia','Sonsie One'=>'Sonsie One','Sorts Mill Goudy'=>'Sorts Mill Goudy','Source Code Pro'=>'Source Code Pro','Source Sans Pro'=>'Source Sans Pro','Special Elite'=>'Special Elite','Spicy Rice'=>'Spicy Rice','Spinnaker'=>'Spinnaker','Spirax'=>'Spirax','Squada One'=>'Squada One','Stalemate'=>'Stalemate','Stalinist One'=>'Stalinist One','Stardos Stencil'=>'Stardos Stencil','Stint Ultra Condensed'=>'Stint Ultra Condensed','Stint Ultra Expanded'=>'Stint Ultra Expanded','Stoke'=>'Stoke','Strait'=>'Strait','Sue Ellen Francisco'=>'Sue Ellen Francisco','Sunshiney'=>'Sunshiney','Supermercado One'=>'Supermercado One','Suwannaphum'=>'Suwannaphum','Swanky and Moo Moo'=>'Swanky and Moo Moo','Syncopate'=>'Syncopate','Tangerine'=>'Tangerine','Taprom'=>'Taprom','Tauri'=>'Tauri','Telex'=>'Telex','Tenor Sans'=>'Tenor Sans','Text Me One'=>'Text Me One','The Girl Next Door'=>'The Girl Next Door','Tienne'=>'Tienne','Tinos'=>'Tinos','Titan One'=>'Titan One','Titillium Web'=>'Titillium Web','Trade Winds'=>'Trade Winds','Trocchi'=>'Trocchi','Trochut'=>'Trochut','Trykker'=>'Trykker','Tulpen One'=>'Tulpen One','Ubuntu'=>'Ubuntu','Ubuntu Condensed'=>'Ubuntu Condensed','Ubuntu Mono'=>'Ubuntu Mono','Ultra'=>'Ultra','Uncial Antiqua'=>'Uncial Antiqua','Underdog'=>'Underdog','Unica One'=>'Unica One','UnifrakturCook'=>'UnifrakturCook','UnifrakturMaguntia'=>'UnifrakturMaguntia','Unkempt'=>'Unkempt','Unlock'=>'Unlock','Unna'=>'Unna','Vampiro One'=>'Vampiro One','Varela'=>'Varela','Varela Round'=>'Varela Round','Vast Shadow'=>'Vast Shadow','Vibur'=>'Vibur','Vidaloka'=>'Vidaloka','Viga'=>'Viga','Voces'=>'Voces','Volkhov'=>'Volkhov','Vollkorn'=>'Vollkorn','Voltaire'=>'Voltaire','VT323'=>'VT323','Waiting for the Sunrise'=>'Waiting for the Sunrise','Wallpoet'=>'Wallpoet','Walter Turncoat'=>'Walter Turncoat','Warnes'=>'Warnes','Wellfleet'=>'Wellfleet','Wendy One'=>'Wendy One','Wire One'=>'Wire One','Yanone Kaffeesatz'=>'Yanone Kaffeesatz','Yellowtail'=>'Yellowtail','Yeseva One'=>'Yeseva One','Yesteryear'=>'Yesteryear','Zeyada'=>'Zeyada');
						
						if(!isset($typography_stored['google'])) $typography_stored['google'] = 'ABeeZee';
						
						foreach ($google_fonts as $i=>$google_font){
							$output .= '<option value="'. $i .'" ' . selected($typography_stored['google'], $i, false) . '>'. $google_font .'</option>';		
						}
						$output .= '</select>';
						$output .= '</div>';
						$output .= '<div class="select_wrapper typography-size typography-google-weight" original-title="Font weight">';
						$output .= '<select class="of-typography of-typography-gweight select" name="'.$value['id'].'[googlew]" id="'. $value['id'].'_googlew">';

						$google_fonts_weights = array('100'=>'100','200'=>'200','300'=>'300','400'=>'400','500'=>'500','600'=>'600','700'=>'700','800'=>'800','900'=>'900');

						if(!isset($typography_stored['googlew'])) $typography_stored['googlew'] = '400';

						foreach ($google_fonts_weights as $iw=>$google_fonts_weight){
							$output .= '<option value="'. $iw .'" ' . selected($typography_stored['googlew'], $iw, false) . '>'. $google_fonts_weight .'</option>';		
						}
						$output .= '</select>';
						$output .= '</div>';
						$output .= '<div class="sampletext" style="clear:both; padding:10px; background:white;">Grumpy wizards make toxic brew for the evil Queen and Jack.</div>';
					}
					/********************************/
					
				break;
				
				//border option
				case 'border':
						
					/* Border Width */
					$border_stored = $data[$value['id']];
					
					$output .= '<div class="select_wrapper border-width">';
					$output .= '<select class="of-border of-border-width select" name="'.$value['id'].'[width]" id="'. $value['id'].'_width">';
						for ($i = 0; $i < 21; $i++){ 
						$output .= '<option value="'. $i .'" ' . selected($border_stored['width'], $i, false) . '>'. $i .'</option>';				 }
					$output .= '</select></div>';
					
					/* Border Style */
					$output .= '<div class="select_wrapper border-style">';
					$output .= '<select class="of-border of-border-style select" name="'.$value['id'].'[style]" id="'. $value['id'].'_style">';
					
					$styles = array('none'=>'None',
									'solid'=>'Solid',
									'dashed'=>'Dashed',
									'dotted'=>'Dotted');
									
					foreach ($styles as $i=>$style){
						$output .= '<option value="'. $i .'" ' . selected($border_stored['style'], $i, false) . '>'. $style .'</option>';		
					}
					
					$output .= '</select></div>';
					
					/* Border Color */		
					$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div style="background-color: '.$border_stored['color'].'"></div></div>';
					$output .= '<input class="of-color of-border of-border-color" name="'.$value['id'].'[color]" id="'. $value['id'] .'_color" type="text" value="'. $border_stored['color'] .'" />';
					
				break;
				
				//images checkbox - use image as checkboxes
				case 'images':
				
					/***** Updated by Gtwebsite *****/
					$images_stored = isset($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					/********************************/

					$i = 0;
					
					foreach ($value['options'] as $key => $option) 
					{ 
					$i++;
			
						$checked = '';
						$selected = '';
						if(NULL!=checked($images_stored, $key, false)) {
							$checked = checked($images_stored, $key, false);
							$selected = 'of-radio-img-selected';  
						}
						$output .= '<span>';
						$output .= '<input type="radio" id="of-radio-img-' . $value['id'] . $i . '" class="checkbox of-radio-img-radio" value="'.$key.'" name="'.$value['id'].'" '.$checked.' />';
						$output .= '<div class="of-radio-img-label">'. $key .'</div>';
						$output .= '<img src="'.$option.'" alt="" class="of-radio-img-img '. $selected .'" onClick="document.getElementById(\'of-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
						$output .= '</span>';				
					}
					
				break;
				
				//info (for small intro box etc)
				case "info":
					$info_text = $value['std'];
					$output .= '<div class="of-info">'.$info_text.'</div>';
				break;
				
				//display a single image
				case "image":
					$src = $value['std'];
					$output .= '<img src="'.$src.'">';
				break;
				
				//tab heading
				case 'heading':
					if($counter >= 2){
					   $output .= '</div>'."\n";
					}
					$header_class = str_replace(' ','',strtolower($value['name']));
					$jquery_click_hook = str_replace(' ', '', strtolower($value['name']) );
					$jquery_click_hook = "of-option-" . $jquery_click_hook;
					$menu .= '<li class="'. $header_class .'"><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
					$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
				break;
				
				//drag & drop slide manager
				case 'slider':
					/***** Updated by Gtwebsite *****/
					if(!isset($data[$value['id']])) {
						$data[$value['id']] = '';
					}
					/********************************/
					$_id = strip_tags( strtolower($value['id']) );
					$int = '';
					$int = optionsframework_mlu_get_silentpost( $_id );
					$output .= '<div class="slider"><ul id="'.$value['id'].'" rel="'.$int.'">';
					$slides = $data[$value['id']];
					$count = count($slides);
					if(!isset($value['std'])) $value['std'] = '';
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::optionsframework_slider_function($value['id'],$value['std'],$oldorder,$order,$int);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::optionsframework_slider_function($value['id'],$value['std'],$oldorder,$order,$int);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button slide_add_button">Add New Slide</a></div>';
					
				break;
				
				//drag & drop block manager
				case 'sorter':
				
					$sortlists = isset($data[$value['id']]) && !empty($data[$value['id']]) ? $data[$value['id']] : $value['std'];
					
					$output .= '<div id="'.$value['id'].'" class="sorter">';
					
					
					if ($sortlists) {
					
						foreach ($sortlists as $group=>$sortlist) {
						
							$output .= '<ul id="'.$value['id'].'_'.$group.'" class="sortlist_'.$value['id'].'">';
							$output .= '<h3>'.$group.'</h3>';
							
							foreach ($sortlist as $key => $list) {
							
								$output .= '<input class="sorter-placebo" type="hidden" name="'.$value['id'].'['.$group.'][placebo]" value="placebo">';
									
								if ($key != "placebo") {
								
									$output .= '<li id="'.$key.'" class="sortee">';
									$output .= '<input class="position" type="hidden" name="'.$value['id'].'['.$group.']['.$key.']" value="'.$list.'">';
									$output .= $list;
									$output .= '</li>';
									
								}
								
							}
							
							$output .= '</ul>';
						}
					}
					
					$output .= '</div>';
				break;
				
				//background images option
				case 'tiles':
					
					$i = 0;
					$select_value = isset($data[$value['id']]) && !empty($data[$value['id']]) ? $data[$value['id']] : '';
					
					foreach ($value['options'] as $key => $option) 
					{ 
					$i++;
			
						$checked = '';
						$selected = '';
						if(NULL!=checked($select_value, $option, false)) {
							$checked = checked($select_value, $option, false);
							$selected = 'of-radio-tile-selected';  
						}
						$output .= '<span>';
						$output .= '<input type="radio" id="of-radio-tile-' . $value['id'] . $i . '" class="checkbox of-radio-tile-radio" value="'.$option.'" name="'.$value['id'].'" '.$checked.' />';
						$output .= '<div class="of-radio-tile-img '. $selected .'" style="background: url('.$option.')" onClick="document.getElementById(\'of-radio-tile-'. $value['id'] . $i.'\').checked = true;"></div>';
						$output .= '</span>';				
					}
					
				break;
				
				//backup and restore options data
				case 'backup':
				
					$instructions = $value['desc'];
					$backup = get_option(BACKUPS);
					
					if(!isset($backup['backup_log'])) {
						$log = 'No backups yet';
					} else {
						$log = $backup['backup_log'];
					}
					
					$output .= '<div class="backup-box">';
					$output .= '<div class="instructions">'.$instructions."\n";
					$output .= '<p><strong>'. __('Last Backup : ').'<span class="backup-log">'.$log.'</span></strong></p></div>'."\n";
					$output .= '<a href="#" id="of_backup_button" class="button" title="Backup Options">Backup Options</a>';
					$output .= '<a href="#" id="of_restore_button" class="button" title="Restore Options">Restore Options</a>';
					$output .= '</div>';
				
				break;
				
				//export or import data between different installs
				case 'transfer':
				
					$instructions = $value['desc'];
					$output .= '<textarea id="export_data" rows="8">'.base64_encode(serialize($data)) /* 100% safe - ignore theme check nag */ .'</textarea>'."\n";
					$output .= '<a href="#" id="of_import_button" class="button" title="Restore Options">Import Options</a>';
				
				break;
			
				/***** Added by Gtwebsite *****/
				//drag & drop testimonial manager
				case 'testimonial':
					if(!isset($data[$value['id']])) {
						$data[$value['id']] = '';
					}
					$_id = strip_tags( strtolower($value['id']) );
					$int = '';
					$int = optionsframework_mlu_get_silentpost( $_id );
					$output .= '<div class="slider"><ul id="'.$value['id'].'" rel="'.$int.'">';
					$testimonials = $data[$value['id']];
					$count = count($testimonials);
					if(!isset($value['std'])) $value['std'] = '';
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::optionsframework_testimonial_function($value['id'],$value['std'],$oldorder,$order,$int);
					} else {
						$i = 0;
						foreach ($testimonials as $testimonial) {
							$oldorder = $testimonial['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::optionsframework_testimonial_function($value['id'],$value['std'],$oldorder,$order,$int);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button testimonial_add_button">'. __( 'Add New Testimonial','cyon' ) .'</a></div>';

				break;

				/***** Added by Gtwebsite *****/
				//drag & drop testimonial manager
				case 'team':
					if(!isset($data[$value['id']])) {
						$data[$value['id']] = '';
					}
					$_id = strip_tags( strtolower($value['id']) );
					$int = '';
					$int = optionsframework_mlu_get_silentpost( $_id );
					$output .= '<div class="slider"><ul id="'.$value['id'].'" rel="'.$int.'">';
					$team = $data[$value['id']];
					$count = count($team);
					if(!isset($value['std'])) $value['std'] = '';
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::optionsframework_team_function($value['id'],$value['std'],$oldorder,$order,$int);
					} else {
						$i = 0;
						foreach ($team as $member) {
							$oldorder = $member['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::optionsframework_team_function($value['id'],$value['std'],$oldorder,$order,$int);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button team_add_button">'. __( 'Add New Member','cyon' ) .'</a></div>';

				break;

				case 'newslider':
					/***** Updated by Gtwebsite *****/
					if(!isset($data[$value['id']])) {
						$data[$value['id']] = '';
					}
					/********************************/
					$_id = strip_tags( strtolower($value['id']) );
					$int = '';
					$int = optionsframework_mlu_get_silentpost( $_id );
					$output .= '<div class="slider"><ul id="'.$value['id'].'" rel="'.$int.'">';
					$slides = $data[$value['id']];
					$count = count($slides);
					if(!isset($value['std'])) $value['std'] = '';
					if ($count < 2) {
						$oldorder = 1;
						$order = 1;
						$output .= Options_Machine::optionsframework_newslider_function($value['id'],$value['std'],$oldorder,$order,$int);
					} else {
						$i = 0;
						foreach ($slides as $slide) {
							$oldorder = $slide['order'];
							$i++;
							$order = $i;
							$output .= Options_Machine::optionsframework_newslider_function($value['id'],$value['std'],$oldorder,$order,$int);
						}
					}			
					$output .= '</ul>';
					$output .= '<a href="#" class="button slide_add_button">Add New Slide</a></div>';

				break;

			}
			
			//description of each option
			/***** Updated by Gtwebsite *****/
			if ( $value['type'] != 'heading' && $value['type'] != 'group_begin' && $value['type'] != 'group_end' ) { 
			// if ( $value['type'] != 'heading' ) { 
				/***** Updated by Gtwebsite *****/
				if(!isset($value['desc']) || $value['type'] == 'checkbox'){ 
				//if(!isset($value['desc'])){ 
					$explain_value = ''; 
				} else{ 
					$explain_value = '<div class="explain">'. $value['desc'] .'</div>'."\n"; 
				} 
				$output .= '</div>'.$explain_value."\n";
				$output .= '<div class="clear"> </div></div></div>'."\n";
			}
		}
		
    	$output .= '</div>';
		
	    return array($output,$menu,$defaults);
	    
	}


	/**
	 * Ajax image uploader - supports various types of image types
	 *
	 * @uses get_option()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function optionsframework_uploader_function($id,$std,$mod){
	
	    $data =get_option(OPTIONS);
		
		$uploader = '';
	    $upload = $data[$id];
		$hide = '';
		
		if ($mod == "min") {$hide ='hide';}
		
	    if ( $upload != "") { $val = $upload; } else {$val = $std;}
	    
		$uploader .= '<input class="'.$hide.' upload of-input" name="'. $id .'" id="'. $id .'_upload" value="'. $val .'" />';	
		
		$uploader .= '<div class="upload_button_div"><span class="button image_upload_button" id="'.$id.'">'._('Upload').'</span>';
		
		if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
		$uploader .= '<span class="button image_reset_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
		$uploader .='</div>' . "\n";
	    $uploader .= '<div class="clear"></div>' . "\n";
		if(!empty($upload)){
			$uploader .= '<div class="screenshot">';
	    	$uploader .= '<a class="of-uploaded-image" href="'. $upload . '">';
	    	$uploader .= '<img class="of-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
	    	$uploader .= '</a>';
			$uploader .= '</div>';
			}
		$uploader .= '<div class="clear"></div>' . "\n"; 
	
		return $uploader;
	
	}

	/**
	 * Native media library uploader
	 *
	 * @uses get_option()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return string
	 */

	/***** Updated by Gtwebsite *****/
	// public static function optionsframework_media_uploader_function($id,$std,$int,$mod){
	public static function optionsframework_media_uploader_function($id,$std,$int,$mod,$placeholder){
	/********************************/
	
	    $data =get_option(OPTIONS);
		
		$uploader = '';
	    $upload = isset($data[$id]) ? $data[$id] : '';
		$hide = '';
		
		if ($mod == "min") {$hide ='hide';}
		
	    if ( $upload != "") { $val = $upload; } else {$val = $std;}
	    
		/***** Updated by Gtwebsite *****/
		$uploader .= '<input class="'.$hide.' upload of-input" name="'. $id .'" id="'. $id .'_upload" value="'. $val .'" placeholder="'. $placeholder .'" />';	
		// $uploader .= '<input class="'.$hide.' upload of-input" name="'. $id .'" id="'. $id .'_upload" value="'. $val .'" />';	
		/********************************/
		
		$uploader .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'" rel="' . $int . '">Upload</span>';
		
		if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
		$uploader .= '<span class="button mlu_remove_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
		$uploader .='</div>' . "\n";
		$uploader .= '<div class="screenshot">';
		if(!empty($upload)){	
	    	$uploader .= '<a class="of-uploaded-image" href="'. $upload . '">';
	    	$uploader .= '<img class="of-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
	    	$uploader .= '</a>';			
			}
		$uploader .= '</div>';
		$uploader .= '<div class="clear"></div>' . "\n"; 
	
		return $uploader;
		
	}

	/**
	 * Drag and drop slides manager
	 *
	 * @uses get_option()
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function optionsframework_slider_function($id,$std,$oldorder,$order,$int){
	
	    $data = get_option(OPTIONS);
		
		if(!isset($data[$id])) $data[$id] = '';

		$slider = '';
		$slide = array();
	    $slide = $data[$id];
		
	    if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		
		//initialize all vars
		$slidevars = array('title','url','link','description');
		
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		
		//begin slider interface	
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>Slide '.$order.'</strong>';
		}
		
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
	
		$slider .= '<a class="slide_edit_button" href="#">Edit</a></div>';
		
		$slider .= '<div class="slide_body">';
		
		$slider .= '<label>Title</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';
		
		$slider .= '<label>Image URL</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][url]" id="'. $id .'_'.$order .'_slide_url" value="'. $val['url'] .'" />';
		
		$slider .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'_'.$order .'" rel="' . $int . '">Upload</span>';
		
		if(!empty($val['url'])) {$hide = '';} else { $hide = 'hide';}
		$slider .= '<span class="button mlu_remove_button '. $hide.'" id="reset_'. $id .'_'.$order .'" title="' . $id . '_'.$order .'">Remove</span>';
		$slider .='</div>' . "\n";
		$slider .= '<div class="screenshot">';
		if(!empty($val['url'])){
			
	    	$slider .= '<a class="of-uploaded-image" href="'. $val['url'] . '">';
	    	$slider .= '<img class="of-option-image" id="image_'.$id.'_'.$order .'" src="'.$val['url'].'" alt="" />';
	    	$slider .= '</a>';
			
			}
		$slider .= '</div>';	
		$slider .= '<label>Link URL (optional)</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][link]" id="'. $id .'_'.$order .'_slide_link" value="'. $val['link'] .'" />';
		
		$slider .= '<label>Description (optional)</label>';
		$slider .= '<textarea class="slide of-input" name="'. $id .'['.$order.'][description]" id="'. $id .'_'.$order .'_slide_description" cols="8" rows="8">'.stripslashes($val['description']).'</textarea>';
	
		$slider .= '<a class="slide_delete_button" href="#">Delete</a>';
	    $slider .= '<div class="clear"></div>' . "\n";
	
		$slider .= '</div>';
		$slider .= '</li>';
	
		return $slider;
		
	}
	
	/**
	 * Drag and drop testimonial manager
	 *
	 * @uses get_option()
	 *
	 * @return string
	 */
	public static function optionsframework_testimonial_function($id,$std,$oldorder,$order,$int){
	
	    $data = get_option(OPTIONS);
		
		if(!isset($data[$id])) $data[$id] = '';

		$slider = '';
		$slide = array();
	    $slide = $data[$id];
		
	    if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		
		//initialize all vars
		$slidevars = array('title','url','link','description');
		
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		
		//begin slider interface	
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>'.__( 'Testimonial', 'cyon' ).' '.$order.'</strong>';
		}
		
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
	
		$slider .= '<a class="slide_edit_button" href="#">'.__( 'Edit', 'cyon' ).'</a></div>';
		
		$slider .= '<div class="slide_body">';
		
		$slider .= '<label>'.__( 'Name', 'cyon' ).'</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';
		
		$slider .= '<label>'.__( 'Title / Company', 'cyon' ).'</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][company]" id="'. $id .'_'.$order .'_slide_company" value="'. $val['company'] .'" />';

		$slider .= '<label>'.__( 'Photo', 'cyon' ).'</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][url]" id="'. $id .'_'.$order .'_slide_url" value="'. $val['url'] .'" />';
		
		$slider .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'_'.$order .'" rel="' . $int . '">'.__( 'Upload', 'cyon' ).'</span>';
		
		if(!empty($val['url'])) {$hide = '';} else { $hide = 'hide';}
		$slider .= '<span class="button mlu_remove_button '. $hide.'" id="reset_'. $id .'_'.$order .'" title="' . $id . '_'.$order .'">'.__( 'Remove', 'cyon' ).'</span>';
		$slider .='</div>' . "\n";
		$slider .= '<div class="screenshot">';
		if(!empty($val['url'])){
			
	    	$slider .= '<a class="of-uploaded-image" href="'. $val['url'] . '">';
	    	$slider .= '<img class="of-option-image" id="image_'.$id.'_'.$order .'" src="'.$val['url'].'" alt="" />';
	    	$slider .= '</a>';
			
			}
		$slider .= '</div>';	
		
		$slider .= '<label>Text</label>';
		$slider .= '<textarea class="slide of-input" name="'. $id .'['.$order.'][description]" id="'. $id .'_'.$order .'_slide_description" cols="8" rows="8">'.stripslashes($val['description']).'</textarea>';
	
		$slider .= '<a class="slide_delete_button" href="#">'.__( 'Delete', 'cyon' ).'</a>';
	    $slider .= '<div class="clear"></div>' . "\n";
	
		$slider .= '</div>';
		$slider .= '</li>';
	
		return $slider;
		
	}

	/**
	 * Drag and drop team manager
	 *
	 * @uses get_option()
	 *
	 * @return string
	 */
	public static function optionsframework_team_function($id,$std,$oldorder,$order,$int){
	
	    $data = get_option(OPTIONS);
		
		if(!isset($data[$id])) $data[$id] = '';

		$slider = '';
		$slide = array();
	    $slide = $data[$id];
		
	    if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		
		//initialize all vars
		$slidevars = array('title','url','link','description');
		
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		
		//begin slider interface	
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>'.__( 'Member', 'cyon' ).' '.$order.'</strong>';
		}
		
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
	
		$slider .= '<a class="slide_edit_button" href="#">'.__( 'Edit', 'cyon' ).'</a></div>';
		
		$slider .= '<div class="slide_body">';
		
		$slider .= '<label>'.__( 'Name', 'cyon' ).'</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';
		
		$slider .= '<label>'.__( 'Title / Job', 'cyon' ).'</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][job]" id="'. $id .'_'.$order .'_slide_job" value="'. $val['job'] .'" />';

		$slider .= '<label>'.__( 'Phone Number', 'cyon' ).'</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][phone]" id="'. $id .'_'.$order .'_slide_phone" value="'. $val['phone'] .'" />';

		$slider .= '<label>'.__( 'Photo', 'cyon' ).'</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][url]" id="'. $id .'_'.$order .'_slide_url" value="'. $val['url'] .'" />';
		
		$slider .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'_'.$order .'" rel="' . $int . '">'.__( 'Upload', 'cyon' ).'</span>';
		
		if(!empty($val['url'])) {$hide = '';} else { $hide = 'hide';}
		$slider .= '<span class="button mlu_remove_button '. $hide.'" id="reset_'. $id .'_'.$order .'" title="' . $id . '_'.$order .'">'.__( 'Remove', 'cyon' ).'</span>';
		$slider .='</div>' . "\n";
		$slider .= '<div class="screenshot">';
		if(!empty($val['url'])){
			
	    	$slider .= '<a class="of-uploaded-image" href="'. $val['url'] . '">';
	    	$slider .= '<img class="of-option-image" id="image_'.$id.'_'.$order .'" src="'.$val['url'].'" alt="" />';
	    	$slider .= '</a>';
			
			}
		$slider .= '</div>';	
		
		$slider .= '<label>Text</label>';
		$slider .= '<textarea class="slide of-input" name="'. $id .'['.$order.'][description]" id="'. $id .'_'.$order .'_slide_description" cols="8" rows="8">'.stripslashes($val['description']).'</textarea>';
	
		$slider .= '<label>'.__( 'Email', 'cyon' ).'</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][email]" id="'. $id .'_'.$order .'_slide_email" value="'. $val['email'] .'" />';

		$slider .= '<label>'.__( 'Facebook', 'cyon' ).' URL</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][facebook]" id="'. $id .'_'.$order .'_slide_facebook" value="'. $val['facebook'] .'" />';

		$slider .= '<label>'.__( 'Twitter', 'cyon' ).' URL</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][twitter]" id="'. $id .'_'.$order .'_slide_twitter" value="'. $val['twitter'] .'" />';

		$slider .= '<label>'.__( 'Google Plus', 'cyon' ).' URL</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][g_plus]" id="'. $id .'_'.$order .'_slide_g_plus" value="'. $val['g_plus'] .'" />';

		$slider .= '<label>'.__( 'LinkedIn', 'cyon' ).' URL</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][linkedin]" id="'. $id .'_'.$order .'_slide_linkedin" value="'. $val['linkedin'] .'" />';

		$slider .= '<a class="slide_delete_button" href="#">'.__( 'Delete', 'cyon' ).'</a>';
	    $slider .= '<div class="clear"></div>' . "\n";
	
		$slider .= '</div>';
		$slider .= '</li>';
	
		return $slider;
		
	}
	
	public static function optionsframework_newslider_function($id,$std,$oldorder,$order,$int){
	
	    $data = get_option(OPTIONS);
		
		if(!isset($data[$id])) $data[$id] = '';

		$slider = '';
		$slide = array();
	    $slide = $data[$id];
		
	    if (isset($slide[$oldorder])) { $val = $slide[$oldorder]; } else {$val = $std;}
		
		//initialize all vars
		$slidevars = array('title','url','link','description');
		
		foreach ($slidevars as $slidevar) {
			if (!isset($val[$slidevar])) {
				$val[$slidevar] = '';
			}
		}
		
		//begin slider interface	
		if (!empty($val['title'])) {
			$slider .= '<li><div class="slide_header"><strong>'.stripslashes($val['title']).'</strong>';
		} else {
			$slider .= '<li><div class="slide_header"><strong>Slide '.$order.'</strong>';
		}
		
		$slider .= '<input type="hidden" class="slide of-input order" name="'. $id .'['.$order.'][order]" id="'. $id.'_'.$order .'_slide_order" value="'.$order.'" />';
	
		$slider .= '<a class="slide_edit_button" href="#">Edit</a></div>';
		
		$slider .= '<div class="slide_body">';
		
		$slider .= '<label>Title</label>';
		$slider .= '<input class="slide of-input of-slider-title" name="'. $id .'['.$order.'][title]" id="'. $id .'_'.$order .'_slide_title" value="'. stripslashes($val['title']) .'" />';
		
		$slider .= '<label>Image URL</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][url]" id="'. $id .'_'.$order .'_slide_url" value="'. $val['url'] .'" />';
		
		$slider .= '<div class="upload_button_div"><span class="button media_upload_button" id="'.$id.'_'.$order .'" rel="' . $int . '">Upload</span>';
		
		if(!empty($val['url'])) {$hide = '';} else { $hide = 'hide';}
		$slider .= '<span class="button mlu_remove_button '. $hide.'" id="reset_'. $id .'_'.$order .'" title="' . $id . '_'.$order .'">Remove</span>';
		$slider .='</div>' . "\n";
		$slider .= '<div class="screenshot">';
		if(!empty($val['url'])){
			
	    	$slider .= '<a class="of-uploaded-image" href="'. $val['url'] . '">';
	    	$slider .= '<img class="of-option-image" id="image_'.$id.'_'.$order .'" src="'.$val['url'].'" alt="" />';
	    	$slider .= '</a>';
			
			}
		$slider .= '</div>';	
		$slider .= '<label>Link URL (optional)</label>';
		$slider .= '<input class="slide of-input" name="'. $id .'['.$order.'][link]" id="'. $id .'_'.$order .'_slide_link" value="'. $val['link'] .'" />';
		
		$slider .= '<label>Description (optional)</label>';
		$slider .= '<textarea class="slide of-input" name="'. $id .'['.$order.'][description]" id="'. $id .'_'.$order .'_slide_description" cols="8" rows="8">'.stripslashes($val['description']).'</textarea>';
	
		$slider .= '<a class="slide_delete_button" href="#">Delete</a>';
	    $slider .= '<div class="clear"></div>' . "\n";
	
		$slider .= '</div>';
		$slider .= '</li>';
	
		return $slider;
		
	}

}//end Options Machine class

?>