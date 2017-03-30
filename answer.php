<?php

// Execute in term with php -f answer.php

// I have embellished the test a little bit by creating an ASCII 
// console viewport, just to keep it interesting and express myself.

// Note that the maximum width dimension is 100, but you are better off with 50 unless you have a wide term
// There is no maximum height dimension but the minimum value for height is 4.

define('ASCII_FUN','');

function decipher( $a ) {
 $a=str_replace("+","\n",$a);
 $a=str_replace("#"," ",$a);
 return $a;
}

class rover_console {

 var $w, $h, $rov_x, $rov_y, $rov_dir, $pseudo, $travelled;

 function draw_top() {
  echo substr(',.-._.^.-.^--_.-.---.--.___.--^-._.-^.-^-._.-^-^.-._,.-._.^.-.^--_.-.---.--.___.--^-._.-^.-^-._.-^-^.-._',0,$this->w).PHP_EOL;
 }

 function draw_side( $y, $right=0 ) {
  $arr=array( '|',')','(', '<', '>' );
  return $arr[($this->pseudo+$y+$right)%count($arr)];
 }
 
 function draw_fill( $seed) {
  $arr=array( ' ','.','o', 'O', ' ', ',', '\'', '`', ' ', ' ', ' ', ' ', ' ' );
  return $arr[($this->pseudo+$seed)%count($arr)];
 }

 function draw_bottom() {
  echo substr('__.--^-._.-^.-^-._.-^-^.-._,.-._.^.-.^--_.-.---.--.,__.--^-._.-^.-^-._.-^-^.-._,.-._.^.-.^--_.-.---.--._',0,$this->w).PHP_EOL;
 }
 
 function rover_heading() {
  switch ( $this->rov_dir ) {
  }
 }
 
 function draw_controls( $idx ) {
  switch ( $idx ) {
   case 0: echo " ____________________"; break;
   case 1: echo '| N A S A MARS ROVER |';
   echo 2: echo '|'.str_pad("GPS: ".$this->rov_x.','.$this->rov_y,20).'|';
   case 3: echo '|'.str_pad("Heading: ",20).'|';
   case 4: echo '|'.str_pad($this->rover_heading(),20).'|';
   case 5: echo '|____________________|'; break;
   default:break;
  }
  echo PHP_EOL;
 }

 function set_size( $w, $h ) {
  $this->w=$w;
  $this->h=$h;
  $this->pseudo=$w*$h+123897;
 }
 
 function render() {
  $this->draw_top();
  for ( $i=0; $i<$this->h; $i++ ) {
   $coord=$this->h-1-$i;
   $this->draw_side($i);
   if ( $coord === $this->rov_y ) { 
    for ( $j=0; $j<$this->rov_x; $j++ ) $this->draw_fill($i+$j);  
   }
   $this->draw_side($i,1);
   $this->draw_controls($i);
   echo PHP_EOL;
  }
  $this->draw_bottom();
 }
 
 function command_prompt() { 
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
  }
  if ( $parts[0] == 'q' || $parts['0'] == 'Q' ) return false;
  $this->process_movement($line);
  return true;
 }
 
 function setup() {
  $this->travelled=0;
  while ( 1 ) {
   $line = readline("Plateau Dimensions: ");
   $parts=explode(" ",$line);
   if ( count($parts) != 2 || !is_number($parts[0]) || !is_number($parts[1]) ) {
    echo 'Input error! Expecting X<space>Y'.PHP_EOL;
    continue;
   }
   $this->set_size(intval($parts[0]),intval($parts[1]));
   break;
  }
  while ( 1 ) {
   $line = readline("Rover Calibration: ");
   $parts=explode(" ",$line);
   if ( isset($parts[2]) ) $parts[2]=strtoupper($parts[2]);
   if ( count($parts) != 3 || !is_number($parts[0]) || !is_number($parts[1])  ) {
    echo 'Input error! Expecting X<space>Y<space>N|S|E|W'.PHP_EOL;
    continue;
   }
   $this->set_size(intval($parts[0]),intval($parts[1]));
   break;
  }
 } 
 
 function loop() {
  $this->render();
  while ( $this->command_prompt() ) {
   echo 'After the 40 minute round trip, the console updates...'.PHP_EOL;
   $this->render();
  }
 }
 
 public function __construct() {
  $this->setup();
  $this->loop();
  $this->end();
 }
 
 public function gameover() { 
  echo decipher('###_________####__##_________###____#_####____________#+##/#____/###|##/##|/##/#____/##/#__#\#|##/#/#____/#__#\+#/#/#__/#/|#|#/#/|_/#/#__/####/#/#/#/#|#/#/#__/#/#/_/#/+/#/_/#/#___#|/#/##/#/#/___###/#/_/#/|#|/#/#/___/#_,#_/#+\____/_/##|_/_/##/_/_____/###\____/#|___/_____/_/#|_|##+#######################################################+YOU#DESTROYED#A#FIFTEEN#MILLION#DOLLAR+ROVER#ON#A#TWO#HUNDRED#MILLION#DOLLAR#MISSION!+');
  die();
 }
 
 public function end() {
  echo 'The last picture you see from the rover\'s camera is:\n';
  echo decipher('#########__.,,------.._+######,`"###_######_###"`.+#####/.__,#._##-=-#_"`####Y+####(.____.-.`######""`###j+#####VvvvvvV`.Y,.####_.,-`#######,#####,#####,+########Y####||,###`"\#########,/####,/####./+########|###,`##,#####`-..,`_,`/___,`/###,`/###,+###..##,;,,`,-`"\,`##,##.#####`#####`#""`#`--,/####..#..+#,`.#`.`---`#####`,#/##,#Y#-=-####,`###,###,.#.`-..||_||#..+ff\\`.#`._########/f#,`j#j#,#,`#,###,#f#,##\=\#Y###||#||`||_..+l`#\`#`.`."`-..,-`#j##/./#/,#,#/#,#/#/l#\###\=\l###||#``#||#||...+#`##`###`-._#`-.,-/#,`#/`"/-/-/-/-"```"`.`.##``.\--``--..``_``#||#,+############"`-_,`,##,`##f####,###/######`._####``._#####,##`-.``//#########,+##########,-"``#_.,-`####l_,-`_,,`##########"`-._#.#"`.#/|#####`.`\#,#######|+########,`,.,-`"##########\=)#,`-.#########,####`-`._`.V#|#######\#//#..#.#/j+########|f\\###############`._#)-."`.#####/|#########`.|#|########`.`-||-\\/+########l`#\`#################"`._###"`--`#j##########j`#j##########`-`---`+#########`##`#####################"`,-##,`/#######,-`"##/+#################################,`",__,-`#######/,,#,-`+#################################Vvv`############VVv`+');
  echo 'TTFN,BYE!'.PHP_EOL;
 }

};

$rover_game=new rover_console;

