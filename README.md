<div align="center">
    <h1>Skilio Web Framework</h1>
    <i>Hi, welcome to my project, hope you will like it !</i> <br />
    <b> > ENJOY < </b>
</div>
<div align="center">
     <h2>Languages used for this project</h2>
     <sub> Web </sub>
     <br />
     <a href="https://fr.wikipedia.org/wiki/Hypertext_Markup_Language">
        <img height="70" alt="HTML" src="https://th.bing.com/th/id/R.36269ef32e1901bd994603c2cb801be1?rik=AHcjqaBm9jncxA&pid=ImgRaw&r=0" />
     </a>
     <a href="https://en.wikipedia.org/wiki/CSS">
        <img height="70" alt="CSS" src="https://www.kindpng.com/picc/m/464-4640184_css3-png-download-css-icon-transparent-png.png" />
     </a>
     <a href="https://www.javascript.com/">
        <img height="70" alt="Javascript" src="https://p7.hiclipart.com/preview/793/545/309/javascript-programmer-node-js-web-application-vector-markup-language.jpg" />
     </a>
     <a href="https://www.php.net/">
        <img height="70" alt="PHP" src="https://th.bing.com/th/id/OIP.bWdhB1dI1fYIYszoMnb_7AAAAA?pid=ImgDet&rs=1" />
     </a>
     <br />
     <sub> Databases </sub>
     <br />
     <a href="https://en.wikipedia.org/wiki/SQL">
        <img height="70" alt="SQL" src="https://img.favpng.com/16/0/21/sql-server-logo-png-favpng-pXyDxFrAFhWQUeLq6SrgeND1g.jpg" />
     </a>
    <br />
    <sub> Libraries </sub>
    <br />
</div>
<div align="center">
    <h2 align="center">Table of Contents</h2>
  
   [What's the project ?](#the-project)   <br>
   [Features](#features)                   <br>
   [Elements to do](#elements-to-do)       <br>
   [Contributors](#contributors)
</div>

<div align="center">
    
   ## <p align="center">The project</p>
   The project consists to create an entire PHP web framework (Skilio)
   
   ## <p align="center">Features</p>
   ### **ALPHA 0.1**
   Base source code library for the framework.
   Implements the routing and redirections
   ### **ALPHA 0.2**
   Upgrade source code and implement more content
   ### **ALPHA 1.0**
   **Patch note**
   <br> ********* <br>
   **SOURCES REWORK** <br>
   -- <br>
   Complete rework of the code. Creating a core folder which contains all the base classes to automatically redirect and set the parameters in back-end.
   <br> ********* <br>
   **COMPONENT** <br>
   -- <br>
   Create a new view usefull element : Component
   The components can be created by you in the component folder, with the ".component.php" extension and can be used in a view of your choice. It permits the creation of separated content for the view. <br>
   Exemple : Create a "card" using css to display a user which can be reusable in differents views with the same appearance.
   A component can accept datas from the view with an array and use the array variable `$componentValues` into the card with the key you created before.
   Simple to use :
   Set a component : `<?php echo Component::Set("fileName"/*Without extension*/, ["datas" => "data"]) ?>`
   Display datas into the component : `<?= $componentValues["datas"] ?>` <br>
   The components can be used with loops and conditions too (in and out the component)
   Exemple of loop:
</div>

   >
        foreach ($users as $key => $user) 
        {
            echo Component::Set("User", ["user" => $user]);
        }
        
<div align="center">

   <br> ********* <br>
   **Errors** <br>
   -- <br>
   Errors corrections for the redirect
   Errors corrections for the native css not found
   <br> ********* <br>
   **Demo** <br>
   -- <br>
   Adding a basic database script and some base pages for a mini demo
   
   ## <p align="center">Elements to do</p>

   ## <p align="center">Contributors</p>
   <b>
       <a href="https://github.com/dam277">Damien Loup</a>
   </b>                     
   <br>
   <a href="https://dam277.github.io/P_Portfolio/">Portfolio</a>       <br>
   <a href="https://skiliox.com">My main project</a>
   </p>
</div>
