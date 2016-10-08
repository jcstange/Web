<!doctype html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<head><title>JCSTANGE.INFO</title></head>
<link rel="stylesheet" href="style/style.css" media="screen" /> 
<?php include_once("template_header.php");?>
<body>
<div id="content">
	<div id="title">
        <p id="texto1">Skydiver Altimeter</p>
        </div>
        <div id="intro"><p id="texto2">This device is a wearable computer for Skydivers. Beyond to measure relative altitude, this device also makes a counting of rising time, freefly time and navigation time. It gets the altitude of the start point, and the deployment - opening of the parachute. <br/><br/>
		After a jump the user can access the history, and check all the data. Still, this device is equipped with a buzzer, producing an alarm sound on the setted up altitudes, for freefall and navigation. <br></div>
        <div id="hardware">
        <p id="texto2">Hardware<br></p>  
        <div id="box"><div id = "phototext"><a href="images/Altimetro/promini.jpg"><img src="images/Altimetro/promini.jpg" width="120px" heigh="120px"/></a></div><div id="textphoto">Atmel328p 16MHz 5V(Anduino Pro Mini)</div></div>
        <div id="box"><div id = "phototext"><a href="images/Altimetro/barometer.jpg"><img src="images/Altimetro/barometer.jpg" width="120px" heigh="120px"/></a></div><div id="textphoto">Barometric sensor (BMP180)</div></div>
        <div id="box"><div id = "phototext"><a href="images/Altimetro/buzzer.jpg"><img src="images/Altimetro/buzzer.jpg" width="120px" heigh="120px"/></a></div><div id="textphoto">Buzzer</div></div>
         <div id="box"><div id = "phototext"><a href="images/Altimetro/nokia.jpg"><img src="images/Altimetro/nokia.jpg" width="120px" heigh="120px"/></a></div><div id="textphoto">Nokia 5110 LCD Screen (84x48)</div></div>
         <div id="box"><div id = "phototext"><a href="images/Altimetro/pcblayer.jpg"><img src="images/Altimetro/pcblayer.jpg" width="120px" heigh="120px"/></a></div><div id="textphoto">PCB Single Layer</div></div>
        <br>
        </div>
        <div id="pcb">
         <p id="texto2">PCB Designs (Altium):<br></p>
       	<div id="box"><div id = "phototext"><a href="images/Altimetro/altpromini.JPG"><img src="images/Altimetro/altpromini.JPG" height="120px" width="120px"/></a></div><div id="textphoto">Atmel328p 16MHz 5V(Anduino Pro Mini)</div></div>
        <div id="box"><div id = "phototext"><a href="images/Altimetro/altbarometer.JPG"><img src="images/Altimetro/altbarometer.JPG" height="120px" width="120px"/></a></div><div id="textphoto"> Barometric sensor (BMP180) </div></div>
         <div id="box"><div id = "phototext"><a href="images/Altimetro/altnokia.JPG"><img src="images/Altimetro/altnokia.JPG" height="120px" width="120px"/></a></div><div id="textphoto">Nokia 5110 LCD Screen (84x48)</div> </div> 
          <div id="box"> <div id = "phototext"><a href="images/Altimetro/altpcb.jpg"><img src="images/Altimetro/altpcb.jpg" height="120px" width="120px"/></a></div><div id="textphoto">PCB Layout</div></div>  
           <div id="box"><div id = "phototext"><a href="images/Altimetro/btnpanel.jpg"><img src="images/Altimetro/btnpanel.jpg" height="120px" width="120px"/></a></div><div id="textphoto">Button Panel</div></div> 
		</div>
        <div id=software>
               <div id="title">
				<p id="texto1">Software</p>
				</div>
               <p id="texto2">
               &radic; Pressure and Temperature Measurement;<br>
               &radic; Velocity Measure (Kinematic Equations);<br>
               &radic; Menu Screen;<br>
               &radic; Battery Level Measument<br>
               &radic; Setup Alarms for buzzer (EEPROM);<br>
               &radic; Log/History record and view (EEPROM);<br>
               &radic; Auto Sleeping;<br>
               &radic; Awaking by Interruption with pressure base reseting;<br>
               &radic; State Machine for Rising, Freefall and Navigation using velocity;<br>
               </p>             							
              <div id="imagens">
                 <div id="imagem"> <a href="images/Altimetro/folder.png"><img src="images/folder.png"/></a></div>  
				<div id="imagem"> <a href="images/Altimetro/pcb.png"><img src="images/pcb.png"/></a></div> 				 
             </div>
			 
			<div id="title">
				<p id="texto1">Screens</p>
				</div>
				<div id="imagens">
					<div id="imagem"> <a href="images/Altimetro/novatela1.jpg"><img src="images/Altimetro/novatela1.jpg"/></a></div>
					<p id="texto2">Main screen that shows the state (STB), temperature, relative altitude, falling velocity and baterry level<br/><br/></p>
				</div>
				<div id="imagens">
					<div id="imagem"> <a href="images/Altimetro/tela2.jpg"><img src=	"images/Altimetro/tela2.jpg"/></a></div>
					<p id="texto2">Menu screen, where you can choose to config or see data from the last jumps<br/><br/></p>
				</div>
				<div id="imagens">
					<div id="imagem"> <a href="images/Altimetro/tela3.jpg"><img src=	"images/Altimetro/tela3.jpg"/></a></div>
					<p id="texto2">Alarms configuration screen, where you can set two alarms for freefall and two alarms for navigation,<br/><br/></p>
				</div>
				<div id="imagens">
					<div id="imagem"> <a href="images/Altimetro/tela4.jpg"><img src="images/Altimetro/tela4.jpg"/></a></div>
					<p id="texto2">History screen with rise time(RT), start altitude (SP), freefall time (FFT), deploiment altitude (DP), and navigation time (NAVT).<br/><br/></p>
				</div>
			 
			<div id="title">
				<p id="texto1">Update Journal</p>
			</div>
			             <p id="texto2">
                         The first prototype test was in April 24, 2014. The function altimeter was working properly. However, because some error in the time between acquisitions for velocity (form [x0-x1]/t) the change in the state machine doesn't work at all, it counted the whole time like it was the rising time.</p>
                         
                         <p id="texto2">The second test was in May 1st, 2014. The state machine worked properly this time, and the climbing velocity was ok, but after 3000 ft we got some turbulence and the state changed inside the airplane - I need to put some delay on this. The device has turned off during the navigation and didn't record log.<br>
                         I've changed the program to get the mean velocity between the last four acquisitions in a queue, and to wait for 2 seconds to change the state, with this a would prevent that the state changes by mistake<br/><br/></p>
                       
             <!--<div id="imagens">
              <div id="imagem"><a href="images/Altimetro/novatela1.jpg"><img src="images/Altimetro/novatela1.jpg"/></a></div>
              <div id="imagem"><a href="images/Altimetro/holder.jpg"> <img src="images/Altimetro/holder.jpg"/></a></div>
              <div id="imagem"><a href="images/Altimetro/carcaca.jpg"> <img src="images/Altimetro/carcaca.jpg"/></a></div><br/>
              </div>-->
               <br/>
               </div>
               </div>
               	<?php include_once("template_footer.php");?>
</body>
</html>