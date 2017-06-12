var $name = $('#hi').text();

function mijnF() {
	$(this).text($name);
	$(this).delay(800);
	$(this).fadeOut(400)
};

$('li').on('click', mijnF);


//-2. build basic html

//-1. link scripts
	//0.1 show CDN
	//0.2 link javascript

//0. create first line
	//jQuery('li').text('hij doe het');

//1. show dollar sign
	//$('li').text('hij doe het');

//2. Using variables
	// 2.1  set variable name
		// var $name = $('#hi').text();

	// 2.2 use this variable
		// $name

//3 using other methods
	//3.1 add delay
		//$('li').delay(800);
	//3.2 add fadeout
		//$('li').fadeOut(400);

//4. put it inside function
	//4.1 dump inside a function
		//function mijnF() {
		//	$('li').text($name);
		//	$('li').delay(800);
		//	$('li').fadeOut(400);
		//};

	//4.2 test function
		//mijnF();

	//4.3 make an onclick
		//$('li').on('click', mijnF );

	//4.4 change li to this
		//$(this).text($name)



//5. ways to make the code smaller

	//5.1 show method chaining
		// .delay(800).fadeOut(400);

	//5.2. Rebuild into a callback function

		//$('li').on('click', function() {
		//		$(this).text($name).delay(800).fadeOut(400);
		//	});

	//5.3 include the variable into the command
		//$('li').on('click', function() {
		//	$(this).text($('#hi').text()).delay(800).fadeOut(400);
		//});
