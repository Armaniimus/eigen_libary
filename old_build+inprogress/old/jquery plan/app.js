var $name = $('#hi').text();

function mijnF() {
	$(this).text($name);
	$(this).delay(800);
	$(this).fadeOut(400)
};

$('li').on('click', mijnF);
