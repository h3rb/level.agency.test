<?php

// Execute in term with php -f answer.php

// I have embellished the test a little bit by creating an ASCII 
// console viewport, just to keep it interesting and express myself.

// Note that the maximum width dimension is 100, but you are better off with 50 unless you have a wide term
// There is no maximum height dimension but the minimum value for height is 4.
// You can enter values that result in undesired effects, though, as I did protect against negative numbers etc.

function decipher( $a ) {
 $a=str_replace("+","\n",$a);
 $a=str_replace("#"," ",$a);
 return $a;
}

function is_number( $a ) {
 $b=str_split($a);
 foreach ($b as $c)switch($c){case '0':case '1':case '2':case '3': case '4':case '5':case '6':case '7':case '8':case '9':break;default:return FALSE;break;}
 return TRUE;
}

class rover_console {
 
 var $w, $h, $rov_x, $rov_y, $rov_dir, $pseudo, $travelled; 
 
 public function __construct() {
  $this->setup();
  $this->loop();
  $this->gameend();
 }
 

 private function set_size( $w, $h ) {
  $this->w=$w;
  $this->h=$h;
  $this->pseudo=$w*$h+123897;
  echo 'Size of plateau: '.$this->w.' by '.$this->h.PHP_EOL;
 }

 private function draw_top() {
  echo substr(',.-._.^.-.^--_.-.---.--.___.--^-._.-^.-^-._.-^-^.-._,.-._.^.-.^--_.-.---.--.___.--^-._.-^.-^-._.-^-^.-._',0,$this->w).PHP_EOL;
 }

 private function draw_side( $y, $right=0 ) {
  $arr=array( '|',')','(', '<', '>' );
  echo $arr[($this->pseudo+$y+$right)%count($arr)];
 }
 
 private function draw_fill( $seed ) {
  $arr=array( ' ','.','o', 'O', ' ', ',', '\'', '`', ' ', ' ', ' ', ' ', ' ' );
  echo $arr[($this->pseudo+$seed)%count($arr)];
 }

 private function draw_bottom() {
  echo substr('__.--^-._.-^.-^-._.-^-^.-._,.-._.^.-.^--_.-.---.--.,__.--^-._.-^.-^-._.-^-^.-._,.-._.^.-.^--_.-.---.--._',0,$this->w).PHP_EOL;
 }
 
 private function rover_heading() {
  switch ( $this->rov_dir ) {
   case 'N': return "North"; break;
   case 'E': return "East"; break;
   case 'W': return "West"; break;
   case 'S': return "South"; break;
   default: return "unknown!"; break;
  }
 }
 
 private function draw_controls( $idx ) {
  switch ( $idx ) {
   case 0: echo " ____________________"; break;
   case 1: echo '| N A S A MARS ROVER |'; break;
   case 2: echo '|'.str_pad(("GPS: ".$this->rov_x.','.$this->rov_y),20).'|'; break;
   case 3: echo '|'.str_pad("Heading: ",20).'|'; break;
   case 4: echo '|'.str_pad($this->rover_heading(),20).'|'; break;
   case 5: echo '|____________________|'; break;
   default:break;
  }
  echo PHP_EOL;
 }
 
 private function on_plateau() {
  if ( $this->rov_x < 0 || $this->rov_y < 0 || $this->rov_x >= $this->w || $this->rov_y >= $this->h ) return FALSE;
  return TRUE;
 }
 
 private function draw_rover() {
  switch ( $this->rov_dir ) {
   case 'N': echo '^'; break;
   case 'S': echo 'V'; break;
   case 'E': echo '>'; break;
   case 'W': echo '<'; break;
   default: echo '?'; break;
  }
 }
 
 private function render() {
  $this->draw_top();
  for ( $i=$this->h-1; $i >= 0; $i-- ) {
   $coord=$this->h-1-$i;
   $this->draw_side($i);
   if ( $i === $this->rov_y ) { 
    for ( $j=0; $j<$this->rov_x; $j++ ) $this->draw_fill($i+$j);  
    $this->draw_rover(); $j++;
    for ( ; $j<$this->w; $j++ ) $this->draw_fill($i+$j);  
   }
   $this->draw_side($i,1);
   $this->draw_controls($coord);
   echo PHP_EOL;
  }
  $this->draw_bottom();
 }
 
 private function rover_left() {
  switch ( $this->rov_dir ) {
   case 'N': $this->rov_dir='W'; break;
   case 'S': $this->rov_dir='E'; break;
   case 'E': $this->rov_dir='N'; break;
   case 'W': $this->rov_dir='S'; break;
  }
 }
 
 private function rover_right() {
  switch ( $this->rov_dir ) {
   case 'N': $this->rov_dir='E'; break;
   case 'S': $this->rov_dir='W'; break;
   case 'E': $this->rov_dir='S'; break;
   case 'W': $this->rov_dir='N'; break;
  }  
 }
 
 private function rover_forward() {
  switch ( $this->rov_dir ) {
   case 'N': $this->rov_y+=1; break;
   case 'E': $this->rov_x+=1; break;
   case 'W': $this->rov_x-=1; break;
   case 'S': $this->rov_y-=1; break;
  }
 }
 
 private function process_movement($cmds) {
  $cmds=strtoupper(str_replace(" ",'',$cmds));
  $parts=str_split($cmds);
  $valid=TRUE;
  foreach ( $parts as $idx=>$cmd ) {
   switch ( $cmd ) { case 'L': case 'M': case 'R': break; default: $valid=FALSE; break; }
  }
  if ( $valid === FALSE ) {
   echo 'Rover rejected an invalid command sequence.'.PHP_EOL;
   return;
  }
  foreach ( $parts as $idx=>$cmd ) {
   switch ( $cmd ) {
    case 'L': $this->rover_left(); break;
    case 'M': $this->rover_forward(); break;
    case 'R': $this->rover_right(); break;
   }
  }
 }
 
 private function command_prompt() { 
  $line = trim(readline("Send Command (?=help): "));
  if ( strlen($line) === 0 ) return;
  $parts=explode(" ",$line);
  if ( count($parts) < 1 ) return;
  if ( $parts[0] == '?' ) {  
   echo 'Expecting a movement command series.
   The movement commands is made of a string consisting of "L", "R", "M".
   L = LEFT
   R = RIGHT
   M = MOVE FORWARD
   
   Other commands:
   q or Q to terminate communication with the rover.
   ? for help
';
   return true;
  }
  if ( $parts[0] == 'q' || $parts['0'] == 'Q' ) return false;
  $this->process_movement($line);
  if ( !$this->on_plateau() ) $this->gameover();
  return true;
 }
 
 private function setup() {
  $this->travelled=0;
  while ( 1 ) {
   $line = readline("Plateau Dimensions: ");
   $parts=explode(" ",$line);
   if ( count($parts) != 2 || !is_number($parts[0]) || !is_number($parts[1]) || intval($parts[0]) <= 0 || intval($parts[1]) <= 0 ) {
    echo 'Input error! Expecting X<space>Y'.PHP_EOL;
    continue;
   }
   $this->set_size(intval($parts[0]),intval($parts[1]));
   break;
  }
  while ( 1 ) {
   $line = readline("Rover Calibration: ");
   $parts=explode(" ",strtoupper($line));
   if ( isset($parts[2]) ) {
    $parts[2]=strtoupper($parts[2]);
    $invalid=FALSE;
    switch ( $parts[2] ) { case 'N': case 'E': case 'S': case 'W'; break; default: $invalid=TRUE; break; }
    if ( $invalid === TRUE ) {
     echo 'Input error! Expecting X<space>Y<space>N|S|E|W'.PHP_EOL;
     continue;
    }
   }
   if ( count($parts) != 3 || !is_number($parts[0]) || !is_number($parts[1]) ) {
    echo 'Input error! Expecting X<space>Y<space>N|S|E|W'.PHP_EOL;
    continue;
   }
   $this->rov_x=intval($parts[0]);
   $this->rov_y=intval($parts[1]);
   $this->rov_dir=$parts[2];
   if ( !$this->on_plateau() ) $this->gameover();
   break;
  }
 } 
 
 private function loop() {
  $this->render();
  while ( $this->command_prompt() ) {
   echo 'After the 40 minute round trip, the console updates...'.PHP_EOL;
   $this->render();
  }
 }
 
 private function gameover() { 
  echo decipher('###_________####__##_________###____#_####____________#+##/#____/###|##/##|/##/#____/##/#__#\#|##/#/#____/#__#\+#/#/#__/#/|#|#/#/|_/#/#__/####/#/#/#/#|#/#/#__/#/#/_/#/+/#/_/#/#___#|/#/##/#/#/___###/#/_/#/|#|/#/#/___/#_,#_/#+\____/_/##|_/_/##/_/_____/###\____/#|___/_____/_/#|_|##+#######################################################+YOU#DESTROYED#A#FIFTEEN#MILLION#DOLLAR+ROVER#ON#A#TWO#HUNDRED#MILLION#DOLLAR#MISSION!+');
  echo 'The rover travelled '.$this->travelled.' Martian units this session.'.PHP_EOL;  
  die();
 }
 
 private function gameend() {
  echo 'The last picture you see from the rover\'s camera is:'.PHP_EOL;
  echo decipher('#########__.,,------.._+######,`"###_######_###"`.+#####/.__,#._##-=-#_"`####Y+####(.____.-.`######""`###j+#####VvvvvvV`.Y,.####_.,-`#######,#####,#####,+########Y####||,###`"\#########,/####,/####./+########|###,`##,#####`-..,`_,`/___,`/###,`/###,+###..##,;,,`,-`"\,`##,##.#####`#####`#""`#`--,/####..#..+#,`.#`.`---`#####`,#/##,#Y#-=-####,`###,###,.#.`-..||_||#..+ff\\`.#`._########/f#,`j#j#,#,`#,###,#f#,##\=\#Y###||#||`||_..+l`#\`#`.`."`-..,-`#j##/./#/,#,#/#,#/#/l#\###\=\l###||#``#||#||...+#`##`###`-._#`-.,-/#,`#/`"/-/-/-/-"```"`.`.##``.\--``--..``_``#||#,+############"`-_,`,##,`##f####,###/######`._####``._#####,##`-.``//#########,+##########,-"``#_.,-`####l_,-`_,,`##########"`-._#.#"`.#/|#####`.`\#,#######|+########,`,.,-`"##########\=)#,`-.#########,####`-`._`.V#|#######\#//#..#.#/j+########|f\\###############`._#)-."`.#####/|#########`.|#|########`.`-||-\\/+########l`#\`#################"`._###"`--`#j##########j`#j##########`-`---`+#########`##`#####################"`,-##,`/#######,-`"##/+#################################,`",__,-`#######/,,#,-`+#################################Vvv`############VVv`+');
  echo 'TTFN,BYE!'.PHP_EOL;
 }
  
};

$rover_game=new rover_console;
echo 'The rover travelled '.$rover_game->travelled.' Martian units this session.'.PHP_EOL;
