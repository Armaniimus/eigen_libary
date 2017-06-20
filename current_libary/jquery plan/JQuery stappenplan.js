//1. create files
    //js.js
    //index.html

//2. build basic html
    //add 3 li elements with thing inside of them
    //add 1 a elements with an id named 'hi'
    //add 1 a elements with an class named 'class'

//3 link the js.js file
    //add to html --> <script src="js.js"></script>
    //add to js.js -> alert('hi')
    //load index.html in the browser

//4. link the cdn
    // google jQuery CDN
    // add the top most jQuery named 3x jQuery

//5. create first line
    // jQuery('li').text('hij doe het');

//6. show dollar sign
    // $('li').text('hij doe het');

    //--

//7. Using variabless
    // 7.1  set variable name
        // var $name = $('#hi').text();

    // 7.2 use this variable
        // $name

// 8. using other methods
    // 8.1 add delay
        // $('li').delay(800);
    // 8.2 add fadeout
        // $('li').fadeOut(400);

// 9. put it inside function
	// 9.1 dump inside a function
		// function mijnF() {
		      // $('li').text($name);
		      // $('li').delay(800);
		      // $('li').fadeOut(400);
		// };

	// 9.2 test function
		// mijnF();

	// 9.3 make an onclick
		// $('li').on('click', mijnF );

	// 9.4 change li to this
		// $(this).text($name)

// 10. ways to make the code smaller

	// 10.1 show method chaining
		// .delay(800).fadeOut(400);

	// 10.2. Rebuild into a callback function
		// $('li').on('click', function() {
			// $(this).text($name).delay(800).fadeOut(400);
		//	});

	// 10.3 include the variable into the command
		// $('li').on('click', function() {
			// $(this).text($('#hi').text()).delay(800).fadeOut(400);
		// });
