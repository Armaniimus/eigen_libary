Copyright notes
I am not responsible for any use of this library or any damages that may occur from using this library

==============
Layout styles
==============
    This library supports 4 layout styles and each style has a different reach.
    A style is used until the with of the screen is the same or higher as the starting point of the next specified style.
        "col-n"    --> 0px and upwards
        "col-m-n"  --> 600px and upwards
        "col-la-n" --> 768px and upwards
        "col-h-n"  --> 1000px and upwards


    Each style has 12 different cols.
    Its recommended to always use those 12 cols to prevent unwanted side effects for a row to be complete.

    Its also required to use those cols with float/float-l or float-r
    For example if you want 2 divs next to each other you can use this code

    ==simple example==
        The class col-6 makes the element 50% of the with of the parent and float floats the element to the left.

        <div class="col-6 float"></div>
        <div class="col-6 float"></div>

    ==recommended use==
        Recommended is to create a wrapper div around them and give them the class row,
        This clears the float and prevents unexpected things from happening if you have multiple lines with 12 cols due to the nature of float.

        ***note***
        ***if you give an element the class col-12, col-m-12, col-la-12 or col-h-12 float cant break the layout if you don't set it inside a row***

        <div class="row">
            <div class="col-6 float"></div>
            <div class="col-6 float"></div>
        </div>

    ==multiple breakpoints==
        when you want to make elements  to take a higher percentage of the screen on broader screens you can do it as shown in the following example.

        <div class="row">
            <div class="col-12 col-m-6 float"></div>
            <div class="col-12 col-m-6 float"></div>
        </div>

===============
 button styles
===============
    A button can be given 4 style classes
        a shape class
        a color class
        a size class
        special hover effect class

    ==Shape classes==
        .button --> is a button with slightly rounded corners
        .button-roundcorner --> is a button with with rounded corners

    ==color classes==
        .button--color-orange --> is a button with a orange background and orange styling effects

    ==size classes==
        .button--size-s --> is a small button
        .button--size-m -->  is a medium button
        .button--size-la -->  is a large button
        .button--size-h -->  is a huge button

    ==special hover effects==
        .button--hover-underline --> underlines the text in the button on the hover over event.

=============
 font styles
=============
 No description yet



===============
 header styles
===============
 Not yet implemented



==================
 special elements
==================
 Not yet implemented



=============
 user_styles
=============
 No description yet



=================
 standard styles
=================
 No description yet



==================
 Developer styles
==================
 No description yet
